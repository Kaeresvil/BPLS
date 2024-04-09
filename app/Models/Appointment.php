<?php

namespace App\Models;

use App\Models\UsersId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $table = "appointment";
    protected $fillable = [
        'user_id',
        'application_id',
        'date',
        'date_claimed',
        'is_claimed',
    ];

    // protected $with = ['applicant','application'];

    public function applicant()
    {
        return $this->hasOne(User::class,'id','user_id');
      
    }
    public function application()
    {
        return $this->hasMany(Application::class,'id','application_id');
      
    }

}
