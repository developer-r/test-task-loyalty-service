<?php

namespace App\Http\Resources\Transactions;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'account_id' => $this->resource->account_id,
            'points_rule' => $this->resource->points_rule,
            'points_amount' => $this->resource->points_amount,
            'description' => $this->resource->description,
            'payment_id' => $this->resource->payment_id,
            'payment_amount' => $this->resource->payment_amount,
            'payment_time' => $this->resource->payment_time,
        ];
    }
}
