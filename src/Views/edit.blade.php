@extends(config('crud.base_view'))

@section('content')
    <div class="container">
        <h1>Edit a {{ $name_singular }}</h1>

        {!! Form::model($item, [
            'method' => 'PATCH',
            'route' => [$route . '.update', $item->id],
        ]) !!}
            @include($views_dir . '.partials.form', [
                'item' => $item,
                'submitText' => 'Update'
            ])
        {!! Form::close() !!}
    </div>
@endsection
