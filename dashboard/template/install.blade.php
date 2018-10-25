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
    <link href="{{ $base }}/assets/css/style.css" rel="stylesheet">
    <style>
        .icon-check {
            color: #2ecc71;
        }
        .icon-close {
            color: #e74c3c;
        }
    </style>
</head>

<body>

    <div id="wrapper" class="container mt-4">
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <img src="{{ $base }}/assets/img/logo.png" alt="Journal logo" class="img-fluid">
            <h1>Welcome to Journal</h1>
            @if ($has_errors)
            <p>Before we begin please make sure Journal has enough permissions to read and write to these folders:</p>
            <ul>
                    <li>
                        @if ($read_tables)
                            <span class="icon-check"></span>
                        @else
                            <span class="icon-close"></span>
                        @endif
                        Journal can read and write to the <code>tables/</code> folder
                    </li>
                    <li>
                        @if ($read_public)
                            <span class="icon-check"></span>
                        @else
                            <span class="icon-close"></span>
                        @endif
                        Journal can read and write to the <code>public/</code> folder
                    </li>
            </ul>
            <a href="{{ $base }}/install" class="btn btn-block btn-outline-dark"><span class="icon-refresh"></span> Re-check permissions</a>
            @else
            <p>Welcome to Journal - a simple CMS for creating a static blog. Your webserver already seems to be set up correctly so we can continue with setting up Journal.<br />
                You can start by creating a new post right away but we advice you to look through Journals settings once to fit your blog.</p>
            <p>If you have any problems please don't hesitate to look at <a href="https://github.com/vantezzen/journal/wiki">Journals documentation</a> or <a href="https://github.com/vantezzen/journal/issues">create a new issue on GitHub</a>.</p>
            <form action="{{ $base }}/install" method="post">
                <button type="submit" class="btn btn-block btn-outline-dark"><span class="icon-rocket"></span> Set up Journal</button>
            </form>
            @endif
        </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- Bootstrap core JavaScript -->
    <script src="{{ $base }}/assets/js/jquery.min.js"></script>
    <script src="{{ $base }}/assets/js/bootstrap.min.js"></script>


</body>

</html>