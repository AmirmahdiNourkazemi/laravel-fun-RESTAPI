<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Validation\Rule;
class TicketController extends Controller
{    public function index(Request $request)
    {
        $data = $request->validate([
            'per_page' => 'integer'
        ]);

        $tickets = Ticket::with('user')
            ->where('user_id', auth()->user()->id)
            ->orderByDesc('created_at')
            ->paginate($data['per_page'] ?? 30);
        return $tickets;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'string|required',
            'description' => 'string',
            'category' => ['integer', 'required', Rule::in(Ticket::CATEGORIES)],
            'message' => 'array',
            'message.text' => 'string',
            'message.files' => 'array',
            'message.files.*.file_name' => 'string',
            'message.files.*.file' => 'file',
        ]);

        $data['user_id'] = auth()->user()->id;
        $ticket = Ticket::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'category' => $data['category'],
            'user_id' =>  auth()->user()->id,
        ]);



        if (isset($data['message'])) {
            $message = $ticket->messages()->create([
                'user_id' => auth()->user()->id,
                'text' => $data['message']['text']
            ]);

            foreach ($data['message']['files'] ?? [] as $file) {
                $media = $message->addMedia($file['file']->path())
                    ->usingName($file['file_name'] ?? '')
                    ->usingFileName($file['file']->getClientOriginalName())
                    ->toMediaCollection('attachments');
            }
        }
        return $ticket;
    }

    public function storeMessage(Request $request, $uuid)
    {
        $data = $request->validate([
            'text' => 'string|required',
            'files' => 'array',
            'files.*.file_name' => 'string',
            'files.*.file' => 'file',
        ]);

        if (!$ticket = Ticket::where('uuid', $uuid)->first()) {
            return response()->json([
                'message' => 'ticket not found'
            ], 404);
        }

        $ticket->update([
            'status' => Ticket::STATUSES['open']
        ]);

        $message = $ticket->messages()->create([
            'user_id' => auth()->user()->id,
            'text' => $data['text']
        ]);

        foreach ($data['files'] ?? [] as $file) {
            $media = $message->addMedia($file['file']->path())
                ->usingName($file['file_name'] ?? '')
                ->usingFileName($file['file']->getClientOriginalName())
                ->toMediaCollection('attachments');
        }


        return response()->json([
            'message' => $message,
            'media' => $media ?? null
        ]);
    }

    public function show(Request $request, $uuid)
    {
        if (!$ticket = Ticket::with(['user', 'messages.user'])->where('uuid', $uuid)->first()) {
            return response()->json([
                'message' => 'ticket not found'
            ], 404);
        }

        return $ticket;
    }

    public function close(Request $request, $uuid)
    {
        if (!$ticket = Ticket::with(['user', 'messages.user'])->where('uuid', $uuid)->first()) {
            return response()->json([
                'message' => 'ticket not found'
            ], 404);
        }

        $ticket->status = Ticket::STATUSES['closed'];
        $ticket->save();

        return $ticket;
    }
}
