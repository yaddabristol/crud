{{--
  This file assumes you are using Laravel Stapler to attach your images to your
  model. If you aren't, you'll have to override this template with your own.
--}}
<fieldset class="form-group">
  <label for="input_{{ $name }}">{{ $field['label'] or ucwords($name) }}</label>

  @if ($value->originalFilename() !== null)
    {{-- Using both Bootstrap 3 and 4 classes here just to make sure. --}}
    <img src="{{ $value->url() }}" alt="{{ $name }} preview" class="pull-xs-right pull-right" style="width: 80px; height: 80px; margin-bottom: 20px;">
  @endif

  <input type="file" name="{{ $name }}" id="input_{{ $name }}" class="form-control-file"{{ (isset($field['required']) && !!$field['required'] ? ' required' : '') }}>

  @if (isset($field['help_text']))
    <small class="text-muted">{{ $field['help_text'] }}</small>
  @endif
</fieldset>
