<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\SubmissionAndAssessment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class SubmissiontaskController extends Controller
{
    public function store(Request $request)
    {
        Log::info($request->all());
        $student = $request->user();

        $request->validate([
            'task_id' => 'required|integer|exists:task,id',
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        try {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $extension = $file->getClientOriginalExtension();

                $originalName = $file->getClientOriginalName();
                $hashName = hash('sha256', $originalName . time() . $student->id) . '.' . $extension;

                $file->storeAs('pengumpulan_tugas', $hashName, 'public');

                $file_path = 'pengumpulan_tugas/' . $hashName;
            } else {
                return response()->json(['message' => 'File tidak ditemukan'], 400);
            }

            $submission = SubmissionAndAssessment::create([
                'task_id' => $request->task_id,
                'student_id' => $student->id,
                'file_path' => $file_path,
                'assignment' => null,
                'status' => 'submitted'
            ]);

            return response()->json([
                'message' => 'Tugas berhasil dikumpulkan',
                'data' => $submission,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }


    public function getSubmissionStatus($taskId)
    {
        $student = request()->user();

        $submission = SubmissionAndAssessment::where('task_id', $taskId)
            ->where('student_id', $student->id)
            ->first();

        if (!$submission) {
            return response()->json([
                'message' => 'Belum ada pengumpulan untuk tugas ini',
                'status' => 'not_submitted'
            ], 404);
        }

        $task = Task::find($taskId);

        return response()->json([
            'message' => 'Data pengumpulan tugas',
            'data' => [
                'id' => $submission->id,
                'task_id' => $submission->task_id,
                'student_id' => $submission->student_id,
                'file_path' => $submission->file_path,
                'status' => $submission->status,
                'created_at' => $submission->created_at,
                'task_title' => $task ? $task->title : null
            ]
        ]);
    }
}
