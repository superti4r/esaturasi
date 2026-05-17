<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    public function getAnnouncement()
    {
        $announcements = Announcement::whereHas('archive', function ($query) {
                $query->where('status', 'Active'); // ← filter archive aktif
            })
            ->get()
            ->map(function ($item) {
                $imageBase64 = null;

                if (preg_match('/<img src="data:image\/[^;]+;base64,([^"]+)"/', $item->content, $matches)) {
                    $imageBase64 = $matches[1];
                }

                return [
                    'id'         => $item->id,
                    'title'      => $item->title,
                    'content'    => $item->content,
                    'image'      => $imageBase64,
                    'created_at' => $item->created_at,
                ];
            });

        return response()->json($announcements);
    }

    public function index()
    {
        $announcements = Announcement::with('archive')
            ->whereHas('archive', function ($query) {
                $query->where('status', 'Active'); // ← filter archive aktif
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id'           => $item->id,
                    'title'        => $item->title,
                    'content'      => $item->content,
                    'archive_file' => $item->archive?->file_path ?? null,
                    'created_at'   => $item->created_at,
                ];
            });

        return response()->json([
            'status'        => 'success',
            'announcements' => $announcements,
        ]);
    }
}