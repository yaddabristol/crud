@include('crud::partials.nav_tabs', ['tabs' => $fields])

<div class="tab-content">

  @foreach ($fields as $tab_name => $tab)

    @if (crud()->tabsCount() > 1)
      @include('crud::partials.nav_panel_open', [
        'parent_array' => $fields,
        'current_item' => $tab,
        'tab_name' => $tab_name
      ])
    @endif

      @foreach($tab as $name => $field)
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

    @if (crud()->tabsCount() > 1)
      </div>
    @endif

  @endforeach

</div>
