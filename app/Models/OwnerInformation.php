<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OwnerInformation extends Model
{
    use HasFactory;
    protected $table = "owner_informations";
    protected $fillable = [
        'application_id',
        'owner_address',
        'owner_postal',
        'owner_tel_no',
        'owner_email',
        'owner_mobile_no',
        'contact_person_mobile_no',
        'contact_person_email',
    ];
}
