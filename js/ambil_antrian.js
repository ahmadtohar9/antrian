$(document).ready(function() {
    $('#ambil_antrian').click(function() {
        var url = $(this).data('url'); // Ambil URL dari data attribute di HTML
        $.getJSON(url, function(data) {
            if (data) {
                $('#nomor_antrian').text('Nomor Antrian: ' + data.nomor_antrian);
            }
        });
    });
});
