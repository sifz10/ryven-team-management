<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'invoice_date',
        'due_date',
        'client_name',
        'client_email',
        'client_phone',
        'client_address',
        'items',
        'subtotal',
        'tax_percentage',
        'tax_amount',
        'discount_percentage',
        'discount_amount',
        'total',
        'currency',
        'notes',
        'terms',
        'status',
    ];

    protected $casts = [
        'items' => 'array',
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public static function generateInvoiceNumber()
    {
        $lastInvoice = self::orderBy('id', 'desc')->first();
        $number = $lastInvoice ? (int)substr($lastInvoice->invoice_number, 4) + 1 : 1;
        return 'INV-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }
}
