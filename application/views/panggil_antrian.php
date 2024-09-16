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
        <?php foreach ($loket as $l): ?>
            <option value="<?= $l->id ?>"><?= $l->nama_loket ?></option>
        <?php endforeach; ?>
    </select>

    <button id="panggil-antrian">Panggil Antrian</button>
    <button id="panggil-ulang-antrian" disabled>Panggil Ulang Antrian</button> <!-- Tombol Panggil Ulang -->

    <p>Nomor Antrian Dipanggil: <span id="nomor-antrian">null</span></p>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
      $(document).ready(function() {
        let nomorTerakhir = null;  // Variabel untuk menyimpan nomor terakhir yang dipanggil
        let loketTerakhir = null;  // Variabel untuk menyimpan loket terakhir yang dipanggil

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
                        loketTerakhir = loket;  // Simpan loket terakhir
                        $('#panggil-ulang-antrian').prop('disabled', false);  // Aktifkan tombol Panggil Ulang

                        // Simpan ke localStorage agar halaman display mendeteksi perubahan
                        localStorage.setItem('nomorTerpanggil', JSON.stringify({ nomor: data.nomor, loket: loket }));
                    } else {
                        alert('Tidak ada antrian yang tersedia.');
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan saat memanggil antrian.');
                }
            });
        });

     $('#panggil-ulang-antrian').on('click', function() {
    if (nomorTerakhir) {
        var loket = loketTerakhir;
        $.ajax({
            url: '<?= base_url('AntrianController/panggil_ulang_antrian') ?>',
            type: 'POST',
            data: { nomor: nomorTerakhir, loket: loket },
            success: function(response) {
                var data = JSON.parse(response);
                if (data.status === 'success') {
                    $('#nomor-antrian').text(data.nomor);

                    // Trigger event panggil ulang ke display
                    var event = new CustomEvent("panggilUlang", {
                        detail: { nomor: data.nomor, loket: loket }
                    });
                    window.dispatchEvent(event);  // Kirim event ke display

                    alert('Panggil ulang antrian berhasil.');
                } else {
                    alert('Terjadi kesalahan saat memanggil ulang antrian: ' + data.message);
                }
            },
            error: function() {
                alert('Terjadi kesalahan saat memanggil ulang antrian.');
            }
        });
    } else {
        alert('Nomor antrian terakhir tidak ditemukan.');
    }
});



    });
</script>

</body>
</html>
