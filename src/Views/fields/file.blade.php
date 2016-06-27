<fieldset class="form-group">
  <label for="input_{{ $name }}">{{ $field['label'] or ucwords($name) }}</label>

  <input type="file" name="{{ $name }}" id="input_{{ $name }}" class="form-control-file"{{ (isset($field['required']) && !!$field['required'] ? ' required' : '') }}>

  @if (isset($field['help_text']))
    <small class="text-muted">{{ $field['help_text'] }}</small>
  @endif
</fieldset>
