<?php 
defined('BASEPATH') or exit('No direct script access allowed');

class Positions_model extends CI_Model {
    private $name_table = 'positions';

    public function getAllPositions() {
        $query = $this->db->get($this->name_table);
        return $query->result();
    }
}

?>