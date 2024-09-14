<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AntrianBaru_model extends CI_Model {

    // Menyimpan data antrian baru ke database
    public function insert_antrian($data) {
        $this->db->insert('antrian', $data);
    }

    // Menghasilkan nomor antrian baru berdasarkan jenis antrian (Pendaftaran, Poliklinik, dll.)
    public function generate_nomor_antrian($jenis) {
        // Menghitung jumlah antrian berdasarkan jenis untuk menghasilkan nomor
        $this->db->where('jenis_antrian', $jenis);
        $this->db->from('antrian');
        $total = $this->db->count_all_results();

        // Nomor antrian berupa huruf pertama jenis antrian dan nomor urut (misalnya P001, A002)
        return strtoupper(substr($jenis, 0, 1)) . str_pad($total + 1, 3, '0', STR_PAD_LEFT);
    }

    public function get_antrian_berikutnya($jenis, $loket) {
    $sql = "SELECT * FROM antrian 
            WHERE jenis_antrian = ? AND status = 'menunggu' 
            ORDER BY CAST(SUBSTRING(nomor_antrian, 2) AS UNSIGNED) ASC 
            LIMIT 1";
            
    $query = $this->db->query($sql, array($jenis));
    
    return $query->row();
}

    public function update_status($id_antrian, $status, $loket) 
    {
        $this->db->where('id_antrian', $id_antrian);
        return $this->db->update('antrian', ['status' => $status, 'loket' => $loket]);
    }


    // Mendapatkan antrian yang statusnya sedang 'dilayani' untuk ditampilkan di display
    public function get_antrian_display() {
        $this->db->where('status', 'dilayani');
        $this->db->order_by('loket', 'ASC');
        return $this->db->get('antrian')->result(); // Mengembalikan semua antrian yang sedang dilayani
    }

    // Mendapatkan antrian berdasarkan nomor
    public function get_antrian_by_nomor($nomor_antrian) {
        $this->db->where('nomor_antrian', $nomor_antrian);
        return $this->db->get('antrian')->row(); // Mengambil antrian berdasarkan nomor
    }
}
