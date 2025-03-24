<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class AuthorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'user',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                $this->mergeWhen($request->routeIs('authors.show'), [
                    'email' => $this->email,
                    'email_verified_at' => $this->email_verified_at,
                    'created' => $this->created_at ? $this->created_at->diffForHumans() : null,
                    'updated' => $this->updated_at ? $this->updated_at->diffForHumans() : null,
                ]),
            ],
            'includes' => [
                'tickets' => TicketResource::collection($this->whenLoaded('tickets')),
            ],
            'links' => [
                'self' => route('authors.show', $this->id)
            ]
        ];
    }
}
