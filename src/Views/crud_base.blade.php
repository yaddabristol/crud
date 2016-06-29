<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>CRUD!</title>
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/medium-editor/5.21.0/css/medium-editor.min.css" type="text/css" media="screen" charset="utf-8">
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/medium-editor/5.21.0/css/themes/bootstrap.min.css" type="text/css" media="screen" charset="utf-8">
    </head>
    <body>
        @yield('content')
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js" type="text/javascript"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/medium-editor/5.21.0/js/medium-editor.min.js"></script>
        <script type="text/javascript" src="{{ asset('crud/crud.js') }}"></script>
    </body>
</html>
