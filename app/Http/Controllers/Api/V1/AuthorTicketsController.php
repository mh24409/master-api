<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\replaceTicketRequest;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Resources\Api\V1\TicketResource;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class AuthorTicketsController extends ApiController
{
    public function index($author_id, TicketFilter $filters)
    {
        return TicketResource::collection(Ticket::where('user_id', $author_id)->filter($filters)->paginate());
    }

    public function store(StoreTicketRequest $request, $author_id)
    {
        try {
            $user = User::findOrFail($author_id);
        } catch (ModelNotFoundException $e) {
            return $this->ok('User Not Exist', ['error' => 'User Not Exist']);
        }
        $ticket = Ticket::create($request->mappedAttributes());
        return $this->ok('Ticket Created Successfully', [
            'ticket' => new TicketResource($ticket)
        ]);
    }

    public function destroy($author_id, $ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);
            if ($ticket->user_id != $author_id) {
                return $this->error('Ticket Not belongs to this user', 404);
            }
            $ticket->delete();
            return $this->ok('Ticket Deleted Successfully', []);
        } catch (ModelNotFoundException $e) {
            return $this->error('Ticket Not Exist', 404);
        }
    }

    public function replace(replaceTicketRequest $request, $author_id, $ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);
            if ($ticket->user_id == $author_id) {
                $ticket->update($request->mappedAttributes());
                return $this->ok('Ticket Updated Successfully', [
                    'ticket' => new TicketResource($ticket)
                ]);
            }
            return $this->error('Ticket Not belongs to this user', 404);
        } catch (ModelNotFoundException $e) {
            return $this->error('Ticket Not Exist', 404);
        }
    }

    public function update(UpdateTicketRequest $request, $author_id, $ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);
            if ($ticket->user_id == $author_id) {
                $ticket->update($request->mappedAttributes());
                return $this->ok('Ticket Updated Successfully', [
                    'ticket' => new TicketResource($ticket)
                ]);
            }
            return $this->error('Ticket Not belongs to this user', 404);
        } catch (ModelNotFoundException $e) {
            return $this->error('Ticket Not Exist', 404);
        }
    }
}
