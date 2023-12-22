<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('dist/_partials/header');
?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= $title; ?></h1>
    </div>

    <div class="section-body">
      <?php
      $totalPPM = (int) (($data["data"][0]->totalPPM) ? $data["data"][0]->totalPPM : 0);
      $totalPKM = (int) (($data["data"][0]->totalPKM) ? $data["data"][0]->totalPKM : 0);
      ?>
      <?php if ($this->session->flashdata('message_roles_error')) { ?>
        <div class="alert alert-danger alert-dismissible show fade">
          <div class="alert-body">
            <button class="close" data-dismiss="alert">
              <span>&times;</span>
            </button>
            <?php echo $this->session->flashdata('message_roles_error'); ?>
          </div>
        </div>
      <?php } ?>
      <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
          <div class="card card-statistic-1">
            <div class="card-icon bg-primary">
              <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4>Total Prognosa PPM <?php echo date("Y") ?></h4>
              </div>
              <div class="card-body">
                <?php echo number_format($totalPPM) ?>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
          <div class="card card-statistic-1">
            <div class="card-icon bg-danger">
              <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4>Total Prognosa PKM <?php echo date("Y") ?></h4>
              </div>
              <div class="card-body">
                <?php echo number_format($totalPKM) ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<?php $this->load->view('dist/_partials/footer'); ?>