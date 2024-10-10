
<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>


    <link rel="stylesheet" href="{{$url}}/theme/css/theme.css">
    <link rel="stylesheet" href="{{$url}}/theme/css/style.css">
    <link rel="stylesheet" href="{{$url}}/theme/css/main.css">
    <link rel="stylesheet" href="{{$url}}/theme/css/bootstrap.min.css">
    <link rel="shortcut icon" href="{{$url}}/img/facebook-logo-2019.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="bg-gray postion-relative">


    <div id="loading-bar" style="width: 0px; display: none;">
        <dd></dd><dt></dt></div>



   @include('layout/header')
  <div id="content">
   {!!$content!!}

  </div>


    
   

    <script src="{{$url}}/theme/js/functions.js"></script>
    <script src="https://unpkg.com/typeit@8.7.1/dist/index.umd.js"></script>
    <script src="{{$url}}/theme/js/theme.js"></script>
    <script src="{{$url}}/theme/js/main.js"></script>


   
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <!-- Font Awesome for the trash icon -->
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script> --}}

    <script>
        const baseURL ='{{$url}}';
    </script>

    <spt>
        @stack('content_script')
    </spt>
  
    @stack('script')
</body>
</html>
