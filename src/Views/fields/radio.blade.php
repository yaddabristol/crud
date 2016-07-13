<fieldset class="form-group {{ $field['fieldset_class'] or '' }} {{ $errors->get($name) ? 'has-error has-danger' : '' }}">
  <div>
    {{ $field['label'] }}
    @if (isset($field['help_text']))
      <br><small class="text-muted">{{ $field['help_text'] }}</small>
    @endif
  </div>


  @foreach ($field['choices'] as $choice_value => $choice_name)
    <div class="radio">
      <label>
        {!! Form::radio(
          $name,
          $choice_value,
          old($name, (isset($field['default']) ? $field['default'] : null)) === $choice_value
        ) !!}
        {{ $choice_name }}
      </label>
    </div>
  @endforeach

  @if ($errors->get($name))
    <div class="help-block text-help">
      {{ $errors->first($name) }}
    </div>
  @endif
</fieldset>
