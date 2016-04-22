<?php
namespace Cms\Repository;

use Illuminate\Http\Request;
use Cms\Repository\Base\Repository;
use Cms\Models\Block;
use Config;
use DB;


/**
 * Class BlockRepository
 * @package Cms\Repository
 */
class BlockRepository extends Repository
{
    /**
     * @var
     */
    protected $blockType;

    /**
     * @param $blockType
     */
    public function setType($blockType)
    {
        $this->blockType = $blockType;
    }

    /**
     * @return Block
     */
    public function newModel()
    {
        $block = new Block;
        $block->created_by = $this->getUserId();
        $block->type = $this->blockType;
        return $block;
    }

    /**
     * @param Request $request
     * @return Block
     */
    public function createFromPage(Request $request)
    {
        $block = $this->newModel();
        $block->fill($request->all());
        $block->type = 'page';
        return $block;
    }

    /**
     * @param $request
     * @return Collection
     */
    public function paginate($request)
    {
        $blocks = Block::orderBy('id', 'asc');

        $blocks = $blocks->where('type', $this->blockType);
        

        $this->count = $request->input('count') ? $request->input('count') : $this->count;

        return $blocks->paginate($this->count);
    }

    /**
     * @param $id
     * @return Block
     * @throws \Exception
     */
    public function findBy($column, $value, $full = false)
    {
        $item = Block::with(
                'image',
                'image.asset',
                'image.crops'
        );

        if ($full) {
            $item = $item->with(
                'content',
                'content.resource',
                'content.image',
                'content.image.asset',
                'content.image.crops'
            );
        }
        $item = $item->where($column, $value)->firstOrFail();

        return $item;
    }

    /**
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public function destroy($id)
    {
        $block = Block::findOrFail($id);
        $block->delete();
        return true;
    }
}