<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;


    protected $table = "applications";
    protected $fillable = [
        'applicant_id',
        'ref_no',
        'status',
        'type_of_application',
        'mode_of_payment',
        'date_of_application',
        'tin',
        'type_of_business',
        'ammendment_from',
        'ammendment_to',
        'dti_registration_no',
        'dti_registration_date',
        'first_name',
        'last_name',
        'middle_name',
        'business_name',
        'trade_name',
        'actioned_by_id',
        'remarks',
        'claimed_by',
        'claimed_date',
    ];

    protected $with = ['documents','business_activity','business_information','owner_information','lessor'];
    protected $appends = ['appointment'];

    public function getAppointmentAttribute()
  {
    $appointment = Appointment::where('application_id', $this->id)->first();
    return $appointment;
  }

    public function applicant()
    {
        return $this->hasOne(User::class,'id','applicant_id');
      
    }
    public function actioned_by()
    {
        return $this->hasOne(User::class,'id','actioned_by_id');
      
    }

    public function documents()
    {
        return $this->hasMany(Document::class,'application_id','id');
      
    }
    public function business_activity()
    {
        return $this->hasMany(BusinessActivity::class,'application_id','id');
      
    }

    public function business_information()
    {
        return $this->hasOne(BusinessInformation::class,'application_id','id');
      
    }
    public function owner_information()
    {
        return $this->hasOne(OwnerInformation::class,'application_id','id');
      
    }
    public function lessor()
    {
        return $this->hasOne(Lessor::class,'application_id','id');
      
    }
}
