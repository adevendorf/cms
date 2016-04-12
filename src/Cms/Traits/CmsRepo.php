<?php
namespace Cms\Traits;

trait CmsRepo
{
    public function getRepository($name)
    {
        try {
            $repository = $this->createRepositoryName($name);
            $repository = new $repository;
        } catch(Exception $e) {
            throw $e;
        }

        return $repository;
    }

    public function createRepositoryName($name)
    {
        return '\\Cms\\Repository\\' .  ucfirst($name) . 'Repository';
    }
}