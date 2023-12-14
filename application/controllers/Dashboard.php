<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('auth_model');
		if (!$this->auth_model->current_user()) {
			redirect('login/auth_login');
		}
	}

	public function index()
	{
		redirect('dashboard/dashboardPrognosa');
	}

	public function dashboardPrognosa()
	{
		$data = array(
			'title' => "Dasboard Prognosa",
			'user' => $this->auth_model->current_user()
		);
		$this->load->view('dashboard/dashboard', $data);
	}
}
