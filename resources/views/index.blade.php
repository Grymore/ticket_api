<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sound Nation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="{{ asset('/css/style.css') }}"> -->

    <link rel="stylesheet" href="{{ asset('css/main.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <div class="hero-image">
        <div class="hero-text">
            <a href="#section_baru"><button type="button" class="btn btn-primary btn-lg">BELI TIKET</button></a>
        </div>
    </div>

    <section class="detail" id="section_baru">
        <div class="container">

            @include('layouts.event_details')
            <br>
            <br>
            <div class="col-lg-4 bordering" id="section1">
                <!-- <h3 class="text-center">Order</h3> -->

                <h3 class="text-center" style="font-family: 'Brush Script MT', cursive; font-size: 40px;">Konser Gebyar
                    2023</h3>


                <form action="/payment" method="POST" name="myForm" class="order my-5">
                    @csrf
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label"><b>Nama <span
                                    style="color:red">*</span></b></label>
                        <input type="text" class="form-control" onkeyup="btnActivation()" name="nama" id="nama" required
                            placeholder="Nama" size="15">
                        <div class="invalid-feedback">
                            Please provide a valid city.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label"><b>Handphone <span
                                    style="color:red">*</span></b></label>
                        <input type="text" class="form-control" id="hp" name="telpon" onkeyup="btnActivation()" required
                            placeholder="Handphone">
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label"><b>Email <span
                                    style="color:red">*</span></b></label>
                        <input type="email" class="form-control" required onkeyup="btnActivation()" name="email" id="email"
                            placeholder="name@example.com">
                    </div>
                    <br>
                    <br>
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label"><b>Harga Per satuan </b></label>
                        <!-- <input type="text" class="form-control" required id="exampleFormControlInput1"
                            placeholder="name@example.com"> -->
                        <p>IDR 100000</p>
                    </div>
                    <div class="mb-3 col-sm-4">
                        <label for="exampleFormControlInput1" class="form-label"><b>Kuantitas <span
                                    style="color:red">*</span></b></label>
                        <select class="form-select" aria-label="Default select example" name="total"  required id="kuantiti" 
                            onchange="myFunction() ">
                            <option selected>Pilih</option>
                            <option value="1">1 Tiket</option>
                            <option value="2">2 Tiket</option>
                            <option value="3">3 Tiket</option>
                            <option value="4">4 Tiket</option>
                            <option value="5">5 Tiket</option>
                        </select>
                    </div>
                    <div>
                        <input type="text" name="invoices" value="<?php echo 'INV-'. time()?>" hidden>
                        <input type="text" name="status_transaksi" value="PENDING" hidden>
                        <input type="text" name="qr_string" value="" hidden>
                    </div>
                    <div class="my-5">
                        <label for="exampleFormControlInput1" class="form-label" id="show"><b>Harga Tiket</b></label>

                        <br>
                        <label for="exampleFormControlInput1"  class="form-label" id="total">IDR 0</label>


                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="myCheckbox" required>
                        <label class="form-check-label" for="flexCheckDefault">
                            Setuju
                        </label>
                    </div>
                    <small>*Harga tiket belum termasuk pajak dan admin fee</small>

                    <div class="text-center">
                    <br>
                    <button type="submit" id="myBtn"  class="button btn btn-primary" disabled>Lanjtukan</button>

                </div>

                </form>
                

            </div>
        </div>
        </div>
    </section>



    @include('layouts.footer')


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous">


    </script>
</body>

<script>
function myFunction() {
    var jumlah = document.getElementById("kuantiti").value;
    const harga = jumlah * 100000;
    document.getElementById("total").innerHTML = "IDR " + harga




}


function btnActivation() {

    var hp = document.getElementById("hp").value.length
    var nama = document.getElementById("nama").value.length
    var email = document.getElementById("email").value.length


    if (nama && email && hp) {
        document.getElementById("myBtn").disabled = false;
       
    } else {
        document.getElementById("myBtn").disabled = true;
        

    }
}





// var modal = document.getElementById("myModal");


// var isiNama = document.getElementById("nama").value
// console.log(isiNama)
// // document.getElementById("tadis").innerHTML = isiNama

// // Get the button that opens the modal
// var btn = document.getElementById("myBtn");

// // Get the <span> element that closes the modal
// var span = document.getElementsByClassName("close")[0];


// // Get the modal


// // When the user clicks the button, open the modal 
// btn.onclick = function() {
//     var jumlah1 = document.getElementById("kuantiti").value;
    
//     if (jumlah1 > 0) {
//         modal.style.display = "block";
//     } else {
//         alert('Pilih tiket')
//     }
// }

// // When the user clicks on <span> (x), close the modal
// span.onclick = function() {
//     modal.style.display = "none";
// }




</script>

</html>