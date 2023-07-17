<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=SITE_MIN_WIDTH, initial-scale=1, maximum-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

    <title>Hello, world!</title>
</head>

<body>
    <h1>Hello, world!</h1>

    <div class="card text-center" style="width: 35rem;">
        <div id="reader" width="600px"></div>
        <div>


            <input hidden name="result" id="result">

            <!-- Optional JavaScript; choose one of the two! -->

            <!-- Option 1: Bootstrap Bundle with Popper -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
                crossorigin="anonymous"></script>



</body>
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>

    function onScanSuccess(decodedText, decodedResult) {
        // handle the scanned code as you like, for example:
        // console.log(`Code matched = ${decodedText}`, decodedResult);
        // alert("alhamdulilah")

        $('#result').val(decodedText);
        let id = decodedText;
        html5QrcodeScanner.clear().then(_ => {
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });

            $.ajax({

                url: "/validasi",
                type: 'POST',
                data: {
                    _methode: "POST",
                    qr_code: id
                },
                success: function (response) {
                    console.log(response);
                    if (response.status == 200) {
                        alert('berhasil');
                        location.reload();
                        
                    } 
                    
                    else if (response.status == 300) {
                        alert('udah pernah scan ente!');
                        location.reload();
                    } 
                    else if(response.status == 400){
                        alert('salah bos qr ente!');
                        location.reload();
                    }

                    else{
                        alert(response);
                        location.reload();
                    }

                }
            });
        }).catch(error => {
            console.log(error);
            alert('something wrong');
            location.reload();
        });



    }

    function onScanFailure(error) {
        // handle scan failure, usually better to ignore and keep scanning.
        // for example:
        // console.warn(`Code scan error = ${error}`);
    }

    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader",
        { fps: 10, qrbox: { width: 250, height: 250 } },
  /* verbose= */ false);
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);


</script>

</html>