<?php
class AntrianModel extends CI_Model {

    // Fungsi untuk mengambil nomor antrian baru
    public function ambil_antrian() {
        $query = $this->db->select('MAX(SUBSTRING(nomor, 2)) as nomor')->get('antrian_pendaftaran');
        $result = $query->row();

        if ($result && $result->nomor) {
            $nomor_antrian_baru = 'P' . str_pad($result->nomor + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $nomor_antrian_baru = 'P001';
        }

        $data = [
            'nomor' => $nomor_antrian_baru,
            'status' => 'menunggu',
            'loket' => 'pendaftaran'
        ];
        $this->db->insert('antrian_pendaftaran', $data);

        return $nomor_antrian_baru;
    }

    // Fungsi untuk memanggil antrian berdasarkan loket
    public function panggil_antrian($loket) {
        $this->db->where('status', 'menunggu');
        $this->db->where('loket', 'pendaftaran');
        $this->db->order_by('nomor', 'ASC');
        $query = $this->db->get('antrian_pendaftaran', 1);

        if ($query->num_rows() > 0) {
            $row = $query->row();
            $this->db->where('id', $row->id);
            $this->db->update('antrian_pendaftaran', ['status' => 'dipanggil']);
            return $row->nomor;
        }

        return null;
    }

    // Tambahkan log di fungsi simpan_nomor_terpanggil
public function simpan_nomor_terpanggil($nomor, $loket, $tipe_panggilan = 'normal') {
    $data = [
        'nomor' => $nomor,
        'loket' => $loket,
        'waktu_panggil' => date('Y-m-d H:i:s'),
        'tipe_panggilan' => $tipe_panggilan // Tipe panggilan normal atau ulang
    ];

    // Log untuk debugging
    log_message('debug', 'Menyimpan nomor: ' . $nomor . ' Loket: ' . $loket . ' Tipe Panggilan: ' . $tipe_panggilan);

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
}
