<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PrognosaKasi_model extends CI_Model
{
  private $name_table = "prognosa_kasi";

  public function __construct()
  {
    parent::__construct();
    $this->load->model('auth_model');

    if (!$this->auth_model->current_user()) {
      redirect('login/auth_login');
    }

    if ($this->auth_model->current_user()->id_position != '3') {
      return "Maaf Anda Tidak Berhak";
    }
  }

  public function rules()
  {
    return [
      [
        'field' => 'ppm',
        'label' => 'Prognosa Penerimaan Masa',
        'rules' => 'required',
        'errors' => array(
          'required' => "Prognosa Penerimaan Masa Perlu diisi"
        )
      ],
      [
        'field' => 'pkm',
        'label' => 'Prognosa Penerimaan Kepatuhan Material',
        'rules' => 'required',
        'errors' => array(
          'required' => 'Prognosa Penerimaan Kepatuhan Material Perlu diisi'
        )
      ],

    ];
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

  public function getPrognosaByYearSeksi($yearP, $seksi)
  {
    $sql = "WITH order_prognosa_kasi AS (
          SELECT
          pk.*,
          RANK() OVER (
            PARTITION BY pk.`month` 
            ORDER BY pk.create_date DESC
          ) order_by_create_date
          FROM komite_kepatuhan.{$this->name_table} pk 
          WHERE pk.`year` = ?
          AND pk.id_seksi = ?
      )
      SELECT *
      FROM order_prognosa_kasi opk
      WHERE opk.order_by_create_date = 1
    ";

    try {
      $result = $this->db->query($sql, array($yearP, $seksi))->result();
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

  public function sumAllPrognosaPerYear($yearP)
  {
    $sql = "SELECT
      SUM(ppm) AS totalPPM,
      SUM(pkm) AS totalPKM
      FROM (
        SELECT
          id_seksi,
          `month`,
          `year`,
          ppm,
          pkm,
          RANK() OVER(
            PARTITION BY id_seksi,`month`,`year`
            ORDER BY create_date DESC) rank_by_seksi
        FROM 
          komite_kepatuhan.{$this->name_table}
        WHERE
          `year` = ?
      ) x
      WHERE
        x.rank_by_seksi = 1
    ";

    try {
      $result = $this->db->query($sql, array($yearP))->result();
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
