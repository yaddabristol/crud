@foreach ($form_fields as $name => $field)
  <?php $data = [
    'name'  => $name,
    'field' => $field,
    'value' => $model->$name
  ]; ?>

  @if(view()->exists('fields.' . $field['type']))
    {{-- Allow using custom field types --}}
    @include('fields.' . $field['type'], $data)
  @elseif (view()->exists('crud::fields.' . $field['type']))
    {{-- Try default field types --}}
    @include('crud::fields.' . $field['type'], $data)
  @else
    {{-- Default to text field if we don't have a layout for this field type --}}
    @include('crud::fields.text', $data)
  @endif
@endforeach
