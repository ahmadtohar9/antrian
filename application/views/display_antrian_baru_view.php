<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Antrian</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>
    <div class="container">
        <h1 class="my-5 text-warning">Nomor Antrian yang Dipanggil</h1>
        <div class="nomor-antrian" id="nomor_antrian">Nomor: -</div>
        <div class="loket" id="loket_antrian">Loket: -</div>
    </div>

    <audio id="antrian_audio" src="<?= base_url('audio/antrian.wav') ?>"></audio>
    <audio id="huruf_audio" src=""></audio>
    <audio id="digit_audio1" src=""></audio>
    <audio id="digit_audio2" src=""></audio>
    <audio id="digit_audio3" src=""></audio>
    <audio id="counter_audio" src="<?= base_url('audio/counter.wav') ?>"></audio>
    <audio id="loket_audio" src=""></audio>

    <!-- Define base_url for JavaScript -->
    <script>
        var base_url = '<?= base_url() ?>';
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="<?= base_url('js/display_antrian.js') ?>"></script>
</body>
</html>
