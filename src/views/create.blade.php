@extends(config('crud.base_view'))

@section('content')
    <h1>Create</h1>

    {!! Form::model([
        'method' => 'POST',
        'route' => $route . '.store',
    ]) !!}
        @include($views_dir . '.form', ['submitText' => 'Create'])
    {!! Form::close() !!}
@endsection
