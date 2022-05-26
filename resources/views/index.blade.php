<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.98.0">
    <title>Convert PDF to Image</title>
    <!-- Favicons -->
    <link rel="apple-touch-icon" href="{{ asset('favicon.jpg') }}" sizes="180x180">
    <link rel="icon" href="{{ asset('favicon.jpg') }}" sizes="32x32" type="image/png">
    <meta name="theme-color" content="#712cf9">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">

    <link href="{{ asset('styles.css') }}" rel="stylesheet">
</head>
<body class="text-center">

<main class="form-signin m-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 offset-4">

                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                <form action="/" method="post" enctype="multipart/form-data">
                    @csrf
                    <img class="mb-4" src="{{ asset('logo.png') }}" alt="">
                    <h1 class="h3 mb-3 fw-normal">Please upload pdf</h1>

                    <input type="file" class="form-control" name="pdf_file" id="pdf_file" required>

                    <button class="w-100 btn btn-lg btn-primary mt-3" type="submit">Convert to Image</button>

                    <p class="mt-5 mb-3 text-muted">TRIAJA IT Services Private Limited &copy; {{ date('Y') }}</p>
                </form>
            </div>
        </div>
    </div>
</main>



</body>
</html>
