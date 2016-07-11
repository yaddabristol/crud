<fieldset class="form-group {{ $field['fieldset_class'] or '' }}">
  <p>
    {{ $field['label'] }}
    @if (isset($field['help_text']))
      <br><small class="text-muted">{{ $field['help_text'] }}</small>
    @endif
  </p>


  @foreach ($field['choices'] as $choice_value => $choice_name)
    <div class="radio">
      <label>
        <input type="radio" name="{{ $name }}" value="{{ $choice_value }}">
        {{ $choice_name }}
      </label>
    </div>
  @endforeach
</fieldset>
