@if(count($tabs) > 1)
  <ul class="nav nav-tabs">
    @foreach($tabs as $tab_name => $x)
       <li class="nav-item">
         <a class="nav-link {{ (reset($tabs) === $x) ? ' active' : '' }}" data-toggle="tab" href="#tab-{{ strtolower(preg_replace('#[^a-zA-Z0-9]+#', '-', $tab_name)) }}">
           {{ $tab_name }}
         </a>
       </li>
    @endforeach
  </ul>
@endif
