@extends('cms.themes.default.blog.blog_body')

@section('blog_content')

  <ol class="breadcrumb">
    <li><a href="{{ config('cms.blog_path') }}">Blog</a></li>
    <li><a href="{{ config('cms.blog_path') }}{{ $page->category->slug }}">{{ $page->category->name }}</a></li>
    <li class="active">{{ $page->title }}</li>
  </ol>

  <h1>{{ $page->title }}</h1>

  <hr />
  <p class="meta">By: {{ Cms::author($page->author) }} | {{ Cms::formatDate($page->created_at) }}</p>
  <hr />

  {!! Cms::render($content, 'main') !!}

  @if ($page->allow_comments)
    <hr />

    <h5>Comments</h5>

    @include('cms.themes.default.partials.disqus')
  @endif

@endsection