<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PrognosaAr_model extends CI_Model
{
    private $name_table = "prognosa_ar";

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
                    PARTITION by nip9 
                    order by create_date DESC
                    ) order_by_create_date
                FROM komite_kepatuhan.prognosa_ar pa
                JOIN komite_kepatuhan.users uu
                ON uu.nip9 = pa.nip9 
                WHERE uu.id_seksi = ?
            )
            SELECT
            *
            FROM order_prognosa_ar opa
            WHERE opa.`month` = ?
            AND opa.`year` = ? 
            AND opa.order_by_create_date = 1";
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
}
