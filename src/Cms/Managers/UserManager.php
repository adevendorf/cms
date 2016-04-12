<?php
namespace Cms\Managers;

use Auth;

class UserManager
{
    protected $user;

    public function __construct() {
        $this->user = Auth::user();
    }

    public function getUserLevel()
    {
        return $this->user->user_level;
    }
}