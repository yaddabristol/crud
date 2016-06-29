<fieldset class="form-group">
  <label for="input_{{ $name }}">{{ $field['label'] or ucwords($name) }}</label>

  @if (isset($field['help_text']))
    <small class="text-muted">{{ $field['help_text'] }}</small>
  @endif

  <textarea class="form-control wysiwyg medium-editor" name="{{ $name }}" placeholder="{{ $field['placeholder'] or '' }}" id="input_{{ $name }}"{{ (isset($field['required']) && !!$field['required'] ? ' required' : '') }}>{{ $value or '' }}</textarea>
</fieldset>
