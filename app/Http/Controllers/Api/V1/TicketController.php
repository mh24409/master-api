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
use App\Traits\ApiResponses;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TicketController extends ApiController
{
    use ApiResponses;

    public function index(TicketFilter $filters)
    {
        return TicketResource::collection(Ticket::filter($filters)->paginate());
    }

    public function store(StoreTicketRequest $request)
    {
        try {
            $user = User::findOrFail($request['data.relations.author.id']);
        } catch (ModelNotFoundException $e) {
            return $this->ok('User Not Exist', ['error' => 'User Not Exist']);
        }
        $ticket = Ticket::create($request->mappedAttributes());
        return $this->ok('Ticket Created Successfully', [
            'ticket' => new TicketResource($ticket)
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            if ($this->include('author')) {
                return new TicketResource(Ticket::findOrFail($id)->load('author'));
            }
            return new TicketResource(Ticket::findOrFail($id));
        } catch (ModelNotFoundException $e) {
            return $this->error('Ticket Not Exist', 404);
        }
    }


    public function replace(replaceTicketRequest $request, $ticket_id)
    {

        try {
            $ticket = Ticket::findOrFail($ticket_id);

            $ticket->update($request->mappedAttributes());
            return $this->ok('Ticket Updated Successfully', [
                'ticket' => new TicketResource($ticket)
            ]);
        } catch (ModelNotFoundException $e) {
            return $this->error('Ticket Not Exist', 404);
        }
    }
    public function update(updateTicketRequest $request, $ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);
            $ticket->update($request->mappedAttributes());
            return $this->ok('Ticket Updated Successfully', [
                'ticket' => new TicketResource($ticket)
            ]);
        } catch (ModelNotFoundException $e) {
            return $this->error('Ticket Not Exist', 404);
        }
    }

    public function destroy($ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);
            $ticket->delete();
            return $this->ok('Ticket Deleted Successfully', []);
        } catch (ModelNotFoundException $e) {
            return $this->error('Ticket Not Exist', 404);
        }
    }
}
