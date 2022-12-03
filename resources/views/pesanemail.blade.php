<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">


</head>

<body>

  @component('mail::message')

  <img src="https://cdn-doku.oss-ap-southeast-5.aliyuncs.com/doku-ui-framework/doku/img/card/mastercard.png"
    class="gambar" style="height: 100px; width:100px;" />


  Kepada {{ $data['nama'] }},

  Terima kasih telah memesan tiket KONSER GEBYAR 2023


  Proses pemesanan dan pembayaran Anda telah sukses!
  Silahkan mencetak e-voucher yang kami sediakan pada tautan berikut.

  @component('mail::button', ['url' => $data['url']])
  Cetak E-Voucher
  @endcomponent



  Detail Pemesanan anda :

  @component('mail::table')
  | | |
  | :------------- |-------------:|
  | **Kode Invoice** : | {{ $data['invoice'] }} |
  | **Status Pembayaran** : | {{ $data['status'] }} |
  | **Waktu Acara** : | 23 Oktober 2023 |
  | **Lokasi Acara** : | Lapangan Banteng |
  | **Tipe Pembayaran** : | Credit / Debit Card |
  @endcomponent

  Detail Pembayaran anda :
  @component('mail::table')
  | Ringkasan | Harga per Tiket | Kuantitas | Jumlah |
  | :------------- |:-------------| :--------| :--------|
  | Denny Cak Nan| Rp. 100.000 | 2 | Rp. 200.000|
  | Tax | | | Rp. 20.000 |
  | Admin Fee | | | Rp. 14.000 |
  | Biaya Lainnya | | | Rp. 12.000 |
  | **Grand Total** | | | **Rp. 282.000** |
  @endcomponent


  @component('mail::footer')
  Loket.com
  Pasaraya Blok M Gedung B Lt. 5
  Jalan Iskandarsyah II No. 7, Melawai, Kebayoran Baru
  Jakarta Selatan, 12160
  Phone: +62-21-8060-0822
  Email: support@loket.com
  @endcomponent


  @endcomponent

  <!-- Optional JavaScript; choose one of the two! -->

  <!-- Option 1: Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

  <!-- Option 2: Separate Popper and Bootstrap JS -->
  <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
</body>

</html>