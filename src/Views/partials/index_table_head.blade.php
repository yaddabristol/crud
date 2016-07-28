@foreach (crud('table_columns') as $name => $value)
  <th>{{ (is_numeric($name) ? $value : $name ) }}</th>
@endforeach
<th>
  Actions
</th>