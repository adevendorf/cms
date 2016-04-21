@extends('cms.themes.default.body')
@section('content')
<div id="page">
    <div id="main">
        @yield('blog_content')
    </div>

    <div id="sidebar">
        @yield('blog_sidebar')
    </div>
</div>
@endsection