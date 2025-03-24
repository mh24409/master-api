<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class BaseTicketRequest extends FormRequest
{
    public function mappedAttributes(): array
    {
        $attributesMap = [
            'data.attributes.title' => 'title',
            'data.attributes.description' => 'description',
            'data.attributes.status' => 'status',
            'data.relations.author.id' => 'user_id',
        ];
        $attributesToUpdate = [];
        foreach ($attributesMap as $key => $value) {
            if ($this->has($key)) {
                $attributesToUpdate[$value] = $this->input($key);
            }
        }
        return $attributesToUpdate;
    }

    public function messages(): array
    {
        return [
            'data.attributes.status.in' => 'Status must be A, C, H or X',
            'data.relations.author.id.exists' => 'Invalid author id',
        ];
    }
}
