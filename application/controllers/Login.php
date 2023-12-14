<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{

    public function auth_login()
    {

        $data = array(
            'title' => "Login"
        );

        $this->load->view('login/auth-login', $data);
    }

    public function login()
    {
        $this->load->model('auth_model');
        $this->load->library('form_validation');

        $rules = $this->auth_model->rules();
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run() == FALSE) {
            redirect('login/auth_login');
        }
        
        $nip = $this->input->post('nip9');
        $password = $this->input->post('password');
        
        if ($this->auth_model->login($nip, $password)) {
            redirect(site_url());
        } else {
            $this->session->set_flashdata('message_login_error', 'Login Gagal, pastikan username dan password benar!');
            redirect('login/auth_login');
        }
    }

    public function logout()
    {
        $this->load->model('auth_model');
        $this->auth_model->logout();
        redirect('login/auth_login');
    }
}
