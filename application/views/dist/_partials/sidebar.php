<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="main-sidebar sidebar-style-2">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="<?php echo base_url(); ?>dist/index">App Komite Kepatuhan</a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
      <a href="<?php echo base_url(); ?>dist/index">KK</a>
    </div>
    <ul class="sidebar-menu">
      <li class="menu-header">Dashboard</li>
      <li class="dropdown <?php echo $this->uri->segment(2) == '' || $this->uri->segment(2) == 'index' || $this->uri->segment(2) == 'index_0' ? 'active' : ''; ?>">
        <a href="#" class="nav-link has-dropdown"><i class="fas fa-fire"></i><span>Dashboard</span></a>
        <ul class="dropdown-menu">
          <li class="<?php echo $this->uri->segment(2) == '' || $this->uri->segment(2) == 'index' ? 'active' : ''; ?>"><a class="nav-link" href="<?php echo base_url(); ?>">Dashboard Prognosa</a></li>
        </ul>
      </li>
      <!-- start main menu -->
      <li class="menu-header">Main Menu</li>
      <li class="dropdown <?php echo $this->uri->segment(1) == 'prognosa' ? 'active' : ''; ?>">
        <a class="nav-link has-dropdown" href="#"><i class="fas fa-hat-wizard"></i><span>Prognosa</span></a>
        <ul class="dropdown-menu">
          <li class="<?php echo $this->uri->segment(2) == 'daftarPrognosa' ? 'active' : ''; ?>"><a class="nav-link" href="<?php echo base_url(); ?>prognosa/daftarPrognosa">Daftar Prognosa</a></li>
          <?php if (($user->id_role == "1") || ($user->id_position == "4")) { ?>
            <li class="<?php echo $this->uri->segment(2) == 'inputPrognosaAr' ? 'active' : ''; ?>"><a class="nav-link" href="<?php echo base_url(); ?>prognosa/inputPrognosaAr">Input Prognosa AR</a></li>
          <?php } ?>
          <?php if (($user->id_role == "1") || ($user->id_position == "3")) { ?>
            <li class="<?php echo $this->uri->segment(2) == 'inputPrognosaKasi' ? 'active' : ''; ?>"><a class="nav-link" href="<?php echo base_url(); ?>prognosa/inputPrognosaKasi">Input Prognosa Kasi</a></li>
          <?php } ?>
        </ul>
      </li>
      <!-- end main menu -->
      <!-- start user management -->
      <?php if ($user->id_role == "1") { ?>
        <!-- hanya muncul di role admin -->
        <li class="dropdown <?php echo $this->uri->segment(1) == 'user' ? 'active' : ''; ?>">
          <a class="nav-link has-dropdown" href="#"><i class="fas fa-users"></i><span>User Management</span></a>
          <ul class="dropdown-menu">
            <li class="<?php echo $this->uri->segment(2) == 'listUser' ? 'active' : ''; ?>"><a href="<?php echo base_url(); ?>user/listUser">Users</a></li>
            <li class="<?php echo $this->uri->segment(2) == 'addUser' ? 'active' : ''; ?>"><a href="<?php echo base_url(); ?>user/addUser">Add User</a></li>
          </ul>
        </li>
      <?php } ?>
      <!-- end user management menu -->
    </ul>

  </aside>
</div>