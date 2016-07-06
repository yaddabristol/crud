@if (crud('paginate'))
  <div class="text-center">
    {!! $items->render() !!}
  </div>
@endif
