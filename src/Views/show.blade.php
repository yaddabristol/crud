@extends(config('crud.base_view'))

@section('content')
    <div class="container">
        @include('crud::partials.messages')

        Showing {{ crud('name_singular') }} #{{ $item->id }}
    </div>
@endsection
