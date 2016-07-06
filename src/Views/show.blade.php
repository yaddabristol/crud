@extends(config('crud.base_view'))

@section('content')
    <div class="container">
        Showing {{ crud('name_singular') }} #{{ $item->id }}
    </div>
@endsection
