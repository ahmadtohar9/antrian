<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ambil Antrian</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>
    <div class="container">
        <h1 class="my-5">Ambil Antrian</h1>
        <button id="ambil_antrian" data-url="<?= site_url('AntrianBaruController/tambah_antrian/pendaftaran') ?>">Ambil Antrian Pendaftaran</button>
        <div id="nomor_antrian" class="mt-3">Nomor Antrian: -</div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="<?= base_url('js/ambil_antrian.js') ?>"></script>

</body>
</html>

