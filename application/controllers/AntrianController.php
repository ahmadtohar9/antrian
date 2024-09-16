<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AntrianController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('AntrianModel');
    }

    // Menampilkan view untuk ambil antrian
    public function view_ambil_antrian() {
        $this->load->view('ambil_antrian');
    }

    // Menampilkan view untuk panggil antrian
    public function view_panggil_antrian() {
        $this->load->view('panggil_antrian');
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

    // Fungsi untuk memanggil nomor antrian dan menyimpannya di database
    public function panggil_antrian() {
        if ($this->input->is_ajax_request()) {
            $loket = $this->input->post('loket');  // Ambil data loket dari request
            $nomor_terpanggil = $this->AntrianModel->panggil_antrian($loket); // Kirim loket ke model
            if ($nomor_terpanggil) {
                // Simpan nomor antrian yang dipanggil di database
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

    // Fungsi untuk mendapatkan antrian terakhir yang dipanggil
    public function get_antrian_terakhir() {
        $nomor_terakhir = $this->AntrianModel->get_antrian_terakhir();

        $response = [
            'status' => 'success',
            'nomor' => $nomor_terakhir
        ];

        echo json_encode($response);
    }

    // Fungsi untuk mendapatkan nomor antrian terakhir yang dipanggil
    public function get_antrian_terpanggil() {
        $nomor_terakhir = $this->AntrianModel->get_nomor_terpanggil_terakhir();

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

    // Fungsi untuk memanggil ulang antrian terakhir
    public function panggil_ulang_antrian() {
        if ($this->input->is_ajax_request()) {
            $nomor = $this->input->post('nomor');
            $loket = $this->input->post('loket');

            // Simpan log panggilan ulang ke database
            $this->AntrianModel->simpan_nomor_terpanggil($nomor, $loket, 'ulang'); // Tambahkan tipe panggilan 'ulang'
            
            $response = [
                'status' => 'success',
                'nomor' => $nomor
            ];

            echo json_encode($response);
        } else {
            show_404();
        }
    }
}

