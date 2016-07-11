<fieldset class="form-group {{ $field['fieldset_class'] or '' }}">
  <label for="input_{{ $name }}">{{ $field['label'] or ucwords($name) }}</label>

  @if (isset($field['help_text']))
    <small class="text-muted">{{ $field['help_text'] }}</small>
  @endif

  <textarea class="form-control" name="{{ $name }}" placeholder="{{ $field['placeholder'] or '' }}" id="input_{{ $name }}" rows="{{ $field['rows'] or 5 }}"{{ (isset($field['required']) && !!$field['required'] ? ' required' : '') }}>{{ $value or '' }}</textarea>
</fieldset>
