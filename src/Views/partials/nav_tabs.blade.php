@if(count($tabs) > 1)
  <ul class="nav nav-tabs">
    @foreach($tabs as $tab_name => $x)
       <li{{ (reset($tabs) === $x) ? ' class=active' : '' }}>
         <a data-toggle="tab" href="#tab-{{ strtolower(preg_replace('#[^a-zA-Z0-9]+#', '-', $tab_name)) }}">
           {{ $tab_name }}
         </a>
       </li>
    @endforeach
  </ul>
@endif
