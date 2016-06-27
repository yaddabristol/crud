<fieldset class="form-group">
  <label>
    <input type="checkbox" name="{{ $name }}" value="{{ $field['value'] or '1' }}" {{ ($value ? 'checked' : '') }}>

    {{ $field['label'] }}

    @if (isset($field['help_text']))
      <small class="text-muted">{{ $field['help_text'] }}</small>
    @endif
  </label>
</fieldset>
