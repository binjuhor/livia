<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property float       quantity
 * @property float       unit_amount
 * @property int         id
 * @property string      description
 * @property string      jira_key
 * @property int         invoice_id
 * @property string|null xero_id
 */
class InvoiceLineItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'xero_id',
        'invoice_id',
        'description',
        'quantity',
        'unit_amount'
    ];

    protected $casts = [
        'quantity' => 'float'
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function getTotalAttribute(): float
    {
        return $this->quantity * $this->unit_amount;
    }

    public function isFree(): bool
    {
        return 0.0 === $this->quantity;
    }
}
