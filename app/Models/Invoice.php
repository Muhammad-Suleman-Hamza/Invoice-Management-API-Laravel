<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\InvoiceStatus;

class Invoice extends Model
{
    /** @use HasFactory<\Database\Factories\InvoiceFactory> */
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'contract_id',
        'invoice_number',
        'subtotal',
        'tax_amount',
        'total',
        'status',
        'due_date',
        'paid_at',
    ];

    protected $casts = [
        'status' => InvoiceStatus::class,
    ];

    public function contract(){
        return $this->belongsTo(Contract::class);
    }

    public function payments(){
        return $this->hasMany(Payment::class);
    }
}
