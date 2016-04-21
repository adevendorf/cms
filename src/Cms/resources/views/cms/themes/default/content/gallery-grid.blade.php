<div class="image-gallery image-gallery-grid" {!! $attributes !!}>
    <h2>{{ $title  }}</h2>
    <div class="row">
        @foreach ($contents as $content)
            <div class="col-sm-2 grid-item">
                {!! $content !!}
            </div>
        @endforeach
    </div>
</div>