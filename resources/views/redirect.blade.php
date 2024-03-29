<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Sound Nation</title>
</head>

<body style="font-family:Verdana">

    <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
        <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
            <svg class="bi me-2" width="40" height="32">
                <use xlink:href="#bootstrap"></use>
            </svg>
            <span class="fs-4">E-TICKET</span>
        </a>

        <ul class="nav nav-pills">

            <li class="nav-item">
                @if ($title == "SUCCESS")
                <a href="/print_ticket/{{$id}}" class="nav-link"><button class="btn btn-info">Print
                        E-Ticket</button></a>
                @endif
            </li>

        </ul>

    </header>


    <section class="tiket">
        <div class="row">
            <div class="col-sm-12 text-center">
                <h2>Transaksi Anda {{$title}}</h2>
                <br>
                <p class="text-center">{{$body}}</p>
                <img src="{{$gambar}}">
            </div>
        </div>

        <div class="text-center my-3">

            @if ($title == "FAILED")
            <a href="/"><button class="btn btn-primary">Kembali</button></a>
            @elseif($title == "PENDING")
            <a href="/checkstatus/{{$invoice}}"><button class="btn btn-primary">CheckStatus</button></a>
            @endif

        </div>

    </section>


</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous">
</script>

</html>