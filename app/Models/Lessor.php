<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lessor extends Model
{
    use HasFactory;
    protected $table = "lessors";
    protected $fillable = [
        'application_id',
        'lessors_fullname',
        'lessors_address',
        'lessors_tel_no',
        'lessors_email',
        'monthly_rental'
    ];
}
