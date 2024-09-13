<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ambil Antrian</title>
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
    </style>
</head>
<body>
    <h1>Ambil Antrian</h1>
    <div class="container">
        <button id="ambil_antrian">Ambil Antrian Pendaftaran</button>
        <div id="nomor_antrian" style="margin-top: 20px;">Nomor Antrian: -</div>
    </div>

    <script>
        $('#ambil_antrian').click(function() {
            $.getJSON('<?= site_url('AntrianBaruController/tambah_antrian/pendaftaran') ?>', function(data) {
                if (data) {
                    $('#nomor_antrian').text('Nomor Antrian: ' + data.nomor_antrian);
                }
            });
        });
    </script>
</body>
</html>
