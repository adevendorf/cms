<div class="form-group">
    <label>{{  $data->question }}</label>
    <input class="form-control" type="text" name="form[{{ $data->id }}]" {{ $data->required ? 'required' : '' }} />
</div>
