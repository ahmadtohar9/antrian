var ws;

$(document).ready(function() {
    // Buka WebSocket connection ke server
    // ws = new WebSocket('ws://localhost:8080/antrian');
    var ws = new WebSocket('ws://localhost:8081/antrian');


    // Terima pesan dari WebSocket server
    ws.onmessage = function(event) {
        var data = JSON.parse(event.data);
        $('#nomor_antrian').text('Nomor: ' + data.nomor_antrian);
        $('#loket_antrian').text('Loket: ' + data.loket);

        // Play audio for the queue number and loket
        playQueueAudio(data.nomor_antrian, data.loket);
    };
});

// Fungsi untuk memutar audio sesuai nomor antrian
function playQueueAudio(queueNumber, loket) {
    var huruf = queueNumber.charAt(0).toLowerCase();
    var digit1 = queueNumber.charAt(1);
    var digit2 = queueNumber.charAt(2);
    var digit3 = queueNumber.charAt(3);

    // Set source audio untuk setiap file
    var antrianAudio = document.getElementById('antrian_audio');
    var hurufAudio = document.getElementById('huruf_audio');
    var digitAudio1 = document.getElementById('digit_audio1');
    var digitAudio2 = document.getElementById('digit_audio2');
    var digitAudio3 = document.getElementById('digit_audio3');
    var counterAudio = document.getElementById('counter_audio');
    var loketAudio = document.getElementById('loket_audio');

    // Set the file paths based on the base folder using the global `base_url`
    hurufAudio.src = base_url + 'audio/' + huruf + '.wav';
    digitAudio1.src = base_url + 'audio/' + digit1 + '.wav';
    digitAudio2.src = base_url + 'audio/' + digit2 + '.wav';
    digitAudio3.src = base_url + 'audio/' + digit3 + '.wav';
    loketAudio.src = base_url + 'audio/' + loket + '.wav';

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
