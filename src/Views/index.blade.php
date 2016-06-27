@extends(config('crud.base_view'))

@section('content')
  <div class="container">
    <h1>
      {{ $name_plural }}
      <a href="{{ route($route . '.create') }}" class="btn btn-primary pull-xs-right">Create</a>
    </h1>

    <hr>

    @include('crud::partials.index_head')

    <table class="table table-hover">
      <thead>
        @foreach ($table_columns as $name => $value)
          <th>{{ $name }}</th>
        @endforeach
        <th>
          Actions
        </th>
      </thead>
      <tbody id="{{ strtolower($name_plural) }}">
        @foreach ($items as $item)
          <tr>
            @foreach ($table_columns as $name => $value)
              <td>{{ $item->$value }}</td>
            @endforeach
            <td>
              {!! Form::open([
                'route' => [$route . '.destroy', $item->id],
                'method' => 'DELETE'
                ]) !!}
                <a href="{{ route($route . '.edit', $item->id) }}" class="btn btn-secondary">Edit</a>
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
