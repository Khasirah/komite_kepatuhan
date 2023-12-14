<?php 
defined('BASEPATH') or exit('No direct script access allowed');

class SP2DK_model extends CI_Model {
    private $name_table = "sp2dk";

    public function getAllSP2DKByNip($nip9) {
        try {
            $query = $this->db->where('nip9', $nip9)->get($this->name_table);
    
            return array(
                'status' => true,
                'desc' => 'Berhasil mengambil data',
                'data' => $query->result()
            );
        } catch (Exception $e) {
            return array(
                'status' => false,
                'desc' => 'Gagal mengambil data',
                'detail' => $e
            );

        }
    }
}

?>