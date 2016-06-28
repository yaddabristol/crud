@extends(config('crud.base_view'))

@section('content')
    <div class="container">
        <h1>Create a new {{ $name_singular }}</h1>

        {!! Form::model($item, [
            'method' => 'POST',
            'route' => $route . '.store',
            'files' => $has_files
        ]) !!}
            @include($views_dir . '.partials.form', [
                'item' => $item,
                'submitText' => 'Create'
            ])
        {!! Form::close() !!}
    </div>
@endsection
