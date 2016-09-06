@extends(config('crud.base_view'))

@section('content')
    <div class="container">
        <h1>
            Create a new {{ crud('name_singular') }}
            <a href="{{ route(crud('route') . '.index') }}" class="btn btn-primary pull-xs-right">Back</a>
        </h1>

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <p>
                    There was a problem.
                    Please fix the errors below and re-submit the form.
                </p>
            </div>
        @endif

        @include('crud::partials.messages')

        {!! Form::model($item, [
            'method' => 'POST',
            'route' => crud('route') . '.store',
            'files' => $has_files
        ]) !!}
            @if (view()->exists(crud('views_dir') . '.partials.form'))
                @include(crud('views_dir') . '.partials.form', [
                    'item' => $item,
                    'submitText' => 'Save'
                ])
            @else
                @include('crud::partials.form', [
                    'item' => $item,
                    'submitText' => 'Save'
                ])
            @endif
        {!! Form::close() !!}
    </div>
@endsection

@section('footer_variables')
  @include('crud::partials.footer_script_enqueue')
@stop
