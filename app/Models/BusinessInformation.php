<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessInformation extends Model
{
    use HasFactory;
    protected $table = "business_informations";
    protected $fillable = [
        'application_id',
        'business_address',
        'business_postal',
        'business_tel_no',
        'business_email',
        'business_mobile_no',
        'business_area',
        'total_employee',
        'no_employee'
    ];
}
