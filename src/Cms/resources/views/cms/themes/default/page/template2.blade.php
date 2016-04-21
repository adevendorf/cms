@inject('render', 'Cms\Services\RenderService')
<?php $render->setPageContent($page, $content); ?>

@extends('cms.themes.default.body')

@section('content')
<div id="page">
  {!! Cms::render($content, 'main') !!}

  {!! Cms::render($content, 'sidebar') !!}
  <div class="clear"></div>
</div>
<hr />
Template: template2
@endsection