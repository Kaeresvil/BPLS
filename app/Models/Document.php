<?php

namespace App\Models;

use App\Models\UsersId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = "documents";
    protected $fillable = [
        'application_id',
        'file_name',
        'name',
        'file_path'
    ];

}
