<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AntrianController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('AntrianModel');
        $this->load->model('LoketModel');
    }

    // Menampilkan view untuk ambil antrian
    public function view_ambil_antrian() {
        $this->load->view('ambil_antrian');
    }

    // Menampilkan view untuk panggil antrian
    public function view_panggil_antrian() {
        $data['loket'] = $this->LoketModel->get_all_loket();
        $this->load->view('panggil_antrian',$data);
    }

    // Menampilkan view untuk display antrian
    public function view_display_antrian() {
        $this->load->view('display_antrian');
    }

    // Fungsi untuk mengambil nomor antrian baru
    public function ambil_antrian() {
        if ($this->input->is_ajax_request()) {
            $nomor_antrian = $this->AntrianModel->ambil_antrian();
            $response = [
                'status' => 'success',
                'nomor' => $nomor_antrian
            ];
            echo json_encode($response);
        } else {
            show_404();
        }
    }

    // Fungsi untuk memanggil nomor antrian
    public function panggil_antrian() {
        if ($this->input->is_ajax_request()) {
            $loket = $this->input->post('loket');
            $nomor_terpanggil = $this->AntrianModel->panggil_antrian($loket);
            if ($nomor_terpanggil) {
                $this->AntrianModel->simpan_nomor_terpanggil($nomor_terpanggil, $loket);
            }
            $response = [
                'status' => $nomor_terpanggil ? 'success' : 'error',
                'nomor' => $nomor_terpanggil ? $nomor_terpanggil : null
            ];
            echo json_encode($response);
        } else {
            show_404();
        }
    }

    // Fungsi untuk memanggil ulang nomor antrian
   public function panggil_ulang_antrian() {
    if ($this->input->is_ajax_request()) {
        $nomor = $this->input->post('nomor');
        $loket = $this->input->post('loket');

        // Simpan log panggilan ulang ke database
        $this->AntrianModel->simpan_nomor_terpanggil($nomor, $loket, 'ulang');

        $response = [
            'status' => 'success',
            'nomor' => $nomor
        ];

        echo json_encode($response);
    } else {
        show_404();
    }
}


    // Fungsi untuk mendapatkan nomor antrian terakhir yang dipanggil
    public function get_antrian_terpanggil() {
    $nomor_terakhir = $this->AntrianModel->get_nomor_terpanggil_terakhir();
    
    // Tambahkan log untuk debugging
    if ($nomor_terakhir) {
        log_message('debug', 'Nomor terpanggil terakhir: ' . $nomor_terakhir->nomor . ' Loket: ' . $nomor_terakhir->loket);
    } else {
        log_message('debug', 'Tidak ada nomor antrian yang dipanggil.');
    }

    if ($nomor_terakhir) {
        $response = [
            'status' => 'success',
            'nomor' => $nomor_terakhir->nomor,
            'loket' => $nomor_terakhir->loket
        ];
    } else {
        $response = [
            'status' => 'error',
            'nomor' => null,
            'loket' => null
        ];
    }

    echo json_encode($response);
}

}
