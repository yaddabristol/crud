{{--
  This file assumes you are using Laravel Stapler to attach your images to your
  model. If you aren't, you'll have to override this template with your own.
--}}
<fieldset class="form-group {{ $field['fieldset_class'] or '' }} {{ $errors->get($name) ? 'has-error has-danger' : 'has-success' }}">
  <label for="input_{{ $name }}">{{ $field['label'] or ucwords($name) }}</label>

  @if ($value->originalFilename() !== null)
    {{-- Using both Bootstrap 3 and 4 classes here just to make sure. --}}
    <img src="{{ $value->url() }}" alt="{{ $name }} preview" class="pull-xs-right pull-right" style="width: 80px; height: 80px; margin-bottom: 20px;">
  @endif

  {!! Form::file($name, [
    'id' => "input_$name",
    'class' => 'form-control-file',
    'required' => isset($field['required']) && !!$field['required'] ? 'required' : null
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
