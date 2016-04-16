<?php
namespace Cms\Helpers;

/**
 * Class MigrationCommons
 * @package Cms\Helpers
 */
class MigrationCommons
{
    /**
     * @param $table
     */
    public function addCommonFields($table) {
        $table->unsignedInteger('created_by')->nullable();
        $table->unsignedInteger('modified_by')->nullable();
        $table->timestamps();
        $table->softDeletes();
    }
}