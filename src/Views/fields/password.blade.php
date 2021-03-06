<fieldset class="form-group {{ $field['fieldset_class'] or '' }} {{ $errors->get($name) ? 'has-error has-danger' : '' }}">
  <label for="input_{{ $name }}">{{ $field['label'] or ucwords($name) }}</label>

  {!! Form::password($name, [
    'id'          => "input_$name",
    'class'       => 'form-control ' . (isset($field['class']) ? $field['class'] : ''),
    'required'    => (isset($field['required']) && !!$field['required'] ? 'required' : null),
    'placeholder' => isset($field['placeholder']) ? $field['placeholder'] : null
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
