<?php

namespace Cms\Helpers;

class MigrationCommons
{

    public function addCommonFields($table) {
        $table->unsignedInteger('created_by')->nullable();
        $table->unsignedInteger('modified_by')->nullable();
        $table->timestamps();
        $table->softDeletes();
    }
}