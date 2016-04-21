
<ul class="feed-list" {!! $attributes !!}>

    @foreach ($contents as $content)
        <li class="item">
            {!! Cms::image($content->image, 'thumb') !!}
            <a href="#" {{ $content->new_window ? 'target="_blank"' : '' }}>{{ $content->title }}</a>
            <p>{{ $content->synopsis }}</p>
        </li>
    @endforeach

</ul>