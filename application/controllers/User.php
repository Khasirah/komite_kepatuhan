<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('auth_model');
    $this->load->model('roles_model');
    $this->load->model('positions_model');
    $this->load->model('users_model');
    $this->load->model('seksi_model');

    $currentUser = $this->auth_model->current_user();

    if ($currentUser->id_role != 1) {
      redirect('dashboard/dashboardPrognosa');
    }
    if (!$currentUser) {
      redirect('login/auth_login');
    }
  }

  public function addUserFromImport()
  {
    $data = array(
      'nip9' => $this->input->post('nip9'),
      'name' => $this->input->post('name'),
      'password' => password_hash($this->input->post('nip9'), PASSWORD_DEFAULT),
      'id_position' => $this->input->post('position'),
      'id_role' => $this->input->post('role'),
      'id_seksi' => $this->input->post('seksi')
    );

    try {
      //code...
      $result = $this->users_model->addUser($data);
      return $this->output->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode($result));
    } catch (\Throwable $th) {
      //throw $th;
      $result = array(
        'status' => false,
        'desc' => $th->getMessage(),
      );
      return $this->output->set_content_type('application/json')
        ->set_status_header(400)
        ->set_output(json_encode($result));
    }
  }

  public function importUsers()
  {
    $data = array(
      'title' => "Import Users",
      'user' => $this->auth_model->current_user(),
    );
    $this->load->view('user/importUsers.php', $data);
  }

  public function listUser()
  {
    $data = array(
      'title' => "Users",
      'user' => $this->auth_model->current_user(),
      'listUser' => $this->users_model->getAllUsers()
    );
    $this->load->view('user/user.php', $data);
  }

  public function addUser()
  {
    $data = array(
      'title' => "Add User",
      'roles' => $this->roles_model->getAllRoles(),
      'positions' => $this->positions_model->getAllPositions(),
      'seksi' => $this->seksi_model->getAllseksi(),
      'user' => $this->auth_model->current_user()
    );
    $this->load->view('user/addUser.php', $data);
  }

  public function editUser()
  {
    $currentUser = $this->auth_model->current_user();

    if ($currentUser->id_role == 1) {
      $nip9 = $this->uri->segment(3);
      $data = array(
        'title' => "Edit User",
        'user' => $this->auth_model->current_user(),
        'roles' => $this->roles_model->getAllRoles(),
        'positions' => $this->positions_model->getAllPositions(),
        'seksi' => $this->seksi_model->getAllSeksi(),
        'selectedUser' => $this->users_model->getUser($nip9)
      );
      $this->load->view('user/editUser.php', $data);
    } else {
      $data = array(
        'title' => "Edit User",
        'user' => $this->auth_model->current_user(),
        'roles' => $this->roles_model->getAllRoles(),
        'positions' => $this->positions_model->getAllPositions(),
      );
      $this->load->view('user/editUser.php', $data);
    }
  }

  public function deleteUser()
  {
    try {
      $user = $this->auth_model->current_user();
      $nip9 = $this->uri->segment(3);
      $result = $this->users_model->deleteUser($nip9, $user->nip9);
      $this->session->set_flashdata('result', $result);
      redirect('user/listUser');
    } catch (Exception $e) {
      $result = array(
        'status' => false,
        'desc' => $e->getMessage()
      );
      redirect('user/listUser');
    }
  }

  public function addUserToDB()
  {
    $this->load->library('form_validation');

    $rules = $this->users_model->rules();
    $this->form_validation->set_rules($rules);

    if ($this->form_validation->run() == FALSE) {
      redirect('user/addUser');
    }

    $data = array(
      'nip9' => $this->input->post('nip9'),
      'name' => $this->input->post('name'),
      'password' => '',
      'id_position' => $this->input->post('position'),
      'id_role' => $this->input->post('role'),
      'id_seksi' => $this->input->post('seksi')
    );

    $password = password_hash($data['nip9'], PASSWORD_DEFAULT);
    $data['password'] = $password;

    try {
      $result = $this->users_model->addUser($data);
      $this->session->set_flashdata('result', $result);
      redirect('user/addUser');
    } catch (Exception $e) {
      $result = array(
        'status' => false,
        'desc' => $e->getMessage()
      );
      $this->session->set_flashdata('result', $result);
      redirect('user/addUser');
    }
  }

  public function updateUserToDB()
  {
    $this->load->library('form_validation');

    $rules = $this->users_model->rules();
    $this->form_validation->set_rules($rules);

    if ($this->form_validation->run() == FALSE) {
      redirect('user/addUser');
    }

    $data = array(
      'name' => $this->input->post('name'),
      'id_position' => $this->input->post('position'),
      'id_role' => $this->input->post('role'),
      'id_seksi' => $this->input->post('seksi')
    );

    $nip9 = $this->input->post('nip9');

    try {
      $result = $this->users_model->updateUser($nip9, $data);
      $this->session->set_flashdata('result', $result);
      redirect('user/editUser/' . $nip9);
    } catch (Exception $e) {
      $result = array(
        'status' => false,
        'desc' => $e->getMessage()
      );
      $this->session->set_flashdata('result', $result);
      redirect('user/editUser/' . $nip9);
    }
  }
}
