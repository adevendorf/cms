<?php
namespace Cms\Models;

use Cms\Models\Eloquent\Content as OrmModel;
use Cms\Contracts\Renderable;

class Content extends OrmModel implements Renderable
{
    protected $contentClass = [
        'Text' => \Cms\Models\Content\ContentText::class,
        'Code' => \Cms\Models\Content\ContentCode::class,
        'Gallery' => \Cms\Models\Content\ContentGallery::class,
        'Image' => \Cms\Models\Content\ContentImage::class,
        'Block' => \Cms\Models\Content\ContentImage::class,
        'Feed' => \Cms\Models\Content\ContentFeed::class,
        'Form' => \Cms\Models\Content\ContentForm::class,
        'FormField' => \Cms\Models\Content\ContentFormField::class,
        'Register' => \Cms\Models\Content\ContentFormField::class,
        'Login' => \Cms\Models\Content\ContentFormField::class,
        'ListCategories' => \Cms\Models\Content\ContentListCategories::class,
        'LatestPosts' => \Cms\Models\Content\ContentLatestPosts::class,
        'IconBox' => \Cms\Models\Content\ContentIconBox::class,
        'ColumnContainer' => \Cms\Models\Content\ContentColumnContainer::class,
    ];

    public function render($options = [])
    {
        $model = $this->contentClass[ucfirst($this->type)];
        $model = new $model();
        $model->fill($this->toArray());

        return $model->render();
    }

}
