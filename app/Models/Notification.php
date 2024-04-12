<?php

namespace App\Models;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = "notifications";
    protected $fillable = [
        'user_id',
        'application_id',
        'title',
        'description',
        'is_read',
        'is_ForStaff'
    ];

}
