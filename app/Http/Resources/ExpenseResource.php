<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,

            // Type
            'type'             => $this->type,
            'type_label'       => ucfirst($this->type),

            // Amount
            'amount'           => (float) $this->amount,
            'formatted_amount' => $this->formattedAmount(),

            // Details
            'note'             => $this->note ?? '',
            'expense_date'     => $this->expense_date
                                    ? \Carbon\Carbon::parse($this->expense_date)->format('d M Y')
                                    : null,

            // Relationships
            'category'         => $this->whenLoaded('category', fn() => [
                'id'   => $this->category->id,
                'name' => $this->category->name,
            ]),

            'user'             => $this->whenLoaded('user', fn() => [
                'id'   => $this->user->id,
                'name' => $this->user->name,
            ]),

            // Timestamps
            'created_at'       => $this->created_at
                                    ? $this->created_at->format('d M Y, h:i A')
                                    : null,
        ];
    }

    // Helper: format amount with + or - sign
    private function formattedAmount(): string
    {
        $amount = number_format((float) $this->amount, 2);

        return $this->type === 'income'
            ? "+\${$amount}"
            : "-\${$amount}";
    }
}
