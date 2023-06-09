<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublishSetQuestionResource extends DefaultSetQuestionResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            "username" => $this->username->name,
            "updated_at" => getTime($this->created_at),
        ]);
    }
}
