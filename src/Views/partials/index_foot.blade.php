@if ($paginate)
  <div class="text-center">
    {!! $items->appends($settings)->render() !!}
  </div>
@endif
