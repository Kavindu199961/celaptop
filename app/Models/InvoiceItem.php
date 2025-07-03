<?php
// app/Models/InvoiceItem.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'description',
        'warranty',
        'quantity',
        'unit_price',
        'amount',
        'user_id', // Foreign key to the users table

    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function user()
{
    return $this->belongsTo(User::class);
}
}