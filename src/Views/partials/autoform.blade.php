@foreach ($form_fields as $name => $field)
  @if (view()->exists('crud::fields.'.$field['type']))
    @include('crud::fields.'.$field['type'], [
      'name'  => $name,
      'field' => $field,
      'value' => $model->$name
    ])
  @else
    {{-- Default to text field if we don't have a layout for this field type --}}
    @include('crud::fields.text', [
      'name'  => $name,
      'field' => $field,
      'value' => $model->$name
    ])
  @endif
@endforeach
