<div class="content content-latestpages">
    <h4>Latest Blog Posts</h4>
    <ul>
        @foreach ($latest as $page)
            <li>
                <a href="{{ config('cms.blog_path') }}{{ $page->id}}/{{ $page->route->url }}">{{ $page->title }}</a> By: {{ Cms::author($page->author) }}
            </li>
        @endforeach
    </ul>
</div>