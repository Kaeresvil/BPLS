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
        'actioned_by_id',
        'date_claimed',
        'is_claimed',
    ];
    protected $appends = ['actioned_by','payor'];
    // protected $with = ['applicant','application'];

    public function applicant()
    {
        return $this->hasOne(User::class,'id','user_id');
      
    }
    public function application()
    {
        return $this->hasMany(Application::class,'id','application_id');
      
    }

    public function getActionedByAttribute(){
        if($this->actioned_by_id !=  null)
        {
        $user = User::find($this->actioned_by_id);
        return $user->full_name ;
        }else{return '';}
    }
    public function getPayorAttribute(){
        if($this->actioned_by_id !=  null)
        {
        $application = Application::find($this->application_id);
        $user = User::find($application->applicant_id);
        return $user->full_name ;
        }else{return '';}
    }

}
