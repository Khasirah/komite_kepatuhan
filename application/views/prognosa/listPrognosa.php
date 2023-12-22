<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('dist/_partials/header');
?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?php echo $title ?></h1>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-lg-3">
          <div class="card">
            <div class="card-header">
              <h4>Periode</h4>
            </div>
            <div class="card-body">
              <div class="row align-items-center justify-content-center">

                <div class="col-8">
                  <select name="year" class="form-control month-year">
                    <?php
                    $yearNow = date("Y");
                    for ($i = $yearNow - 1; $i <= $yearNow + 10; $i++) { ?>
                      <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                    <?php } ?>
                  </select>
                </div>

                <div class="col">
                  <div data-url="<?php echo base_url() ?>prognosa/getListPrognosaByNip" class="btn btn-primary">Pilih</div>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-header">
          <h4>Daftar Prognosa</h4>
        </div>
        <div class="cord-body">
          <table id="table-list-prognosa" class="table table-hover">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Bulan</th>
                <th scope="col">PPM</th>
                <th scope="col">PKM</th>
                <th scope="col">Aksi</th>
              </tr>
            </thead>
            <tbody></tbody>
            <tfoot>
              <tr>
                <td colspan="2" class="text-center"><strong>Total</strong></td>
                <td id="totalPPM" class="nominal">0</td>
                <td id="totalPKM" class="nominal">0</td>
                <td></td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </section>
</div>
<?php $this->load->view('dist/_partials/footer'); ?>