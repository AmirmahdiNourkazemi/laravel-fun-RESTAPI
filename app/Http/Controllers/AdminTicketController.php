<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class AdminTicketController extends Controller
{
    public function getTickets(Request $request)
    {
        $data = $request->validate([
            'per_page' => 'integer',
            'mobile' => 'string|nullable',
            'category' => 'integer|nullable',
            'status' => ['integer', Rule::in(Ticket::STATUSES), 'nullable'],
        ]);

        $tickets = Ticket::with('user')
            ->orderByDesc('created_at')
            // ->filter([
            //     'status' => $data['status'] ?? null,
            //     'category' => $data['category'] ?? null,
            //     'mobile' => $data['mobile'] ?? null,
            // ])
            ->
            paginate($data['per_page'] ?? 30);

        return $tickets;
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

        $ticket->update([
            'status' => Ticket::STATUSES['answered']
        ]);



        return response()->json([
            'message' => $message,
            'media' => $media ?? null
        ]);
    }

    public function showTicket(Request $request, $uuid)
    {
        if (!$ticket = Ticket::with(['messages.user', 'user'])->where('uuid', $uuid)->first()) {
            return response()->json([
                'message' => 'ticket not found'
            ], 404);
        }

        return $ticket;
    }

    public function changeTicketStatus(Request $request, $uuid)
    {
        $data = $request->validate([
            'status' => ['integer', Rule::in(Ticket::STATUSES), 'required']
        ]);

        if (!$ticket = Ticket::where('uuid', $uuid)->first()) {
            return response()->json([
                'message' => 'ticket not found'
            ], 404);
        }

        $ticket->update([
            'status' => $data['status']
        ]);

        return $ticket;
    }
}
