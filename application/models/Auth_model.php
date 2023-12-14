<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth_model extends CI_Model
{
    private $name_table = "users";
    const SESSION_KEY = "user_id";

    public function rules()
    {
        return [
            [
                'field' => 'nip9',
                'label' => 'NIP Pendek',
                'rules' => 'required'
            ],
            [
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'required'
            ]
        ];
    }

    public function login($nip, $password)
    {
        $this->db->where('nip9', $nip);
        $this->db->where('status !=', 0);
        $query = $this->db->get($this->name_table);
        $user = $query->row();

        // check if user exist
        if (!$user) {
            return FALSE;
        }

        // check if password right
        if (!password_verify($password, $user->password)) {
            return FALSE;
        }

        // create session
        $this->session->set_userdata([self::SESSION_KEY => $user->nip9]);
        return $this->session->has_userdata(self::SESSION_KEY);
    }

    public function current_user()
    {
        if (!$this->session->has_userdata(self::SESSION_KEY)) {
            return null;
        }

        $nip9 = $this->session->userdata(self::SESSION_KEY);
        $this->db->select('nip9, name, id_position, id_role, id_seksi');
        $this->db->where('nip9', $nip9);
        $this->db->where('status !=', 0);
        $query = $this->db->get($this->name_table);
        return $query->row();
    }

    public function logout()
    {
        $this->session->unset_userdata(self::SESSION_KEY);
        return !$this->session->has_userdata(self::SESSION_KEY);
    }
}
