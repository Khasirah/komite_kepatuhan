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
      <form action="<?php echo base_url() ?>profile/changePassword" method="post">
        <div class="row">
          <div class="col">
            <div class="row">
              <div class="col-lg-4">
                <div class="card">
                  <div class="card-body">
                    <div class="row align-items-center justify-content-center">
                      <div class="col">
                        <div class="form-group">
                          <label for="">New Password</label>
                          <input name="newPassword" type="password" class="form-control">
                        </div>
                        <div class="form-group">
                          <label for="">Retype Password</label>
                          <input name="retypePassword" type="password" class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-2">
                        <button type="submit" class="btn btn-primary">Ubah Password</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </section>
</div>
<?php $this->load->view('dist/_partials/footer'); ?>