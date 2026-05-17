<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'formatted_amount' => $this->formatted_amount,
            'type' => $this->type,
            'type_label' => $this->type_label,
            'note' => $this->note,
            'expense_date' => $this->expense_date,
            'formatted_date' => $this->formatted_date,

            'category' => [
                'id' => $this->category?->id,
                'name' => $this->category?->name,
            ],

            'user' => [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
                'email' => $this->user?->email,
            ],

            'created_at' => $this->created_at,
        ];
    }
}
