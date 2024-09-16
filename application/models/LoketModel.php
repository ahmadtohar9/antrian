<?php
class LoketModel extends CI_Model {

    // Mendapatkan semua loket
    public function get_all_loket() {
        return $this->db->get('loket')->result();
    }

    // Mendapatkan loket berdasarkan ID
    public function get_loket_by_id($id) {
        return $this->db->get_where('loket', ['id' => $id])->row();
    }
}
