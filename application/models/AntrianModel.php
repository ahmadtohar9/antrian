<?php
class AntrianModel extends CI_Model {

    // Fungsi untuk mengambil nomor antrian baru
    public function ambil_antrian() {
        // Logika untuk mendapatkan nomor antrian baru dengan format PXXX
        $query = $this->db->select('MAX(SUBSTRING(nomor, 2)) as nomor')->get('antrian_pendaftaran');
        $result = $query->row();

        if ($result && $result->nomor) {
            $nomor_antrian_baru = 'P' . str_pad($result->nomor + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $nomor_antrian_baru = 'P001'; // Jika belum ada antrian, mulai dari P001
        }

        // Simpan nomor antrian baru dengan status "menunggu"
        $data = [
            'nomor' => $nomor_antrian_baru,
            'status' => 'menunggu',
            'loket' => 'Pendaftaran'
        ];
        $this->db->insert('antrian_pendaftaran', $data);

        return $nomor_antrian_baru;
    }

    // Fungsi untuk memanggil antrian berdasarkan loket
    public function panggil_antrian($loket) {
        // Ambil antrian dengan status "menunggu" dan berdasarkan loket yang dipilih
        $this->db->where('status', 'menunggu');
        $this->db->where('loket', 'pendaftaran'); // Pastikan 'Pendaftaran' sesuai dengan value di dropdown
        $this->db->order_by('nomor', 'ASC');
        $query = $this->db->get('antrian_pendaftaran', 1); // Ambil 1 antrian

        if ($query->num_rows() > 0) {
            $row = $query->row();

            // Update status antrian menjadi 'dipanggil'
            $this->db->where('id', $row->id);
            $this->db->update('antrian_pendaftaran', ['status' => 'dipanggil']);

            return $row->nomor; // Kembalikan nomor antrian
        }

        return null; // Kembalikan null jika tidak ada antrian yang tersedia
    }

    // Fungsi untuk menyimpan log nomor terakhir yang dipanggil (panggilan ulang atau normal)
    public function simpan_nomor_terpanggil($nomor, $loket, $tipe_panggilan = 'normal') {
        $data = [
            'nomor' => $nomor,
            'loket' => $loket,
            'waktu_panggil' => date('Y-m-d H:i:s'),
            'tipe_panggilan' => $tipe_panggilan // Tipe panggilan normal atau ulang
        ];
        $this->db->insert('log_panggilan_antrian', $data);
    }

    // Fungsi untuk mendapatkan nomor terakhir yang dipanggil (normal atau ulang)
    public function get_nomor_terpanggil_terakhir() {
        $this->db->select('nomor, loket');
        $this->db->from('log_panggilan_antrian');
        $this->db->order_by('waktu_panggil', 'DESC');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row();  // Kembalikan nomor dan loket
        }

        return null;  // Jika belum ada nomor yang dipanggil
    }

    // Fungsi untuk menyimpan log panggilan ulang
    public function simpan_log_panggilan_ulang($nomor, $loket) {
        $data = [
            'nomor' => $nomor,
            'loket' => $loket,
            'waktu_panggil' => date('Y-m-d H:i:s'),  // Waktu saat panggilan ulang dilakukan
            'tipe_panggilan' => 'ulang'
        ];
        $this->db->insert('log_panggilan_antrian', $data);
    }

    // Fungsi untuk mendapatkan nomor dan loket yang dipanggil ulang
    public function get_nomor_panggilan_ulang() {
        $this->db->select('nomor, loket');
        $this->db->from('log_panggilan_antrian');
        $this->db->where('tipe_panggilan', 'ulang');
        $this->db->order_by('waktu_panggil', 'DESC');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row();  // Kembalikan nomor dan loket yang akan dipanggil ulang
        }

        return null;  // Jika tidak ada panggilan ulang
    }

    // Fungsi untuk mendapatkan panggilan ulang dari controller
    public function panggil_ulang_antrian() {
    if ($this->input->is_ajax_request()) {
        $nomor = $this->input->post('nomor');
        $loket = $this->input->post('loket');

        // Cari antrian yang dipanggil sebelumnya berdasarkan nomor antrian
        $antrian = $this->AntrianModel->get_antrian_by_nomor($nomor);
        
        if ($antrian) {
            // Simpan log panggilan ulang di database
            $this->AntrianModel->simpan_nomor_terpanggil($nomor, $loket, 'ulang');

            // Kirim respons ke front-end
            $response = [
                'status' => 'success',
                'nomor' => $antrian->nomor_antrian
            ];
            echo json_encode($response);
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Nomor antrian tidak ditemukan.'
            ];
            echo json_encode($response);
        }
    } else {
        show_404();
    }
}

}