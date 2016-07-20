<fieldset class="form-group {{ $field['fieldset_class'] or '' }} {{ $errors->get($name) ? 'has-error has-danger' : '' }}">
  <label>
    {!! Form::checkbox($name, isset($field['value']) ? $field['value'] : '1', null, [
      'class' => (isset($field['class']) ? $field['class'] : null)
    ]) !!}

    {{ $field['label'] }}

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
  </label>
</fieldset>
