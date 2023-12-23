<?php 
defined('BASEPATH') or exit('No direct script access allowed');

class Seksi_model extends CI_Model {
    private $name_table = 'seksi';

    public function getAllSeksi() {
        $query = $this->db->where('id_seksi !=', 0)->get($this->name_table);
        return $query->result();
    }
}

?>