<div class="form-group">
    <label>{{  $data->question }}</label>
    <textarea class="form-control" name="form[{{ $data->id }}]" {{ $data->required ? 'required' : '' }}></textarea>
</div>
