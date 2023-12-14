<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('dist/_partials/header');
?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?php echo $title; ?></h1>
    </div>

    <div class="section-body">
      <input id="linkGetDataPrognosa" type="text" hidden value="<?php echo base_url() ?>prognosa/getDataPrognosaAr">
      <?php if ($this->session->flashdata('result')) {
        $result = $this->session->flashdata('result');
      ?>
        <script>
          iziToast.<?php echo ($result['status']) ? "success" : "error" ?>({
            title: '<?php echo ($result['status']) ? "Berhasil" : "Gagal" ?>',
            message: '<?php echo $result['desc'] ?>',
            position: 'topRight'
          });
        </script>
      <?php } ?>
      <form action="<?php echo base_url(); ?>prognosa/prognosaKasiToDB" method="post">
        <input type="text" name="seksi" hidden value="<?php echo $user->id_seksi ?>">
        <div class="row">
          <div class="col-lg-6">
            <div class="card">
              <div class="card-header">
                <h4>Periode</h4>
              </div>
              <div class="card-body">
                <div class="row align-items-center justify-content-center">
                  <div class="col-5">
                    <div class="form-group">
                      <select name="month" class="form-control">
                        <?php for ($m = 1; $m <= 12; ++$m) { ?>
                          <option value="<?php echo $m; ?>"><?php echo date('F', mktime(0, 0, 0, $m, 1)) ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-5">
                    <div class="form-group">
                      <select name="year" class="form-control">
                        <?php
                        $yearNow = date("Y");
                        for ($i = $yearNow; $i <= $yearNow + 10; $i++) { ?>
                          <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-2">
                    <div class="form-group">
                      <div id="chooseBtn" class="btn btn-primary">Pilih</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col"></div>
        </div>
        <div class="row">
          <div class="col">
            <div class="card">
              <div class="card-header">
                <h4>Prognosa Penerimaan Masa</h4>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        Rp
                      </div>
                    </div>
                    <input name="ppm" type="text" class="form-control currency">
                  </div>
                  <?php if ($this->session->flashdata('validate_error')) {
                    $errors = $this->session->flashdata('validate_error');
                  ?>
                    <div>
                      <div class="text-danger"><em><?php echo $errors['ppm'] ?></em></div>
                    </div>
                  <?php } ?>
                </div>
                <div class="form-group">
                  <label for="">Keterangan</label>
                  <textarea name="descPpm" class="form-control">-</textarea>
                </div>
                <strong>Total PPM Ar <span id="totalPPMAr" class="nominal text-primary"></span></strong>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="card">
              <div class="card-header">
                <h4>Prognosa Penerimaan Kepatuhan Material</h4>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        Rp
                      </div>
                    </div>
                    <input name="pkm" type="text" class="form-control currency">
                  </div>
                  <?php if ($this->session->flashdata('validate_error')) {
                    $errors = $this->session->flashdata('validate_error');
                  ?>
                    <div>
                      <div class="text-danger"><em><?php echo $errors['pkm'] ?></em></div>
                    </div>
                  <?php } ?>
                </div>
                <div class="form-group">
                  <label for="">Keterangan</label>
                  <textarea name="descPkm" class="form-control">-</textarea>
                </div>
                <strong>Total PKM Ar <span id="totalPKMAr" class="nominal text-primary"></span></strong>
              </div>
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-body">
            <button class="btn btn-primary">Submit Prognosa</button>
          </div>
        </div>
      </form>
      <div class="card">
        <div class="card-header">
          <h4>Daftar Referensi</h4>
        </div>
        <div class="card-body">
          <input id="linkGetDetailsPrognosa" type="text" hidden value="<?php echo base_url() ?>prognosa/getDetailsPrognosaArById">
          <table id="table-referenece" class="table table-hover">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Nama AR</th>
                <th scope="col">Periode</th>
                <th scope="col">Total PPM</th>
                <th scope="col">Total PKM</th>
                <th scope="col">Aksi</th>
              </tr>
            </thead>
            <tbody></tbody>
            <tfoot>
              <tr class="color bg-secondary">
                <td colspan="3" class="text-center"><strong>Total</strong></td>
                <td><strong id="totalPPM" class="nominal">0</strong></td>
                <td><strong id="totalPKM" class="nominal">0</strong></td>
                <td></td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
    <!-- Modal -->
    <dialog data-modal class="position-relative">
      <button data-close-modal class="btn btn-icon btn-danger position-absolute"><i class="fas fa-times"></i></button>
      <div></div>
    </dialog>
  </section>
</div>
<?php $this->load->view('dist/_partials/footer'); ?>