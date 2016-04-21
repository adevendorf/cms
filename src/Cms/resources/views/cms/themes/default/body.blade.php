<!doctype html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
@if (isset($page))
<title>{{ $page->title }}</title>
@else 
<title>@yield('title')</title>
@endif
@if (isset($meta))
{!! $meta !!}
@endif
<link rel="stylesheet" href="/css/bootstrap.min.css" />
<style>
body {
  padding: 10px;
}
.clear {
  clear: left;
}
#main {
  float: left;
  width: 70%;
  padding: 10px;
}
#sidebar {
  float: left;
  width: 30%;
  padding: 10px;
}
.form {
  border: solid 1px #ccc;
  padding: 10px;
}
.form-field {
  margin-bottom: 10px;
}
.form-submit {
  border-top: solid 1px #ccc;
  padding-top: 10px;
}
.content-image {
  padding: 10px;
}
.content-image img {
  max-width: 100%;
  margin: 10px;
}
.content-gallery {
  margin: 20px 0;
}
.grid-item {
  border: solid 1px #ccc;
  padding: 5px;
  margin: 5px;
}
.grid-item img {
  max-width: 100%;
}
.image-gallery {
  margin-bottom: 10px;
}
</style>
</head>
<body>

<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-nav">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="/">Site</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="main-nav">
      <ul class="nav navbar-nav">
        <li><a href="/">Home</a></li>
        <li><a href="/blog">Blog</a></li>
      </ul>

    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

@yield('content')

<script src="/js/vendor/jquery/jquery.min.js"></script>
<script src="/js/vendor/bootstrap/bootstrap.min.js"></script>
@include('cms.themes.default.partials.scripts')
@include('cms.themes.default.partials.tracking')
</body>
</html>