<?php
namespace Cms\Generators;

use Cms\Models\Form;
use Cms\Models\Block;
use Faker\Factory;
use Config;
use Auth;

class FormGenerator
{
    public function createNewForm($request)
    {
        $faker = Factory::create();

        $item = new Form;
        $item->fill($request->all());
        $item->title = "New Form";
        $item->submission_uuid = $faker->uuid;
        $item->save_to = 'database';
        $item->created_by = Auth::user()->id;
        $item->save();

        $block = new Block;
        $block->title = '1';
        $block->type = 'form';
        $block->created_by = Auth::user()->id;

        $item->blocks()->save($block);

        return $item;
    }
}