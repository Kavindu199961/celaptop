<?php
// app/Models/EstimateCounter.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstimateCounter extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'last_number',
    ];
}