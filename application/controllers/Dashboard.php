<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('auth_model');
    $this->load->model('prognosakasi_model');

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
    $result = array(
      'totalPrognosa' => $this->prognosakasi_model->sumAllPrognosaPerYear(date("Y")),
    );

    $data = array(
      'title' => "Dasboard Prognosa",
      'user' => $this->auth_model->current_user(),
      'data' => $this->prognosakasi_model->sumAllPrognosaPerYear(date("Y")),
    );
    $this->load->view('dashboard/dashboard', $data);
  }
}
