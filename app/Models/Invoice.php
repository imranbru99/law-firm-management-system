<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'invoice_date' => 'date',
            'due_date' => 'date',
            'sub_total' => 'decimal:2',
            'discount' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'net_total' => 'decimal:2',
            'tax_rate' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'grand_total' => 'decimal:2',
            'paid' => 'decimal:2',
            'due' => 'decimal:2',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function case(): BelongsTo
    {
        return $this->belongsTo(LegalCase::class, 'case_id');
    }

    public function tax(): BelongsTo
    {
        return $this->belongsTo(Tax::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function timeEntries(): HasMany
    {
        return $this->hasMany(TimeEntry::class);
    }

    public function recalculate(): void
    {
        $subTotal = $this->items()->sum('total');

        if ($this->discount_type === 'percentage') {
            $discountAmount = ($subTotal * ($this->discount / 100));
        } else {
            $discountAmount = min($this->discount, $subTotal);
        }

        $netTotal = max(0, $subTotal - $discountAmount);

        $taxAmount = 0;
        if ($this->tax_id && $this->tax) {
            $taxAmount = ($netTotal * ($this->tax->rate / 100));
        }

        $grandTotal = $netTotal + $taxAmount;
        $paid = $this->transactions()->where('type', 'in')->sum('amount');
        $due = max(0, $grandTotal - $paid);

        $status = 'due';
        if ($paid >= $grandTotal && $grandTotal > 0) {
            $status = 'paid';
        } elseif ($paid > 0) {
            $status = 'partially_paid';
        }

        $this->update([
            'sub_total' => $subTotal,
            'discount_amount' => $discountAmount,
            'net_total' => $netTotal,
            'tax_amount' => $taxAmount,
            'grand_total' => $grandTotal,
            'paid' => $paid,
            'due' => $due,
            'payment_status' => $status,
        ]);
    }
}
