<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SP2DK_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('auth_model');
        $this->load->model('sp2dk_model');

        if (!$this->auth_model->current_user()) {
            redirect('login/auth_login');
        }
    }

    public function getAllSP2DKByNip()
    {
        $currentUser = $this->auth_model->current_user();
        $nip9 = $this->input->post('nip9');

        // check user with nip9 request must same
        if ($currentUser == $nip9) {
            echo "user sama";
        }
        
        $result = array(
            'sp2dk' => $this->sp2dk_model->getAllSP2DKByNip($nip9)
        );

        header('Content-Type: application/json');
        return $this->output->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($result));
    }
}
