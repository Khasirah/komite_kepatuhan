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
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col">
              <ul class="nav nav-pills">
                <li class="nav-item">
                  <a class="nav-link active" href="<?= base_url(); ?>user/addUser"><i class="fas fa-plus"></i> Add New User</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link import-btn" href="<?php echo base_url() ?>user/importUsers"><i class="fas fa-file-import"></i> Import Users From CSV</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#"><i class="fas fa-file-export"></i> Export Users To CSV</a>
                </li>
              </ul>
            </div>
            <div class="col-3">
            </div>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-body">
          <table class="table table-hover">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">NIP</th>
                <th scope="col">Nama</th>
                <th scope="col">Seksi</th>
                <th scope="col">Jabatan</th>
                <th scope="col">Role</th>
                <th scope="col" class="w-15">Operation</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($listUser as $key => $user) { ?>
                <tr>
                  <th scope="row"><?php echo $key + 1 ?></th>
                  <td><?php echo $user->nip9; ?></td>
                  <td><?php echo $user->name; ?></td>
                  <td><?php echo $user->name_seksi ?></td>
                  <td>
                    <span class="badge badge-light"><?php echo $user->name_position ?></span>
                  </td>
                  <td><?php echo $user->name_role; ?></td>
                  <td>
                    <div class="">
                      <a href="<?php echo base_url() ?>user/editUser/<?php echo $user->nip9; ?>" class="btn btn-icon btn-left btn-outline-primary btn-sm"><i class="fas fa-edit"> Edit</i></a>
                      <?php if ($user->id_role != 1) { ?>
                        <a href="<?php echo base_url() ?>user/deleteUser/<?php echo $user->nip9; ?>" class="btn btn-icon btn-left btn-outline-danger btn-sm"><i class="fas fa-trash"> Delete</i></a>
                      <?php } ?>
                    </div>
                  </td>
                </tr>
              <?php } ?>

            </tbody>
          </table>
        </div>
      </div>
    </div>
</div>
</section>
</div>
<?php $this->load->view('dist/_partials/footer'); ?>