<?php
class AntrianController extends CI_Controller {

    // Fungsi memanggil Google Cloud Text-to-Speech API
    public function googleCloudTTS($text) {
        $url = 'https://texttospeech.googleapis.com/v1/text:synthesize?key=YOUR_API_KEY';
        $data = [
            'input' => ['text' => $text],
            'voice' => ['languageCode' => 'id-ID', 'name' => 'id-ID-Wavenet-D'],  // Suara Bahasa Indonesia
            'audioConfig' => ['audioEncoding' => 'MP3']
        ];

        $options = [
            'http' => [
                'header' => "Content-type: application/json\r\n",
                'method' => 'POST',
                'content' => json_encode($data),
            ],
        ];

        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        $audioContent = json_decode($response, true)['audioContent'];

        // Simpan file audio di folder tertentu
        file_put_contents('./uploads/tts_output.mp3', base64_decode($audioContent));
    }

    // Fungsi untuk memanggil antrian dan memutar suara
    public function panggil_antrian($jenis, $loket) {
        // Ambil nomor antrian
        $antrian = $this->Antrian_model->get_antrian_berikutnya($jenis, $loket);
        if ($antrian) {
            $this->Antrian_model->update_status($antrian->id_antrian, 'dilayani', $loket);

            // Panggil Text-to-Speech untuk mengucapkan nomor antrian
            $textToSpeak = "Nomor antrian " . $antrian->nomor_antrian . ", menuju loket " . $loket;
            $this->googleCloudTTS($textToSpeak);

            // Kirim nomor antrian ke view atau frontend
            echo json_encode($antrian);
        } else {
            echo json_encode(null); // Jika tidak ada antrian yang menunggu
        }
    }
}
