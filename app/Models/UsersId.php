<?php

namespace App\Models;

use App\Models\UsersId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersId extends Model
{
    protected $table = "applicant_ids";
    protected $fillable = [
        'user_id',
        'file_name',
        'file_path'
    ];

}
