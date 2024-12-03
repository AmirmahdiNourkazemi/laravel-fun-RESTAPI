<?php

namespace App\Http\Controllers;
use App\Models\Comment;

use Illuminate\Http\Request;
use App\Models\Project;
class CommentController extends Controller
{
    public function index() 
    {
        $comments = Comment::all();
        return $comments;
    }
    public function verifyProjectComment(Request $request, $uuid, $commentUuid)
    {
        if (!$project = Project::where('uuid', $uuid)->first()) {
            return response()->json([
                'message' => 'project not found'
            ], 404);
        }

        if (!$comment = $project->comments()->where('uuid', $commentUuid)->first()) {
            return response()->json([
                'message' => 'project not found'
            ], 404);
        }

        $comment->verified = true;
        $comment->save();

        return response()->json([
            'message' => 'success'
        ]);
    }

    public function getProjectComments(Request $request, $uuid)
    {
        $data = $request->validate([
            'per_page' => 'integer',
        ]);

        if (!$project = Project::where('uuid', $uuid)->first()) {
            return response()->json([
                'message' => 'project not found'
            ], 404);
        }

        return $project->comments()
            ->whereNull('parent_id')
            ->with(['user', 'replies'])
            ->paginate($data['per_page'] ?? 30);
    }
}
