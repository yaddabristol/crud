@if(!empty(crud('variables')))
  <script type="text/javascript">
    var crud = {!! json_encode(crud('variables')) !!};
  </script>
@endif