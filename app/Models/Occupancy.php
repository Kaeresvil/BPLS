<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Occupancy extends Model
{
    protected $table = "occupancy";
    protected $fillable = [
        'total',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
    

}
