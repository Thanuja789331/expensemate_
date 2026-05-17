<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BudgetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'limit_amount' => $this->limit_amount,
            'month' => $this->month,
            'year' => $this->year,

            'category' => [
                'id' => $this->category?->id,
                'name' => $this->category?->name,
            ],
        ];
    }
}
