<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inquiry extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['product_id', 'assigned_to', 'requested_quantity', 'machine_model', 'material', 'name', 'company', 'email', 'phone', 'subject', 'message', 'status', 'priority', 'quoted_amount', 'quoted_at', 'next_follow_up_at', 'internal_notes', 'read_at'];

    protected function casts(): array
    {
        return ['requested_quantity' => 'integer', 'quoted_amount' => 'decimal:2', 'quoted_at' => 'datetime', 'next_follow_up_at' => 'datetime', 'read_at' => 'datetime'];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function scopeOpen(Builder $query): Builder
    {
        return $query->whereNotIn('status', ['won', 'lost', 'resolved']);
    }
}
