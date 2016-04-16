<?php
namespace Cms\Managers\System;

/**
 * Class RepositoryManager
 * @package Cms\Managers
 */
class RepositoryManager
{
    /**
     * @param $name
     * @return mixed
     * @throws Exception
     */
    public static function get($name)
    {
        try {
            $repositoryName = self::getNamespace($name);
            return new $repositoryName;
        } catch(Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $name
     * @return string
     */
    private static function getNamespace($name)
    {
        return '\\Cms\\Repository\\' .  ucfirst($name) . 'Repository';
    }
}