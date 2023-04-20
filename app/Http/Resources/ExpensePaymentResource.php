<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExpensePaymentResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'expense' => ExpenseResource::make($this->resource->expense),
            'start_date' => $this->resource->start_date->toDateTimeString(),
            'end_date' => $this->resource->end_date->toDateTimeString(),
        ]);
    }
}
