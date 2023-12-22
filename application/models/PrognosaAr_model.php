<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PrognosaAr_model extends CI_Model
{
  private $name_table = "prognosa_ar";

  public function __construct()
  {
    parent::__construct();
    $this->load->model('auth_model');

    if (!$this->auth_model->current_user()) {
      redirect('login/auth_login');
    }

    if ($this->auth_model->current_user()->id_position != '4') {
      return "Maaf Anda Tidak Berhak";
    }
  }

  public function addPrognosa($data)
  {
    try {
      $this->db->insert($this->name_table, $data);
      return array(
        'status' => true,
        'desc' => 'Berhasil menambahkan prognosa'
      );
    } catch (Exception $e) {
      return array(
        'status' => false,
        'desc' => $e->getMessage()
      );
    }
  }

  public function getPrognosaBySeksiMonthYear($seksi, $month, $year)
  {
    $table_user = 'users';

    try {
      $sql = "WITH order_prognosa_ar AS (
          SELECT 
          pa.*,
          uu.name,
          RANK() OVER (
              PARTITION by pa.nip9 
              order by pa.create_date DESC
              ) order_by_create_date
          FROM komite_kepatuhan.prognosa_ar pa
          JOIN komite_kepatuhan.users uu
          ON uu.nip9 = pa.nip9 
          WHERE pa.id_seksi = ?
          AND pa.`month` = ?
          AND pa.`year` = ?
      )
      SELECT
      *
      FROM order_prognosa_ar opa
      WHERE opa.order_by_create_date = 1";
      $result = $this->db->query($sql, array($seksi, $month, $year))->result();
      return array(
        'status' => true,
        'desc' => 'Berhasil menambahkan prognosa',
        'data' => $result
      );
    } catch (Exception $e) {
      return array(
        'status' => false,
        'desc' => $e->getMessage()
      );
    }
  }

  public function getListPrognosaByNip($nip9, $yearP, $seksi)
  {
    $sql = "WITH order_prognosa_ar AS (
      SELECT 
      pa.id_prognosa_ar,
      pa.`month`,
      pa.ppm,
      pa.total_sp2dk_recom,
      RANK() OVER (
        PARTITION BY pa.`month`
        ORDER BY pa.create_date DESC
        ) order_by_create_date
      FROM komite_kepatuhan.prognosa_ar pa
      WHERE pa.nip9 = ?
      AND pa.`year` = ?
      AND pa.id_seksi = ?
    )
    SELECT *
    FROM order_prognosa_ar opa
    WHERE opa.order_by_create_date = 1
    ORDER BY 2 ASC";

    try {
      $result = $this->db->query($sql, array($nip9, $yearP, $seksi))->result();
      return array(
        'status' => true,
        'desc' => 'Berhasil mengambil prognosa',
        'data' => $result,
      );
    } catch (\Throwable $th) {
      return array(
        'status' => false,
        'desc' => 'Gaagl mengambil prognosa',
        'data' => $th,
      );
    }
  }
}
