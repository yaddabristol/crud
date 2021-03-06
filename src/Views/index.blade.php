@extends(config('crud.base_view'))

@section('content')
  <div class="container">
    <h1>
      {{ crud('name_plural') }}
      <a href="{{ route(crud('route') . '.create') }}" class="btn btn-primary pull-xs-right">Create</a>
    </h1>

    <hr>

    @include('crud::partials.index_head')

    @include('crud::partials.messages')

    <table class="table table-hover"> 
      <thead>
        @include('crud::partials.index_table_head')
      </thead>
      <tbody id="{{ strtolower(crud('name_plural')) }}">
        @foreach ($items as $item)
          <tr>
            @foreach (crud('table_columns') as $name => $value)
              @if(is_array($value))
                <td>{!! $item->$value[0] !!}</td>
              @else
                <td>{{ $item->$value }}</td>
              @endif
            @endforeach
            <td>
              {!! Form::open([
                'route' => [crud('route') . '.destroy', $item->id],
                'method' => 'DELETE'
                ]) !!}
                <a href="{{ route(crud('route') . '.edit', $item->id) }}" class="btn btn-secondary">Edit</a>
                <button class="btn btn-danger" type="submit">Delete</button>
                {!! Form::close() !!}
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>

      @include('crud::partials.index_foot')
  </div>
@endsection

@section('footer_variables')
  @include('crud::partials.footer_script_enqueue')
@stop