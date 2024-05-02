<?php

namespace App\Models;

use App\Models\Application;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected $table = "assessment";
    protected $fillable = [
        'application_id',
        'key',
        'tax',
        'amount',
        'penalty',
        'total'
    ];


}
