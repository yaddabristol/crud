
{{--
  You will probably want to copy this file to your-views-folder/partials/form.blade.php
--}}

{{--
  This will insert form fields generated from the
  form_fields attributes on your controller.
--}}
@include('crud::partials.autoform', [
  'fields' => crud('form_fields'),
  'model'  => $item
])

<button class="btn btn-primary pull-right">{{ $submitText }}</button>
