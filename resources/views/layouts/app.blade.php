<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="{{ URL::asset('css/app.css') }}" rel="stylesheet"> 

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#menu-toggle').click(function() {
                    $('#menu-list').toggleClass('collapsed');
                });
            });
    </script>
    <script>
    $(document).ready(function() {
        // Show the popup when the "Add" button is clicked
        $('#add-member-button').click(function(e) {
            e.preventDefault();
            $('#add-member-popup').fadeIn();
            $('#add-member-popup').removeClass('edit-mode');
            $('#add-member-form')[0].reset();
        });

        // Open the popup form when a row is selected
        $('table').on('click', 'tr', function() {
            // Get the selected row data
            var rowData = $(this).find('td').map(function() {
                return $(this).text();
            }).get();

            // Fill the form fields with the selected row data
            $('#name').val(rowData[1]);
            $('#mobile_number').val(rowData[2]);
            $('#savings').val(rowData[3]);
            $('#arrears').val(rowData[4]);
            $('#monthly_payment').val(rowData[5]);

            // Get the member ID from the first column
            var memberId = $(this).find('td:first-child').text();

            // Set the member ID as a hidden input value in the form
            $('#member_id').val(memberId);

            // Change button text to "Edit"
            $('#submit-button').text('Edit');
            $('#form-title').text('Edit Member');

            // Add "edit-mode" class to distinguish edit mode
            $('#add-member-popup').addClass('edit-mode');

            // Show the popup form
            $('#add-member-popup').fadeIn();
        });

        // Close the popup when the close button is clicked
        $('#close-popup').click(function() {
            $('#add-member-popup').fadeOut();
            $('#add-member-popup').removeClass('edit-mode');
            $('#submit-button').text('Add');
            $('#form-title').text('Add Member');
        });
    });
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>


</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                @auth
                    <aside>
                    <div class="menu-button">
                        <button id="menu-toggle">Menu</button>
                    </div>
                    <ul id="menu-list">
                            <li><a href="{{ route('members.index') }}" class="dropdown-item">Members</a></li>
                            <li><a href="{{ route('transactions.index') }}" class="dropdown-item">Transactions</a></li>
                            <li><a href="{{ route('account.index') }}" class="dropdown-item">My Account</a></li>
                        </ul>
                    </aside>
                @endauth
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
            @yield('content')
        </main>
    </div>
</body>
</html>
