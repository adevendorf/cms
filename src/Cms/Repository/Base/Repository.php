<?php
namespace Cms\Repository\Base;

use Auth;
use Config;
use Cache;

class Repository
{
    protected $count;
    protected $cachingEnabled = false;
    protected $cacheKey;
    protected $minutes;

    public function __construct($count = 10)
    {
        $this->count = $count;
    }

    public function results($data)
    {
        
    }

    public function getUserId()
    {
        return Auth::user()->id;
    }

    public function limitToFields($collection, $fields = null)
    {
        if (!$fields) return $collection;

        foreach($collection as $item) {
            $item->setFields(explode(',',$fields));
        }

        return $collection;
    }

    public function all()
    {
        return $this->all();
    }
    

    public function findById($id, $full = false)
    {
        return $this->findBy('id', $id, $full);
    }

    public function remember($minutes = 30)
    {
        $this->minutes = $minutes;
//        $this->enableCaching($minutes);
        return $this;
    }

    public function enableCaching($minutes)
    {
        $this->cachingEnabled = true;
    }

    public function shouldCache()
    {
        return $this->cachingEnabled;
    }

    public function createCacheKey($key = null)
    {
        if (!$key) {
            throw \Exception('No key defined');
        }

        $this->cacheKey = $key;

        return $this->cacheKey;
    }

    public function getCache($key)
    {
        return Cache::get($key, function() {
            return false;
        });
    }

    public function putCache($key, $value, array $tags, $minutes = null)
    {
        Cache::tags($tags)->put($key, $value, $this->minutes ? $minutes : $this->minutes);
    }

    public function clearCacheByTags($tags)
    {
        Cache::tags($tags)->flush();
    }
}