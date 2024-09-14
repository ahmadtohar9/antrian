$(document).ready(function() {
    var ws = new WebSocket('ws://localhost:8080/antrian');

    $('#ambil_antrian').click(function() {
        $.getJSON('<?= site_url("AntrianBaruController/tambah_antrian/pendaftaran") ?>', function(data) {
            if (data) {
                $('#nomor_antrian').text('Nomor Antrian: ' + data.nomor_antrian);
            }
        });
    });

    $('#panggil_antrian').click(function() {
        var selectedLoket = $('#loket').val();
        $.getJSON('<?= site_url("AntrianBaruController/panggil_antrian/pendaftaran") ?>/' + selectedLoket, function(data) {
            if (data) {
                $('#nomor_antrian').text('Nomor Antrian: ' + data.nomor_antrian);
                ws.send(JSON.stringify({ action: "call", nomor_antrian: data.nomor_antrian, loket: selectedLoket }));
            } else {
                alert('Tidak ada antrian yang menunggu.');
            }
        });
    });

    $('#panggil_ulang').click(function() {
        if (lastAntrian && lastLoket) {
            $('#nomor_antrian').text('Nomor Antrian: ' + lastAntrian);
            ws.send(JSON.stringify({ action: "recall", nomor_antrian: lastAntrian, loket: lastLoket }));
        } else {
            alert('Belum ada antrian yang dipanggil.');
        }
    });

    // WebSocket listener for the display
    ws.onmessage = function(event) {
        var data = JSON.parse(event.data);
        $('#nomor_antrian').text('Nomor: ' + data.nomor_antrian);
        $('#loket_antrian').text('Loket: ' + data.loket);
        playQueueAudio(data.nomor_antrian, data.loket);
    };
});
