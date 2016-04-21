<div class="image-gallery" {!! $attributes !!}>
    <div id="carousel-feed" class="carousel slide" data-ride="carousel">
        @if (count($contents) > 1)
            <ol class="carousel-indicators">
                <?php $index = 0; ?>
                @foreach ($contents as $content)
                    <li data-target="#carousel-feed" data-slide-to="{{ $index + 1 }}" class="{{ $index == 0 ? "active" : "" }}">
                    </li>
                    <?php $index++; ?>
                @endforeach
            </ol>
        @endif

        <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox">
            <?php $index = 0; ?>
            @foreach ($contents as $content)
                <div class="item {{ $index == 0 ? "active" : "" }}">
                    {!! Cms::image($content->page->image, 'hero') !!}
                    <div class="carousel-caption">
                        <a href="{{ $content->path }}" {{ $content->new_window ? 'target="_blank"' : '' }}>{{ $content->title }}</a>
                        <p>{{ $content->synopsis }}</p>
                    </div>
                </div>
                <?php $index++; ?>
            @endforeach
        </div>

        @if (count($contents) > 1)
            <a class="left carousel-control" href="#carousel-feed" role="button" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#carousel-feed" role="button" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        @endif
    </div>
</div>