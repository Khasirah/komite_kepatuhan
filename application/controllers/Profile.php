<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('auth_model');
		$this->load->model('users_model');

		$currentUser = $this->auth_model->current_user();

		if (!$currentUser) {
			redirect('login/auth_login');
		}
	}

	public function profileUser()
	{
		$data = array(
			'title' => "Ganti Password",
			'user' => $this->auth_model->current_user()
		);
		$this->load->view('profile/profile.php', $data);
	}

	public function changePassword()
	{
		$user = $this->auth_model->current_user();
		$newPassword = $this->input->post('newPassword');
		$retypePassword = $this->input->post('retypePassword');

		// check password valid from user
		if ($newPassword != $retypePassword) {
			$result = array(
				'status' => false,
				'desc' => 'Password tidak sama',
				'test' => $newPassword
			);
			$this->session->set_flashdata('result', $result);
			redirect('profile/profileUser');
		}

		// hash password using codeigniter
		$data = array(
			'password' => password_hash($newPassword, PASSWORD_DEFAULT) 
		);

		// send data to model and catch result send back to FE
		try {
			$result = $this->users_model->updateUser($user->nip9, $data);
			$this->session->set_flashdata('result', $result);
			redirect('profile/profileUser');
		} catch (Exception $e) {
			$result = array(
				'status' => false,
				'desc' => $e->getMessage()
			);
			$this->session->set_flashdata('result', $result);
			redirect('profile/profileUser');
		}
	}
}
