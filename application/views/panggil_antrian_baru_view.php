<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panggil Antrian</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>
    <div class="container">
        <h1 class="my-5">Panggil Antrian</h1>
        <label for="loket">Pilih Loket:</label>
        <select id="loket" class="form-control mb-3">
            <option value="1">Loket 1</option>
            <option value="2">Loket 2</option>
            <option value="3">Loket 3</option>
        </select>
        <button id="panggil_antrian" data-url="<?= site_url('AntrianBaruController/panggil_antrian/pendaftaran') ?>">Panggil Antrian Berikutnya</button>
        <button id="panggil_ulang" style="display:none;">Panggil Ulang Antrian</button>
        <div id="nomor_antrian" class="mt-3">Nomor Antrian: -</div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="<?= base_url('js/panggil_antrian.js') ?>"></script>
</body>
</html>


