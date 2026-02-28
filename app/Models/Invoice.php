<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    /** @use HasFactory<\Database\Factories\InvoiceFactory> */
    use HasFactory;
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
