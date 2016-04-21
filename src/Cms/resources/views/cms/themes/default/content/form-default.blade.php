@if ($last)
    <form class="form cms-form" id="form-{{ $form->id }}" method="POST" action="/submit/{{ $form->submission_uuid }}">
@else
    <form class="form cms-form" id="form-{{ $form->id }}" method="POST" action="/form/{{ $form->submission_uuid }}/{{ $step }}">
@endif

    @if ($form->ajax)
        <input type="hidden" name="_ajax" value="true" />
    @endif

    @foreach ($fields as $field)

        {!! $field !!}

    @endforeach

    <div class="form-submit">
        @if ($last)
            <input type="submit" class="btn btn-primary form_submit" id="submit_{{ $form->id }}" value="{{ $form->submit_text }}" />
        @else
            <input type="submit" class="btn btn-primary form_submit" id="submit_{{ $form->id }}" value="Continue" />
        @endif
    </div>
</form>