<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} Documentation</title>





    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    
    <style>

        .navbar {
            position: fixed !important;
            width: 100% !important;
            z-index: 9999 !important;
            height: 40px !important;
        }

        .content {
            padding-top: 40px !important;
        }

        h1, h2, h3 {
            float: left;
            margin-right: 30px;
        }

        h1 {
            text-decoration: underline;
        }

        .inactive {
            background-color: rgb(255, 178, 164) !important;
        }

        .class_type-wrapper,
        .namespace-wrapper,
        .class-wrapper {
            padding: 10px;
            margin: 20px;
            border-radius: 10px;
        }

        .class-wrapper {
            border: 1px solid rgb(196, 196, 196);
        }
        
        

        /* CHECKBOX */

        input[type=checkbox] + label {
            display: block;
            cursor: pointer;
            margin-bottom: 0px;
            float: left;
        }

        input[type=checkbox] {
            display: none;
        }

        input[type=checkbox] + label:before {
            content: "\2714";
            border: 0.1em solid #000;
            border-radius: 0.2em;
            display: inline-block;
            padding: 6px;
            vertical-align: bottom;
            color: transparent;
            transition: .2s;
        }

        input[type=checkbox] + label:active:before {
            transform: scale(0);
        }

        input[type=checkbox]:checked + label:before {
            background-color: rgb(120, 224, 167);
            border-color: MediumSeaGreen;
            color: #fff;
        }
        input[type=checkbox]:not(:checked) + label:before {
            content: "\2716";
            background-color: rgb(241, 115, 93);
            border-color: rgb(179, 60, 60);
            color: #fff;
        }
        input[type=checkbox]:disabled + label:before {
            transform: scale(1);
            border-color: #aaa;
        }

        input[type=checkbox]:checked:disabled + label:before {
            transform: scale(1);
            background-color: #bfb;
            border-color: #bfb;
        }

    </style>  

    <!-- Styles -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                        <li class="navbar-brand"><a href="{{ route('ldocs-index') }}">LDocs</a></li>
                        <li class="nav-link"><a href="{{ route('ldocs-edit') }}">Edit</a></li>
                        <li class="nav-link"><a href="{{ route('ldocs-scan-project') }}">Scan Project</a></li>

                    </ul>

                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


    @yield('scripts')

</body>
</html>
