<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AntrianBaruController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('AntrianBaru_model'); // Sesuaikan dengan nama model

        // Tambahkan header CORS
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');

        // Jika request method adalah OPTIONS, hentikan eksekusi
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            die();
        }
    }

    // Menampilkan halaman untuk ambil antrian
    public function ambil_antrian_view() {
        $this->load->view('ambil_antrian_baru_view');
    }

    // Menampilkan halaman untuk panggil antrian
    public function panggil_antrian_view() {
        $this->load->view('panggil_antrian_baru_view');
    }

    // Menampilkan halaman display antrian
    public function display_antrian() {
        $this->load->view('display_antrian_baru_view');
    }

    // Fungsi untuk menambah nomor antrian berdasarkan jenis
    public function tambah_antrian($jenis) {
        $nomor_antrian = $this->AntrianBaru_model->generate_nomor_antrian($jenis);
        $data = array(
            'nomor_antrian' => $nomor_antrian,
            'jenis_antrian' => $jenis,
            'status' => 'menunggu',
            'loket' => null
        );
        $this->AntrianBaru_model->insert_antrian($data);
        echo json_encode($data);
    }

    // Fungsi untuk memanggil antrian berdasarkan jenis dan loket
    public function panggil_antrian($jenis, $loket) {
        $this->db->trans_start();

        $antrian = $this->AntrianBaru_model->get_antrian_berikutnya($jenis, $loket);
        if ($antrian) {
            // Update status antrian menjadi 'dilayani'
            $this->AntrianBaru_model->update_status($antrian->id_antrian, 'dilayani', $loket);
            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                echo json_encode(null);
            } else {
                echo json_encode($antrian);
            }
        } else {
            $this->db->trans_complete();
            echo json_encode(null);
        }
    }

    // Fungsi untuk memanggil ulang antrian yang sama
    public function panggil_ulang_antrian($nomor_antrian) {
        $antrian = $this->AntrianBaru_model->get_antrian_by_nomor($nomor_antrian);
        if ($antrian) {
            echo json_encode($antrian);
        } else {
            echo json_encode(null);
        }
    }

    // Fungsi untuk mendapatkan data antrian yang sedang dipanggil untuk display
    public function get_antrian_display() {
        $antrian = $this->AntrianBaru_model->get_antrian_display();
        echo json_encode($antrian);
    }
}
