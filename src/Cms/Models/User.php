<?php
namespace Cms\Models;

use Cms\Models\Eloquent\User as OrmModel;

class User extends OrmModel
{
    public function isAdmin()
    {
        return $this->user_level == 'cms_admin' || $this->user_level == 'cms_super';
    }

    public function isSuperAdmin()
    {
        return $this->user_level == 'cms_super';
    }
}


