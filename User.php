<?php

namespace App\Service;

use App\Models\User;
use App\Models\Category;
use App\Models\Permission;

class UserService {
    public function __construct(User $user,
        Category $category, Permission $permission) {
        $this->user = $user;
        $this->category = $category;
        $this->permission = $permission;
    }

    public function create_user($param)
    {
        $user = [
            'name' => $param['name'],
            'email' => $param['email']
        ];
        if(isset($param['password'] && $param['password'])){
        	$user['password'] = $param['password']
        }
        return $this->user->create($user);
    }

    public function updateUser($userId, $param)
    {
        $user = $this->user->find($userId);
        $updatedUser = [
            'name' => $param['name'] ?? $user->name, 
            'email' => $param['email'] ?? $user->email, 
        ];
        
        if (isset($param['password']) && $param['password']) {
            $updatedUser['password'] = $param['password'];
        }
        
        $user->update($updatedUser);
        
        return $user;
    }


    public function search($param){
    	$UserSearch = $this->user;
    	if ($param['keyword']) {
    		$UserSearch->where('name', "like","%$param['keyword']");
    	}
    	return $UserSearch->with("role",'group')->where('status', true)->whereHas('schedule')->paginate();
    }
}