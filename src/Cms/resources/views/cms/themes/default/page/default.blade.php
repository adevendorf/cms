@extends('cms.themes.default.body')

@section('content')
<style>
  body {
    background: #fafafa;
    color: #444;
    font-family: 'georgia';
    font-size: 16px;
  }
  p {
    line-height: 1.5em;
  }
</style>
<div id="page">

    {!! Cms::render($content, 'main') !!}

    {!! Cms::render($content, 'sidebar') !!}

  <div class="clear"></div>
</div>
<hr />
Template: generic
@endsection