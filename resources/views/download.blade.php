<!DOCTYPE html>
<html>



<body style="background-color: #f1f1f1; ">


    @php
    $sn = 0;
    @endphp

    @foreach($qr_strings as $qrcode)




    <div class="container my-3" style="background-color: white;  border : 5px solid gray; ">
        <div class="row ">
            <div class="my-3 col-md-6 offset-md-3 text-center">
                <strong>TICKET TYPE : SHOW: 19.00 - 21.00 WIB (275.000) </strong>
                <br>
                <strong>​​​​​​​HARGA DILUAR PAJAK TERKAIT DAN BIAYA PLATFORM </strong>
                <br>
                <strong>BACA SYARAT DAN KETENTUAN SEBELUM MEMBELI ​​​​​​​</strong>

            </div>
        </div>
        <div class="row" style="border-bottom: 5px solid grey; border-top: 5px solid grey;  ">
            <div class="my-3 col-md-8 text-center" style="border-top: 5px solid grey; border-bottom: 5px solid grey; 
			border-right: 5px solid grey; padding: 10px">

                <img class="img-fluid" src="{{$banner}}" width="600px" height="400px" style="padding:50px">

            </div>
            <div class="my-3 col-md-4 text-center"
                style="border-top: 5px solid grey; border-bottom: 5px solid grey;  padding: 10px">
                
                <img src="{{$banner}}" width="150px" height="150px">


                <div class="my-3" style="padding: 10px">
                    <strong>E-Ticket</strong>
                    <div class="my-3"><img
                            src="data:image/png;base64, {!! base64_encode(QrCode::size(150)->generate($qrcode->qr_string)) !!} ">
                    </div>

                    <div class="my-3"><strong> Ticket {{$sn+1}} of {{$kuantiti}} ticket </strong></div>

                </div>

            </div>
        </div>

        <div class="row ">
            <div class="my-3 col-md-12">
                <h2 class="text-center" style="color: red;">Term and Condition</h2>
                <br>
                <ul>
                    <li>
                        <b> You are required to fill in your email address, active telephone number, name & gender, and
                            identification number in accordance with each ID card on each purchase. This applies to
                            purchases of
                            1 to 4 tickets. If you buy 4 tickets, it means you have to fill 4 personal
                            information.</b><br>
                        Anda wajib mengisi alamat email, nomor telepon aktif, nama & gender, dan nomor identitas sesuai
                        dengan
                        KTP masing-masing di setiap pembelian. Ketentuan ini berlaku untuk pembelian 1 sampai 4 tiket.
                        Jika Anda
                        membeli 4 tiket, artinya Anda harus mengisi 4 informasi personal.
                    </li>
                    <li>
                        <b> E-voucher will deliver to your registered email</b><br>
                        E-Voucher akan dikirimkan melalui email ke alamat email yang terdaftar pada saat pembelian.
                    </li>
                    <li>
                        <b> Proof of ID is a requirement for every ticket purchased and redemption </b><br>
                        Wajib menunjukkan kartu identitas asli untuk setiap pembelian dan penukaran tiket
                    </li>
                    <li>
                        <b> TIckets are refundable if there is event cancellation</b><br>
                        Tiket dapat di refund apabila ada perubahan waktu acara atau pengumuman acara batal
                    </li>
                    <li>
                        <b> Must follow the seats that have been provided by the organizer</b><br>
                        Wajib mengikuti tempat duduk yag telah disediakan oleh penyelenggara
                    </li>
                    <li>
                        <b> Ticket verification via barcode scanning is carried out at the venue wih the following
                            schedule :
                            18.00 - 20.00 WIB.</b><br>
                        Verifikasi tiket melalui scan barcode dilakukan di venue dengan jadwal sebagai berikut : 18.00 -
                        20.00
                        WIB.
                    </li>
                    <li>
                        <b> We are NOT responsible for the lost of e-voucher </b><br>
                        Kami tidak bertanggung jawab atas kehilangan e-voucher
                    </li>
                    <li>
                        <b> NO WEAPON & NO DRUGS</b><br>
                        DILARANG MEMBAWA SENJATA ATAU OBAT-OBATAN TERLARANG
                    </li>
                    <li>
                        <b> We will have every right to refuse and/or discharge entry for ticket holders that does not
                            meet the
                            Term & Condition</b><br>
                        Penyelenggara berhak untuk tidak memberikan izin untuk masuk ke dalam tempat acara apabila
                        syarat-syarat
                        & ketentuan tidak dipenuhi
                    </li>
                </ul>

            </div>
        </div>

        @php
        $sn++;
        @endphp
    </div>
    @endforeach




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous">
	</body >
</html >