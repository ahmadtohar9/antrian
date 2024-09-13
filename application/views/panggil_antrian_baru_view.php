<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panggil Antrian</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .container {
            text-align: center;
            margin-top: 50px;
        }
        button {
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        select {
            padding: 10px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Panggil Antrian</h1>
    <div class="container">
        <!-- Dropdown untuk memilih loket -->
        <label for="loket">Pilih Loket:</label>
        <select id="loket">
            <option value="1">Loket 1</option>
            <option value="2">Loket 2</option>
            <option value="3">Loket 3</option>
            <!-- Tambahkan opsi loket lainnya sesuai kebutuhan -->
        </select>

        <br>

        <!-- Tombol untuk memanggil antrian berikutnya -->
        <button id="panggil_antrian">Panggil Antrian Berikutnya</button>

        <div id="nomor_antrian" style="margin-top: 20px;">Nomor Antrian: -</div>
    </div>

    <script>
    var displayWindow = null;

    $(document).ready(function() {
        // Buka window display antrian hanya jika belum terbuka
        if (!displayWindow || displayWindow.closed) {
            displayWindow = window.open('<?= site_url('AntrianBaruController/display_antrian') ?>', 'Display Antrian', 'width=800,height=600');
            console.log('Jendela display dibuka:', displayWindow);
        }

        // Fungsi untuk memanggil antrian berikutnya
        $('#panggil_antrian').click(function() {
            var selectedLoket = $('#loket').val(); // Ambil loket yang dipilih
            console.log('Loket yang dipilih:', selectedLoket);

            // Panggil antrian berikutnya dan kirimkan informasi loket
            $.getJSON('<?= site_url('AntrianBaruController/panggil_antrian/pendaftaran') ?>/' + selectedLoket, function(data) {
                console.log('Respons dari server:', data);
                if (data) {
                    $('#nomor_antrian').text('Nomor Antrian: ' + data.nomor_antrian);

                    // Kirimkan data untuk memperbarui display antrian
                    if (displayWindow && !displayWindow.closed) {
                        displayWindow.updateDisplay(data.nomor_antrian, selectedLoket);
                        console.log('Data dikirim ke display: ', data.nomor_antrian, selectedLoket);
                    } else {
                        console.log('Jendela display tidak tersedia.');
                    }
                } else {
                    alert('Tidak ada antrian yang menunggu.');
                }
            });
        });
    });

    </script>
</body>
</html>
