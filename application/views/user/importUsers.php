<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('dist/_partials/header');
?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= $title ?></h1>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col">
          <div class="card">
            <div class="card-header">
              <h4>Silahkan masukkan file .csv</h4>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="form-group">
                  <input data-url="<?php echo base_url() ?>user/addUserFromImport" type="file" name="fileUploaded" class="form-control">
                </div>
              </div>
              <div class="row">
                <div class="form-group">
                  <div id="submitBtn" class="btn btn-primary">Submit</div>
                </div>
              </div>
              <div class="row" hidden>
                <div class="progress mb-3 w-100" data-height="15">
                  <div class="progress-bar bg-warning" role="progressbar" data-width="0%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">0%</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
</section>
</div>
<?php $this->load->view('dist/_partials/footer'); ?>