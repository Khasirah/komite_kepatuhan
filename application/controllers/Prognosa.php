<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Prognosa extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('auth_model');
    $this->load->model('prognosakasi_model');
    $this->load->model('sp2dk_model');
    $this->load->model('prognosaar_model');
    $this->load->model('detailsprognosaar_model');
    $this->load->library('form_validation');

    if (!$this->auth_model->current_user()) {
      redirect('login/auth_login');
    }
  }

  // daftar prognosa page
  public function listPrognosa()
  {
    $data = array(
      'title' => "Daftar Prognosa",
      'user' => $this->auth_model->current_user(),

    );
    $this->load->view('prognosa/listPrognosa', $data);
  }

  public function inputPrognosaAr()
  {
    $currentUser = $this->auth_model->current_user();
    if ($currentUser->id_position != '4' && $currentUser->id_position != '3') {
      $this->session->set_flashdata('message_roles_error', "Maaf Anda Tidak Berhak");
      redirect('dashboard/dashboardPrognosa');
    }

    $data = array(
      'title' => "Input Prognosa AR",
      'user' => $this->auth_model->current_user(),

    );
    $this->load->view('prognosa/inputPrognosaAr', $data);
  }

  public function inputPrognosaKasi()
  {
    $currentUser = $this->auth_model->current_user();
    if ($currentUser->id_position != '3') {
      $this->session->set_flashdata('message_roles_error', "Maaf Anda Tidak Berhak");
      redirect('dashboard/dashboardPrognosa');
    }

    $data = array(
      'title' => "Input Prognosa Kasi",
      'user' => $currentUser,
    );
    $this->load->view('prognosa/inputPrognosaKasi', $data);
  }

  public function prognosaKasiToDB()
  {
    $currentUser = $this->auth_model->current_user();

    $rules = $this->prognosakasi_model->rules();
    $this->form_validation->set_rules($rules);

    if ($this->form_validation->run() == FALSE) {
      $errors = array(
        'ppm' => form_error('ppm'),
        'pkm' => form_error('pkm')
      );
      $this->session->set_flashdata('validate_error', $errors);
      redirect('prognosa/inputPrognosaKasi');
    }

    $data = array(
      'month' => $this->input->post('month'),
      'year' => $this->input->post('year'),
      'ppm' => intval(str_replace(",", "", $this->input->post('ppm'))),
      'desc_ppm' => $this->input->post('descPpm'),
      'pkm' => intval(str_replace(",", "", $this->input->post('pkm'))),
      'desc_pkm' => $this->input->post('descPkm'),
      'nip9' => $currentUser->nip9,
      'id_seksi' => $currentUser->id_seksi,
    );

    try {
      $result = $this->prognosakasi_model->addPrognosa($data);
      $this->session->set_flashdata('result', $result);
      redirect('prognosa/inputPrognosaKasi');
    } catch (Exception $e) {
      $this->session->set_flashdata('result', $e);
      redirect('prognosa/inputPrognosaKasi');
    }
  }

  public function prognosaArToDB()
  {
    $user = $this->auth_model->current_user();
    $miles = round(microtime(true) * 1000);
    $month = $this->input->post('month');
    $year = $this->input->post('year');
    $idPrognosaAr = $user->nip9 . '-' . $year . $month . '-' . $miles;
    $dataPrognosaArModel = array(
      'id_prognosa_ar' => $idPrognosaAr,
      'month' => $month,
      'year' => $year,
      'ppm' => intval(str_replace(",", "", $this->input->post('ppm'))),
      'desc_ppm' => $this->input->post('descPpm'),
      'total_sp2dk_recom' => intval($this->input->post('totalNominal')),
      'nip9' => $user->nip9,
      'id_seksi' => $user->id_seksi,
    );
    $dataDetailsPrognosaAr = array(
      'id_prognosa_ar' => $idPrognosaAr,
      'id_sp2dk' => $this->input->post('idSp2dk'),
      'estimate_pay' => $this->input->post('estimatePay'),
      'estimate_date_pay' => $this->input->post('estimateDatePay'),
      'desc_sp2dk' => $this->input->post('desc')
    );

    $resultPrognosaArModel = $this->prognosaar_model->addPrognosa($dataPrognosaArModel);
    // check has details
    if ($dataDetailsPrognosaAr['estimate_pay']) {
      $resultDetailsPrognosaAr = $this->detailsprognosaar_model->addDetailsPrognosa($dataDetailsPrognosaAr);
    } else {
      $resultDetailsPrognosaAr['status'] = true;
    }

    // check result from model
    if ($resultPrognosaArModel['status'] && $resultDetailsPrognosaAr['status']) {
      $result = array(
        'status' => true,
        'desc' => 'Berhasil menambahkan prognosa dan detail prognosa',
      );
      $this->session->set_flashdata('result', $result);
      redirect('prognosa/inputPrognosaAr');
    } else {
      $result = array(
        'status' => false,
        'desc' => $resultPrognosaArModel['desc'] . ' and ' . $resultDetailsPrognosaAr['desc']
      );
      $this->session->set_flashdata('result', $result);
      redirect('prognosa/inputPrognosaAr');
    }
  }

  // get data from prognosa ar model
  public function getDataPrognosaAr()
  {
    $data = array(
      'seksi' => $this->input->post('seksi'),
      'month' => $this->input->post('month'),
      'year' => $this->input->post('year')
    );
    $result = array(
      'result' => $this->prognosaar_model->getPrognosaBySeksiMonthYear($data['seksi'], $data['month'], $data['year'])
    );
    return $this->output->set_content_type('application/json')
      ->set_status_header(200)
      ->set_output(json_encode($result));
  }

  // get data detail prognosa from details prognosa ar model
  public function getDetailsPrognosaArById()
  {
    $idPrognosaAr = $this->input->post('idPrognosaAr');
    $result = array(
      'result' => $this->detailsprognosaar_model->getDetailsPrognosaById($idPrognosaAr)
    );

    return $this->output->set_content_type('application/json')
      ->set_status_header(200)
      ->set_output(json_encode($result));
  }

  // get list prognosa by NIP
  public function getListPrognosaByNip()
  {
    $user = $this->auth_model->current_user();
    $result = array();

    // list prognosa ar
    if ($user->id_position == '4') {
      $result['result'] = $this->prognosaar_model->getListPrognosaByNip($user->nip9, $this->input->post('yearP'), $user->id_seksi);
    }
    // list prognosa kasi
    elseif ($user->id_position == '3') {
      $result['result'] = $this->prognosakasi_model->getPrognosaByYearSeksi($this->input->post('yearP'), $user->id_seksi);
    }
    // list prognosa kepala kantor
    elseif ($user->id_position == '2') {
      $result['result'] = 'kepala kantor ini';
    }


    return $this->output->set_content_type('application/json')
      ->set_status_header(200)
      ->set_output(json_encode($result));
  }
}
