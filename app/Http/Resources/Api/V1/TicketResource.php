<?php

namespace App\Http\Resources\Api\V1;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class TicketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'ticket',
            'id' => $this->id,
            'attributes' => [
                'title' => $this->title,
                'description' => $this->when($request->routeIs('tickets.show'), $this->description),
                'status' => $this->status,
                'created' => $this->created_at->diffForHumans(),
                'updated' => $this->updated_at->diffForHumans(),
            ],
            'relations' => [
                'author' => [
                    'type' => 'user',
                    'id' => $this->user_id,
                    'attributes' => [
                        'name' => User::find($this->user_id)->name
                    ],
                    'links' => [
                        'self' => 'user todo',
                    ]
                ]
            ],
            'includes' => new AuthorResource($this->whenLoaded('author')),
            'links' => [
                'self' => Route('tickets.show', ['ticket' => $this->id]),
            ]
        ];
    }
}
