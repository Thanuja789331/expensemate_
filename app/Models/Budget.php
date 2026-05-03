<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'limit_amount',
        'period',
        'period_year',
        'period_month',
        'alert_sent'
    ];

    protected $casts = [
        'alert_sent'   => 'boolean',
        'limit_amount' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
