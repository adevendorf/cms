<html>
<head>
    <title>App</title>
    {{--<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,400italic,700' rel='stylesheet' type='text/css'>--}}
    <link href="/css/cms/app.css" rel="stylesheet">
    <script src="/js/vendor/jquery/jquery.min.js"></script>
    <link href="/js/vendor/tether/dist/css/tether.css" type="text/css" rel="stylesheet" />
    <script src="/js/vendor/tether/dist/js/tether.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
    <link href="/js/vendor/fontawesome/css/font-awesome.min.css" rel="stylesheet">
    <script src="/js/vendor/underscore/underscore-min.js"></script>


    <link href="/js/vendor/cropper/dist/cropper.min.css" rel="stylesheet"/>
    <script src="/js/vendor/cropper/dist/cropper.min.js"></script>

    <script src="/js/vendor/ckeditor/ckeditor.js"></script>
    <link href="/js/vendor/summernote/dist/summernote.css" type="text/css" rel="stylesheet" />
    <script src="/js/vendor/summernote/dist/summernote.js"></script>

    <link href="/js/vendor/fullcalendar/fullcalendar.css" rel="stylesheet"/>
    <script src='/js/vendor/fullcalendar/lib/moment.min.js'></script>
    <script src="/js/vendor/fullcalendar/fullcalendar.js"></script>
    <script src='/js/vendor/dragula/dragula.min.js'></script>


    <meta id="_token" data-value="{{ $token }}" />
</head>
<body id="app">
<div id="cms"></div>
<div id="system"></div>
<div id="imagemanager"></div>
<script src="/js/cms/app.js"></script>
</body>
</html>