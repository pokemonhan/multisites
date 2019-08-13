<?php

namespace App\Models\User;

use App\Models\BaseModel;

class FrontendUsersRegisterableLink extends BaseModel
{
    protected $guarded = ['id'];
    
    public function RegisteredUsersCount(){
        return $this->hasMany(FrontendLinksRegisteredUsers::class, 'register_link_id', 'id')->count();
    }
    
}
