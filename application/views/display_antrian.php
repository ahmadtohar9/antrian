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
        function updateAntrian(nomor, loket, tipe = 'normal') {
            console.log("Fungsi updateAntrian dijalankan");
            console.log("Nomor yang akan di-update:", nomor, "Loket:", loket, "Tipe:", tipe);

            $('#nomor_display').text('Nomor Antrian Dipanggil: ' + nomor + ' Loket: ' + loket + ' Tipe: ' + tipe);

            // Fungsi untuk memutar audio bisa diaktifkan sesuai kebutuhan
            playAudio(nomor, loket);
        }

        // Fungsi untuk memutar audio
        function playAudio(nomorAntrian, loket) {
            var audioPath = "<?= base_url('audio/') ?>";
            var nomor = nomorAntrian.replace('P', ''); // Menghapus huruf "P" dari nomor antrian

            console.log("Memutar audio untuk nomor:", nomor, "loket:", loket);

            var audioQueue = [
                new Audio(audioPath + 'antrian.wav'),  // antrian.wav
                new Audio(audioPath + 'p.wav')         // p.wav
            ];

            for (var i = 0; i < nomor.length; i++) {
                audioQueue.push(new Audio(audioPath + nomor[i] + '.wav'));
            }

            audioQueue.push(new Audio(audioPath + 'counter.wav'));
            audioQueue.push(new Audio(audioPath + loket + '.wav'));

            function playQueue(index) {
                if (index < audioQueue.length) {
                    audioQueue[index].play();
                    audioQueue[index].onended = function() {
                        playQueue(index + 1);
                    };
                }
            }

            playQueue(0);
        }

        // Polling untuk mengecek nomor terbaru yang dipanggil dari server
        setInterval(function() {
            console.log("Polling: Checking for updates...");
            $.getJSON('<?= base_url('AntrianController/get_antrian_terpanggil') ?>', function(data) {
                if (data.status === 'success' && data.nomor !== lastNomor) {
                    console.log("Data diterima dari server:", data);
                    lastNomor = data.nomor;
                    updateAntrian(data.nomor, data.loket, 'normal');
                } else {
                    console.log("Nomor antrian tidak berubah:", lastNomor);
                }
            });
        }, 2000);

        // Event listener untuk menerima event panggil ulang dari halaman lain
        window.addEventListener("panggilUlang", function(event) {
            var data = event.detail;
            console.log("Event panggil ulang diterima:", data);
            updateAntrian(data.nomor, data.loket, 'ulang');  // Panggil updateAntrian dengan tipe 'ulang'
        });
    </script>
</body>
</html>
