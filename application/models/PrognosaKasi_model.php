<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PrognosaKasi_model extends CI_Model
{
    private $name_table = "prognosa_kasi";

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
    
}
