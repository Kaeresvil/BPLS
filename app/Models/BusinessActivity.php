<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessActivity extends Model
{
    use HasFactory;
    protected $table = "business_activity";
    protected $fillable = [
        'application_id',
        'line_of_business',
        'units',
        'capitalization',
        'essential',
        'non_essential'
    ];
}
