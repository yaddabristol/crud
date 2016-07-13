<fieldset class="form-group {{ $field['fieldset_class'] or '' }} {{ $errors->get($name) ? 'has-error has-danger' : '' }}">
  <label for="input_{{ $name }}">{{ $field['label'] or ucwords($name) }}</label>

  {!! Form::text($name, isset($field['default']) ? $field['default'] : null, [
    'id'          => "input_$name",
    'class'       => 'form-control',
    'required'    => (isset($field['required']) && !!$field['required'] ? 'required' : null),
    'placeholder' => isset($field['placeholder']) ? $field['placeholder'] : null,
    'data-source' => $field['source'],
  ]) !!}

  @if (isset($field['help_text']))
    <div>
      <small class="text-muted">{{ $field['help_text'] }}</small>
    </div>
  @endif

  @if ($errors->get($name))
    <div class="help-block text-help">
      {{ $errors->first($name) }}
    </div>
  @endif
</fieldset>
