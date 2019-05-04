<?php

namespace App\models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use SMartins\PassportMultiauth\HasMultiAuthApiTokens;

class UserHandleModel extends Authenticatable
{
    use Notifiable, HasMultiAuthApiTokens;

    const TYPE_TOP_AGENT     = 1;
    const TYPE_AGENT         = 2;
    const TYPE_USER          = 3;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'top_id', 'parent_id', 'rid', 'sign', 'account_id', 'type', 'vip_level', 'is_tester', 'frozen_type', 'nickname', 'password', 'fund_password', 'prize_group', 'remember_token', 'level_deep', 'register_ip', 'last_login_ip', 'register_time', 'last_login_time', 'extend_info', 'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'register_time' => 'datetime',
        'last_login_time' => 'datetime',
    ];


    public function platform()
    {
        return $this->hasOne(PlatForms::class,'platform_id', 'platform_id');
    }

    public function account()
    {
        return $this->hasOne(HandleUserAccounts::class,'id','account_id');
    }
    //用户冻结历史
    public function userAdmitedFlow()
    {
        return $this->hasMany(UserAdmitedFlowsModel::class,'user_id','id')->orderBy('created_at', 'desc');
    }

    public function getAccountAttribute()
    {
        return $this->account->first(); // not addresses()->first(); as it would run the query everytime
    }
}
