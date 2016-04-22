<?php
namespace Cms\Models;

use Cms\Models\Eloquent\Route as OrmModel;

class Route extends OrmModel
{
    public function slugify() {
        $path = explode('/', $this->url);

        $finalPath = array_map(function($segment) {
            return str_slug($segment);
        }, $path);

        $finalPath = str_replace('//', '/', '/' . implode("/", $finalPath));

        if (substr($finalPath, 0, 1) === '/') {
            $finalPath = substr($finalPath, 1);
        }

        $this->url = $finalPath;

        return $this;
    }

    public function setPrimaryDir()
    {
        $path = explode('/', $this->url);

        if (count($path) == 0) $path = [null];

        $this->primary_dir = head($path);
        return $this;
    }

    public function save(array $options = [])
    {
        $this->slugify();
        $this->setPrimaryDir();
        parent::save();
    }

}
