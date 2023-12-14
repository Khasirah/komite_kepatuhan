<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users_model extends CI_Model
{
    private $name_table = "users";

    public function rules()
    {
        return [
            [
                'field' => 'nip9',
                'label' => 'NIP Pendek',
                'rules' => 'required|exact_length[9]'
            ],
            [
                'field' => 'name',
                'label' => 'Nama Pegawai',
                'rules' => 'required'
            ],
            [
                'field' => 'position',
                'label' => 'Posisi / Jabatan',
                'rules' => 'required'
            ],
            [
                'field' => 'role',
                'label' => 'Role',
                'rules' => 'required'
            ],
            [
                'field' => 'seksi',
                'label' => 'Seksi',
                'rules' => 'required'
            ],

        ];
    }

    public function addUser($data)
    {
        // search for same nip
        $duplicate = $this->db->select('nip9, name')->where('nip9', $data['nip9'])->count_all_results($this->name_table);
        if ($duplicate > 0) {
            $userDuplicate = $this->db->select('name')->where('nip9', $data['nip9'])->get($this->name_table)->row();
            throw new Exception("NIP sudah terdaftar " . $userDuplicate->name);
        }
        $this->db->insert($this->name_table, $data);
        $query = $this->db->select('nip9, name')->where('nip9', $data['nip9'])->get($this->name_table);
        $result = $query->row();
        return array(
            'status' => true,
            'desc' => "Berhasil menambah <strong>" . $result->nip9 . "</strong> dengan nama <strong>" . $result->name . "</strong>"
        );
    }

    public function updateUser($nip9, $data)
    {
        $this->db->where('nip9', $nip9);
        $this->db->update($this->name_table, $data);
        $user = $this->db->select('nip9, name')->where('nip9', $nip9)->get($this->name_table)->row();
        return array(
            'status' => true,
            'desc' => "Berhasil update data <strong>" . $user->nip9 . "</strong> dengan nama <strong>" . $user->name . "</strong>"
        );
    }

    public function deleteUser($nip9, $deleteBy)
    {
        $tb_bk_user = 'bk_users';

        // insert deleted user to table bk_users
        $this->db->where('nip9', $nip9);
        $deletedUser = $this->db->get($this->name_table)->row();
        $data = array(
            'nip9' => $deletedUser->nip9,
            'name' => $deletedUser->name,
            'id_position' => $deletedUser->id_position,
            'id_role' => $deletedUser->id_role,
            'create_date' => $deletedUser->create_date,
            'update_date' => $deletedUser->update_date,
            'id_seksi' => $deletedUser->id_seksi,
            'delete_by' => $deleteBy
        );
        $this->db->insert($tb_bk_user, $data);

        // delete user from table users
        $this->db->where('nip9', $nip9);
        $this->db->delete($this->name_table);
        return array(
            'status' => true,
            'desc' => "Berhasil update data <strong>" . $deletedUser->nip9 . "</strong> dengan nama <strong>" . $deletedUser->name . "</strong>"
        );
    }

    public function getAllUsers()
    {
        $table_roles = 'roles';
        $table_position = 'positions';
        $table_seksi = 'seksi';
        $this->db->select('nip9, name, name_position, name_role, name_seksi, ' . $this->name_table . '.id_role');
        $this->db->from($this->name_table);
        $this->db->join($table_roles, $this->name_table . '.id_role = ' . $table_roles . '.id_role');
        $this->db->join($table_position, $this->name_table . '.id_position = ' . $table_position . '.id_position');
        $this->db->join($table_seksi, $this->name_table . '.id_seksi = ' . $table_seksi . '.id_seksi');
        $this->db->where($this->name_table . '.status !=', 0);
        $this->db->order_by($this->name_table . '.id_position asc, ' . $this->name_table . '.name asc');
        $query = $this->db->get();
        return $query->result();
    }

    public function getUser($nip9)
    {
        $this->db->select('nip9, name, id_position, id_role, id_seksi');
        $this->db->from($this->name_table);
        $this->db->where('nip9', $nip9);
        $this->db->where('status !=', 0);
        $query = $this->db->get();
        return $query->row();
    }
}
