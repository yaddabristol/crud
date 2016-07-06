@extends(config('crud.base_view'))

@section('content')
    <div class="container">
        <h1>Edit a {{ crud('name_singular') }}</h1>

        {!! Form::model($item, [
            'method' => 'PATCH',
            'route' => [crud('route') . '.update', $item->id],
            'files' => $has_files
        ]) !!}
            @include(crud('views_dir') . '.partials.form', [
                'item' => $item,
                'submitText' => 'Update'
            ])
        {!! Form::close() !!}
    </div>
@endsection
