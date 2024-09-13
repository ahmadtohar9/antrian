<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Antrian</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            text-align: center;
            width: 80%;
            max-width: 1000px;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.5);
        }
        h1 {
            font-size: 48px;
            margin-bottom: 20px;
            text-transform: uppercase;
            color: #ffcc00;
        }
        .nomor-antrian {
            font-size: 64px;
            color: #ffcc00;
            margin: 20px 0;
        }
        .loket {
            font-size: 32px;
            color: #ffcc00;
            margin: 10px 0;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Nomor Antrian yang Dipanggil</h1>

        <!-- Nomor antrian yang sedang dipanggil -->
        <div class="nomor-antrian" id="nomor_antrian">Nomor: -</div>
        <div class="loket" id="loket_antrian">Loket: -</div>
    </div>

    <!-- Audio elements for number playback -->
    <audio id="antrian_audio" src="<?= base_url('audio/antrian.wav') ?>"></audio>
    <audio id="huruf_audio" src=""></audio>
    <audio id="digit_audio1" src=""></audio>
    <audio id="digit_audio2" src=""></audio>
    <audio id="digit_audio3" src=""></audio>
    <audio id="counter_audio" src="<?= base_url('audio/counter.wav') ?>"></audio>
    <audio id="loket_audio" src=""></audio>

    <script>
        // Fungsi untuk memutar file audio secara berurutan
        function playQueueAudio(queueNumber, loket) {
            // Map nomor antrian ke file audio
            var huruf = queueNumber.charAt(0).toLowerCase(); // Misal: 'p'
            var digit1 = queueNumber.charAt(1); // Misal: '0'
            var digit2 = queueNumber.charAt(2); // Misal: '1'
            var digit3 = queueNumber.charAt(3); // Misal: '2'
            var loketChar = loket.toLowerCase(); // Misal: 'a', 'b', 'c'

            // Set source audio untuk setiap file
            var antrianAudio = document.getElementById('antrian_audio');
            var hurufAudio = document.getElementById('huruf_audio');
            var digitAudio1 = document.getElementById('digit_audio1');
            var digitAudio2 = document.getElementById('digit_audio2');
            var digitAudio3 = document.getElementById('digit_audio3');
            var counterAudio = document.getElementById('counter_audio');
            var loketAudio = document.getElementById('loket_audio');

            // Path file audio disesuaikan dengan folder 'audio'
            hurufAudio.src = '<?= base_url('audio/') ?>' + huruf + '.wav';
            digitAudio1.src = '<?= base_url('audio/') ?>' + digit1 + '.wav';
            digitAudio2.src = '<?= base_url('audio/') ?>' + digit2 + '.wav';
            digitAudio3.src = '<?= base_url('audio/') ?>' + digit3 + '.wav';
            loketAudio.src = '<?= base_url('audio/') ?>' + loketChar + '.wav';

            // Mainkan audio secara berurutan
            antrianAudio.play();
            antrianAudio.onended = function() {
                hurufAudio.play();
            };
            hurufAudio.onended = function() {
                digitAudio1.play();
            };
            digitAudio1.onended = function() {
                digitAudio2.play();
            };
            digitAudio2.onended = function() {
                digitAudio3.play();
            };
            digitAudio3.onended = function() {
                counterAudio.play();
            };
            counterAudio.onended = function() {
                loketAudio.play();
            };
        }

        // Fungsi untuk memperbarui tampilan dan memutar audio
        window.updateDisplay = function(nomorAntrian, loket) {
            $('#nomor_antrian').text('Nomor: ' + nomorAntrian);
            $('#loket_antrian').text('Loket: ' + loket);
            
            // Putar audio sesuai nomor antrian dan loket
            playQueueAudio(nomorAntrian, loket);
        };
    </script>

</body>
</html>