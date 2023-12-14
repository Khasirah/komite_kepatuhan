<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DetailsPrognosaAr_model extends CI_Model
{
  private $name_table = "details_prognosa_ar";

  public function addDetailsPrognosa($dataFromController)
  {
    // iterate data from controller 
    $data = array();
    for ($i = 0; $i < count($dataFromController['id_sp2dk']); $i++) {
      // transform data from controller
      $elem = array(
        'id_prognosa_ar' => $dataFromController['id_prognosa_ar'],
        'id_sp2dk' => $dataFromController['id_sp2dk'][$i],
        'estimate_pay' => intval(str_replace(",", "", $dataFromController['estimate_pay'][$i])),
        'estimate_date_pay' => $dataFromController['estimate_date_pay'][$i],
        'desc_sp2dk' => $dataFromController['desc_sp2dk'][$i]
      );
      array_push($data, $elem);
    }

    try {
      $this->db->insert_batch($this->name_table, $data);
      return array(
        'status' => true,
        'desc' => 'Berhasil menambahkan detail prognosa'
      );
    } catch (Exception $e) {
      return array(
        'status' => false,
        'desc' => $e->getMessage()
      );
    }
  }

  public function getDetailsPrognosaById($idPrognosaAr) {
    $table_sp2dk = 'sp2dk';
    try {
      $this->db->select('*');
      $this->db->from($this->name_table);
      $this->db->join($table_sp2dk, $table_sp2dk.'.id_sp2dk = '.$this->name_table.'.id_sp2dk');
      $this->db->where($this->name_table.'.id_prognosa_ar', $idPrognosaAr);
      $result = $this->db->get()->result();
      return array(
        'status' => true,
        'desc' => 'Berhasil mengambil data',
        'data' => $result
      );
    } catch (\Throwable $th) {
      return array(
        'status' => false,
        'desc' => 'Gagal mengambil data'
      );
    }
  }
}
