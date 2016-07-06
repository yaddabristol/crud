@extends(config('crud.base_view'))

@section('content')
    <div class="container">
        <h1>Create a new {{ crud('name_singular') }}</h1>

        {!! Form::model($item, [
            'method' => 'POST',
            'route' => crud('route') . '.store',
            'files' => $has_files
        ]) !!}
            @include(crud('views_dir') . '.partials.form', [
                'item' => $item,
                'submitText' => 'Create'
            ])
        {!! Form::close() !!}
    </div>
@endsection
