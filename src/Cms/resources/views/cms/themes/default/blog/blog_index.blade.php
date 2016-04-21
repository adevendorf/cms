@extends('cms.themes.default.blog.blog_body')

@section('blog_content')

  <h1>Blog Index</h1>

  @if (!$posts)
    <div class="post">No Posts</div>
  @endif

  @foreach ($posts as $post)
    <div class="post">

      <h2><a href="{{config('cms.blog_path')}}{{$post->id}}/{{ $post->route->url }}">{{ $post->title }}</a></h2>
      <p>By: {{ Cms::author($post->author) }} | {{  Cms::formatDate($post->created_at) }}</p>
      <p><a href="{{config('cms.blog_path')}}{{ $post->category->slug}}">{{ $post->category->name }}</a></p>

      {!! $post['html'] !!}

    </div>
    <hr />
  @endforeach

  {!! $posts->render() !!}

@endsection
