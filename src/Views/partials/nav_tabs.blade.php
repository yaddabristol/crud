@if(count($tabs) > 1)
  <ul class="nav nav-tabs">
    @foreach($tabs as $tab_name => $x)
       <li>
         <a href="#tab-{{ strtolower(preg_replace('#[^a-zA-Z0-9]+#', '-', $tab_name)) }}" data-toggle="tab"{{ (reset($tabs) === $x) ? ' class=active' : '' }}>
           {{ $tab_name }}
         </a>
       </li>
    @endforeach
  </ul>
@endif
