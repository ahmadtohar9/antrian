var ws; // Inisialisasi WebSocket
var lastAntrian = null; // Menyimpan nomor antrian terakhir
var lastLoket = null; // Menyimpan loket terakhir

$(document).ready(function() {
    // WebSocket Connection
    ws = new WebSocket('ws://localhost:8080/antrian');

    $('#panggil_antrian').click(function() {
        var selectedLoket = $('#loket').val();
        var url = $(this).data('url'); // Ambil URL dari data attribute di HTML
        $.getJSON(url + '/' + selectedLoket, function(data) {
            if (data) {
                $('#nomor_antrian').text('Nomor Antrian: ' + data.nomor_antrian);
                $('#panggil_ulang').show(); // Tampilkan tombol panggil ulang
                lastAntrian = data.nomor_antrian; // Simpan nomor antrian terakhir
                lastLoket = selectedLoket; // Simpan loket terakhir

                // Kirim data ke WebSocket server
                if (ws.readyState === WebSocket.OPEN) {
                    ws.send(JSON.stringify({ action: "call", nomor_antrian: data.nomor_antrian, loket: selectedLoket }));
                }
            } else {
                alert('Tidak ada antrian yang menunggu.');
            }
        });
    });

    // Fungsi panggil ulang
    $('#panggil_ulang').click(function() {
        if (lastAntrian && lastLoket) {
            $('#nomor_antrian').text('Nomor Antrian: ' + lastAntrian);

            // Kirim ulang data ke WebSocket server
            if (ws.readyState === WebSocket.OPEN) {
                ws.send(JSON.stringify({ action: "recall", nomor_antrian: lastAntrian, loket: lastLoket }));
            }
        } else {
            alert('Belum ada antrian yang dipanggil.');
        }
    });
});
