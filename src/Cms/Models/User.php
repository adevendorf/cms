<?php
namespace Cms\Models;

use Cms\Models\Eloquent\User as OrmModel;

class User extends OrmModel
{
    public function isAdmin()
    {
        return $this->user_level == 'ROLE_ADMIN';
    }

    public function isSuperAdmin()
    {
        return $this->user_level == 'ROLE_SUPER_ADMIN';
    }
}


