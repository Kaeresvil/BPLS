<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'extension_name',
        'email',
        'password',
        'role_id',
        'is_verified',
        'file_name',
        'file_path'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $appends = ['full_name', 'role'];
    protected $with = ['verification_ids'];

    public function role()
    {
        return $this->hasOne(Role::class,'id','role_id');
      
    }
    public function verification_ids()
    {
        return $this->hasMany(UsersId::class,'user_id','id');
      
    }

    public function unseenMessages()
    {
        return $this->hasMany(Message::class, 'sender_id')
                ->where('seen', 0)
                ->where('receiver_id', auth()->user()->id);
    }

    public function getFullNameAttribute(){
        return $this->first_name .' '. $this->middle_name.' '. $this->last_name.' '. $this->extension_name;
    }
    public function getRoleAttribute(){
        $role = Role::find($this->role_id);
        return $role->name;
    }
}
