<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Journal</title>

    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> 
    <link href="{{ $base }}/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ $base }}/assets/css/simple-line-icons.css" rel="stylesheet">
    <link href="{{ $base }}/assets/css/sidebar.css" rel="stylesheet">
    <link href="{{ $base }}/assets/css/main.css" rel="stylesheet">
    <link href="{{ $base }}/assets/css/write.css" rel="stylesheet">

</head>

<body>

    <div id="wrapper">

        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li class="sidebar-brand">
                    <a href="{{ $base }}">
                        <span class="icon-pencil"></span> Journal
                    </a>
                </li>
                <li>
                    <a href="{{ $base }}"><span class="icon-layers"></span> Posts</a>
                </li>
                <li>
                    <a href="{{ $base }}/write"><span class="icon-note"></span> Create new</a>
                </li>
                <li>
                    <a href="{{ $base }}/menu"><span class="icon-list"></span> Menu</a>
                </li>
                <li>
                    <a href="{{ $base }}/settings">
                        <span class="icon-settings"></span> 
                        @if ($update)
                            <span class="update-notification" title="New update availible"></span>
                        @endif
                        Settings
                    </a>
                </li>
                <hr>
                <li>
                    <a href="{{ $base }}/public/"><span class="icon-eye"></span> View static blog</a>
                </li>
                <!-- <li>
                    <a href="#"><span class="icon-logout"></span> Logout</a>
                </li> -->
            </ul>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-md navbar-light fixed-top bg-light">
                <button class="navbar-toggler" id="menu-toggle"
                    aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </nav>

            @yield('content')
        </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- Bootstrap core JavaScript -->
    <script src="{{ $base }}/assets/js/jquery.min.js"></script>
    <script src="{{ $base }}/assets/js/bootstrap.min.js"></script>

    <!-- Menu Toggle Script -->
    <script>
        $("#menu-toggle").click(function (e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
        });
    </script>

</body>

</html>