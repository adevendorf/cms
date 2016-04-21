<div class="content content-listcategories">
    <h4>Categories</h4>
    <ul>
        @foreach ($list as $cat)
            <li>
                <a href="{{ config('cms.blog_path') }}{{ $cat['slug'] }}">{{ $cat['name'] }}</a>
            </li>
        @endforeach
    </ul>
</div>