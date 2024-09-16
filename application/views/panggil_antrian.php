<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panggil Nomor Antrian</title>
</head>
<body>
    <h1>Panggil Nomor Antrian</h1>

    <!-- Pilih Loket -->
    <label for="loket">Pilih Loket:</label>
    <select id="loket">
        <option value="1">Loket 1</option>
        <option value="2">Loket 2</option>
        <option value="3">Loket 3</option>
        <option value="4">Loket 4</option>
        <option value="5">Loket 5</option>
    </select>

    <button id="panggil-antrian">Panggil Antrian</button>
    <button id="panggil-ulang-antrian" disabled>Panggil Ulang Antrian</button> <!-- Tombol Panggil Ulang -->

    <p>Nomor Antrian Dipanggil: <span id="nomor-antrian">null</span></p>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
      $(document).ready(function() {
        let nomorTerakhir = null;  // Variabel untuk menyimpan nomor terakhir yang dipanggil

        // Fungsi untuk memanggil antrian baru
        $('#panggil-antrian').on('click', function() {
            var loket = $('#loket').val();  // Ambil value dari dropdown loket
            $.ajax({
                url: '<?= base_url('AntrianController/panggil_antrian') ?>',
                type: 'POST',
                data: { loket: loket },  // Kirim data loket
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.status === 'success') {
                        // Tampilkan nomor antrian
                        $('#nomor-antrian').text(data.nomor);
                        nomorTerakhir = data.nomor;  // Simpan nomor terakhir yang dipanggil
                        $('#panggil-ulang-antrian').prop('disabled', false);  // Aktifkan tombol Panggil Ulang

                        // Kirim data ke view display antrian
                        if (window.opener) {
                            window.opener.updateAntrian(data.nomor, loket); // Kirim nomor dan loket ke display antrian
                        }
                    } else {
                        alert('Tidak ada antrian yang tersedia.');
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan saat memanggil antrian.');
                }
            });
        });

        // Fungsi untuk memanggil ulang antrian terakhir
        $('#panggil-ulang-antrian').on('click', function() {
            if (nomorTerakhir) {
                var loket = $('#loket').val();  // Ambil value dari dropdown loket
                $.ajax({
                    url: '<?= base_url('AntrianController/panggil_ulang_antrian') ?>',
                    type: 'POST',
                    data: { nomor: nomorTerakhir, loket: loket },  // Kirim nomor dan loket
                    success: function(response) {
                        var data = JSON.parse(response);
                        if (data.status === 'success') {
                            $('#nomor-antrian').text(data.nomor);
                            // Kirim data ke view display antrian (trigger polling baru di display)
                            if (window.opener) {
                                window.opener.updateAntrian(data.nomor, loket); // Kirim nomor dan loket ke display antrian
                            }
                        }
                    },
                    error: function() {
                        alert('Terjadi kesalahan saat memanggil ulang antrian.');
                    }
                });
            }
        });
     });
    </script>
</body>
</html>
