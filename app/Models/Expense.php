<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'type',
        'amount',
        'note',
        'expense_date'
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount'       => 'decimal:2',
    ];

    // ── RELATIONSHIPS ────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // ── QUERY SCOPES ─────────────────────────────
    // Filter by user
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Filter by type
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    // Filter this month
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('expense_date', now()->month)
                     ->whereYear('expense_date', now()->year);
    }

    // Filter by date range
    public function scopeDateRange($query, $from, $to)
    {
        return $query->whereBetween('expense_date', [$from, $to]);
    }

    // ── ACCESSORS ────────────────────────────────
    // Format amount with + or - sign
    protected function formattedAmount(): Attribute
    {
        return Attribute::make(
            get: fn() => ($this->type === 'income' ? '+' : '-')
                        . '$' . number_format($this->amount, 2)
        );
    }

    // Format date nicely
    protected function formattedDate(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->expense_date->format('d M Y')
        );
    }

    // Get type with capital first letter
    protected function typeLabel(): Attribute
    {
        return Attribute::make(
            get: fn() => ucfirst($this->type)
        );
    }

    // ── MUTATORS ─────────────────────────────────
    // Always store amount as positive number
    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value,
            set: fn($value) => abs((float) $value)
        );
    }

    // Always store note trimmed
    protected function note(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value,
            set: fn($value) => $value ? trim($value) : null
        );
    }
}
