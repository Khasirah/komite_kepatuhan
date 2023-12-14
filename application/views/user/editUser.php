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
      <div class="row">
        <div class="col-lg-2 col-sm-12"></div>
        <div class="col-lg-8 col-sm-12">
          <form action="<?php echo base_url(); ?>user/updateUserToDB" method="post">
            <div class="card">
              <div class="col card-body">
                <div class="form-group">
                  <label>NIP Pendek</label>
                  <input maxlength="9" name="nip9" type="text" class="form-control" value="<?php echo $selectedUser->nip9; ?>" readonly>
                </div>
                <div class="form-group">
                  <label>Nama</label>
                  <input name="name" type="text" class="form-control" value="<?= $selectedUser->name; ?>">
                </div>
                <div class="form-group">
                  <label>Posisi / Jabatan</label>
                  <select name="position" class="form-control">
                    <?php foreach ($positions as $position) { ?>
                      <option value="<?php echo $position->id_position; ?>" <?php echo ($position->id_position == $selectedUser->id_position) ? "selected='selected'":"" ?>><?php echo $position->name_position; ?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="form-group">
                  <label>Role</label>
                  <select name="role" class="form-control">
                    <?php foreach ($roles as $role) { ?>
                      <option class="text-capitalize" value="<?php echo $role->id_role; ?>" <?php echo ($role->id_role == $selectedUser->id_role) ? "selected='selected'":"" ?>><?php echo $role->name_role; ?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="form-group">
                  <label>Seksi</label>
                  <select name="seksi" class="form-control">
                    <?php foreach ($seksi as $item) { ?>
                      <option class="text-capitalize" value="<?php echo $item->id_seksi; ?>" <?php echo ($item->id_seksi == $selectedUser->id_seksi) ? "selected='selected'":"" ?>><?php echo $item->name_seksi; ?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="card-footer text-right">
                  <button class="btn btn-primary mr-1" type="submit" id="addUserSubmitButton">Submit</button>
                  <button class="btn btn-secondary" type="reset">Reset</button>
                </div>
              </div>
            </div>
          </form>
          <div class="col-lg-2 col-sm-12"></div>
        </div>

      </div>
  </section>
</div>
<?php $this->load->view('dist/_partials/footer'); ?>