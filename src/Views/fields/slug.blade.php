<fieldset class="form-group">
  <label for="input_{{ $name }}">{{ $field['label'] or ucwords($name) }}</label>

  <input type="text" name="{{ $name }}" class="form-control" value="{{ $value }}" placeholder="{{ $field['placeholder'] or '' }}" id="input_{{ $name }}" data-source="{{ $field['source'] }}"{{ (isset($field['required']) && !!$field['required'] ? ' required' : '') }}>

  @if (isset($field['help_text']))
    <small class="text-muted">{{ $field['help_text'] }}</small>
  @endif
</fieldset>
