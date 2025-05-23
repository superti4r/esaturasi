<?php

namespace App\Http\Controllers\API;

use App\Models\Slugs;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    public function getPendingTasks($classroomId, $studentId)
    {
        Log::info("Memulai getPendingTasks dengan classroomId: $classroomId, studentId: $studentId");

        try {
            $schedules = \App\Models\Schedule::where('classroom_id', $classroomId)->pluck('id')->toArray();
            Log::info("Jadwal yang ditemukan untuk kelas $classroomId: " . count($schedules) . " jadwal");

            if (empty($schedules)) {
                Log::warning("Tidak ada jadwal yang ditemukan untuk kelas $classroomId");
                return $this->returnEmptyOrSampleResponse($studentId, $classroomId);
            }

            $slugs = Slugs::whereIn('schedule_id', $schedules)->pluck('id')->toArray();
            Log::info("Slug yang ditemukan untuk jadwal kelas: " . count($slugs) . " slug");

            if (empty($slugs)) {
                Log::warning("Tidak ada slug yang ditemukan untuk jadwal kelas $classroomId");
                return $this->returnEmptyOrSampleResponse($studentId, $classroomId);
            }

            $tasks = Task::whereIn('slug_id', $slugs)
                ->with(['slug.schedule.subject'])
                ->get();

            Log::info("Total tugas yang ditemukan: " . $tasks->count() . " tugas");

            if ($tasks->isEmpty()) {
                Log::warning("Tidak ada tugas yang ditemukan untuk kelas $classroomId");
                return $this->returnEmptyOrSampleResponse($studentId, $classroomId);
            }

            $taskIds = $tasks->pluck('id')->toArray();

            $submittedTaskIds = \App\Models\SubmissionAndAssessment::where('student_id', $studentId)
                ->whereIn('task_id', $taskIds)
                ->pluck('task_id')
                ->toArray();

            Log::info("Tugas yang sudah dikumpulkan oleh siswa $studentId: " . count($submittedTaskIds) . " tugas");

            $pendingTasks = [];

            foreach ($tasks as $task) {
                if (!in_array($task->id, $submittedTaskIds)) {
                    Log::info("Memproses tugas yang belum dikumpulkan: ID {$task->id}, Judul: {$task->title}");

                    $subjectName = 'Tidak Ada Mata Pelajaran';
                    try {
                        if ($task->slug && $task->slug->schedule && $task->slug->schedule->subject) {
                            $subjectName = $task->slug->schedule->subject->name;
                        }
                    } catch (\Exception $e) {
                        Log::error("Error saat mengambil nama mata pelajaran untuk tugas {$task->id}: " . $e->getMessage());
                    }

                    try {
                        $deadline = new \DateTime($task->deadline);
                        $now = new \DateTime();

                        $isUrgent = $deadline < $now;

                        if ($deadline->format('Y-m-d') === $now->format('Y-m-d')) {
                            $deadlineFormatted = 'Hari ini, ' . $deadline->format('H:i');
                        } elseif ($deadline->format('Y-m-d') === (clone $now)->modify('+1 day')->format('Y-m-d')) {
                            $deadlineFormatted = 'Besok, ' . $deadline->format('H:i');
                        } else {
                            $deadlineFormatted = $deadline->format('d/m/Y, H:i');
                        }

                        $pendingTasks[] = [
                            'id' => $task->id,
                            'title' => $task->title,
                            'course' => $subjectName,
                            'deadline' => $task->deadline,
                            'deadline_formatted' => $deadlineFormatted,
                            'isUrgent' => $isUrgent
                        ];

                        Log::info("Berhasil menambahkan tugas ID {$task->id} ke daftar tugas yang pending");
                    } catch (\Exception $e) {
                        Log::error("Error saat memproses deadline untuk tugas {$task->id}: " . $e->getMessage());

                        $pendingTasks[] = [
                            'id' => $task->id,
                            'title' => $task->title,
                            'course' => $subjectName,
                            'deadline' => $task->deadline ?? 'Tidak diketahui',
                            'deadline_formatted' => 'Format tanggal tidak valid',
                            'isUrgent' => false
                        ];

                        Log::info("Berhasil menambahkan tugas ID {$task->id} ke daftar tugas yang pending (dengan penanganan error)");
                    }
                }
            }

            Log::info("Total tugas yang belum dikumpulkan: " . count($pendingTasks));

            if (empty($pendingTasks)) {
                Log::info("Tidak ada tugas yang pending - menambahkan contoh tugas untuk testing");
                $pendingTasks[] = [
                    'id' => 999999,
                    'title' => 'Tugas Debugging (Task Sampel)',
                    'course' => 'Debug 101',
                    'deadline' => date('Y-m-d H:i:s', strtotime('+2 days')),
                    'deadline_formatted' => 'Dalam 2 hari',
                    'isUrgent' => false
                ];
            }

            return response()->json([
                'tasks' => $pendingTasks,
                'debug_info' => [
                    'total_tasks_queried' => $tasks->count(),
                    'submitted_tasks' => count($submittedTaskIds),
                    'pending_tasks' => count($pendingTasks),
                    'student_id' => $studentId,
                    'classroom_id' => $classroomId
                ]
            ]);
        } catch (\Exception $e) {
            Log::error("Error dalam getPendingTasks: " . $e->getMessage());
            Log::error("Stack trace: " . $e->getTraceAsString());

            return response()->json([
                'tasks' => [
                    [
                        'id' => 999999,
                        'title' => 'Error: ' . substr($e->getMessage(), 0, 50) . '...',
                        'course' => 'Debug 101',
                        'deadline' => date('Y-m-d H:i:s', strtotime('+1 days')),
                        'deadline_formatted' => 'Terjadi error saat memuat tugas',
                        'isUrgent' => true
                    ]
                ],
                'error' => true,
                'message' => 'Terjadi error: ' . $e->getMessage()
            ]);
        }
    }

    private function returnEmptyOrSampleResponse($studentId, $classroomId)
    {
        return response()->json([
            'tasks' => [
                [
                    'id' => 999999,
                    'title' => 'Tugas Debugging (Task Sampel)',
                    'course' => 'Debug 101',
                    'deadline' => date('Y-m-d H:i:s', strtotime('+2 days')),
                    'deadline_formatted' => 'Dalam 2 hari',
                    'isUrgent' => false
                ]
            ],
            'debug_info' => [
                'total_tasks_queried' => 0,
                'submitted_tasks' => 0,
                'pending_tasks' => 0,
                'student_id' => $studentId,
                'classroom_id' => $classroomId,
                'note' => 'Tidak ada tugas yang ditemukan - menampilkan contoh tugas'
            ]
        ]);
    }

    public function getTugas()
    {
        $student = Auth::user();
        if (!$student) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $studentId = $student->id;

        $tugas = Task::with([
            'slug.schedule.teacher',
            'slug.schedule.subject',
            'submissions' => function ($query) use ($studentId) {
                $query->where('student_id', $studentId);
            }
        ])->get();

        $formatted = $tugas->map(function ($task) {
            $submission = $task->submissions->first();

            return [
                'id' => $task->id,
                'judul' => $task->title,
                'deskripsi' => $task->description,
                'guru' => optional($task->slug->schedule->teacher)->name ?? 'Guru tidak diketahui',
                'mata_pelajaran' => optional($task->slug->schedule->subject)->name ?? 'Mata pelajaran tidak diketahui',
                'deadline' => $task->deadline,
                'file_path' => $task->file_path,
                'attachments' => $task->file_path ? 1 : 0,
                'status' => $submission ? $submission->status : 'Belum Dikerjakan',
                'score' => $submission ? $submission->assignment : null,
            ];
        });

        return response()->json($formatted);
    }
}
