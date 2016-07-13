<fieldset class="form-group {{ $field['fieldset_class'] or '' }} {{ $errors->get($name) ? 'has-error has-danger' : 'has-success' }}">
  <label>
    {!! Form::checkbox($name, isset($field['value']) ? $field['value'] : '1') !!}

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
