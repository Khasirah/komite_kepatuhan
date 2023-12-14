<?php 
defined('BASEPATH') or exit('No direct script access allowed');

class Roles_model extends CI_Model {
    private $name_table = 'roles';

    public function getAllRoles() {
        $query = $this->db->get($this->name_table);
        return $query->result();
    }
}

?>