<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ambil Antrian</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>Ambil Nomor Antrian</h1>
    <button id="btnAmbilAntrian">Ambil Antrian</button>
    <p id="nomor_ambil">Nomor Antrian Anda akan muncul di sini</p>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#btnAmbilAntrian').click(function() {
                $.getJSON('<?= base_url('AntrianController/ambil_antrian') ?>', function(data) {
                    if (data.status === 'success') {
                        $('#nomor_ambil').text('Nomor Antrian Anda: ' + data.nomor);
                    }
                });
            });
        });
    </script>
</body>
</html>
