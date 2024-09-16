<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Nomor Antrian</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>Display Nomor Antrian</h1>
    <p id="nomor_display">Nomor Antrian Dipanggil: -</p>

    <script type="text/javascript">
        var lastNomor = '';  // Variabel untuk menyimpan nomor antrian terakhir yang ditampilkan

        // Fungsi untuk memperbarui nomor antrian dan memutar audio
        function updateAntrian(nomor, loket) {
            console.log("Update Antrian Dipanggil:", nomor, loket); // Debug log
            $('#nomor_display').text('Nomor Antrian Dipanggil: ' + nomor);

            // Panggil fungsi untuk memutar audio
            playAudio(nomor, loket);
        }

        // Fungsi untuk memutar audio
        function playAudio(nomorAntrian, loket) {
            var audioPath = "<?= base_url('audio/') ?>";
            
            var nomor = nomorAntrian.replace('P', ''); // Menghapus huruf "P" dari nomor antrian
            console.log("Playing audio for:", nomor, loket); // Debug log

            // Urutan pemutaran audio: "antrian.wav", "p.wav", nomor.wav per digit, "counter.wav", nomor loket.wav
            var audioQueue = [
                new Audio(audioPath + 'antrian.wav'),  // antrian.wav
                new Audio(audioPath + 'p.wav')         // p.wav
            ];

            // Tambahkan setiap digit nomor antrian ke dalam queue
            for (var i = 0; i < nomor.length; i++) {
                audioQueue.push(new Audio(audioPath + nomor[i] + '.wav'));
            }

            // Tambahkan counter.wav dan nomor loket.wav
            audioQueue.push(new Audio(audioPath + 'counter.wav'));
            audioQueue.push(new Audio(audioPath + loket + '.wav'));

            // Fungsi untuk memainkan audio secara berurutan
            function playQueue(index) {
                if (index < audioQueue.length) {
                    console.log("Playing audio:", audioQueue[index].src); // Debug log
                    audioQueue[index].play();
                    audioQueue[index].onended = function() {
                        playQueue(index + 1);
                    };
                }
            }

            // Mulai pemutaran audio dari urutan pertama
            playQueue(0);
        }

        // Polling setiap 2 detik untuk mengecek nomor terbaru yang dipanggil
        setInterval(function() {
            $.getJSON('<?= base_url('AntrianController/get_antrian_terpanggil') ?>', function(data) {
                if (data.status === 'success' && data.nomor !== lastNomor) {
                    lastNomor = data.nomor;  // Simpan nomor terbaru
                    updateAntrian(data.nomor, data.loket);
                }
            });
        }, 2000);  // Polling setiap 2 detik
    </script>
</body>
</html>
