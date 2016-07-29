@extends(config('crud.base_view'))

@section('content')
    <div class="container">
        @include('crud::partials.messages')

        Showing {{ crud('name_singular') }} #{{ $item->id }}
    </div>
@endsection

@section('footer_variables')
  @include('crud::partials.footer_script_enqueue')
@stop