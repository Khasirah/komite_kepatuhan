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
        if ($result['status']) { ?>
          <script>
            deleteAllDataFromLocal();
            iziToast.<?php echo ($result['status']) ? "success" : "error" ?>({
              title: '<?php echo ($result['status']) ? "Berhasil" : "Gagal" ?>',
              message: '<?php echo $result['desc'] ?>',
              position: 'topRight'
            });
          </script>
        <?php } ?>
      <?php } ?>

      <script>
        getAllSP2DKByNip('<?php echo $user->nip9 ?>', '<?php echo base_url() ?>SP2DK_controller/getAllSP2DKByNip')
          .then((result) => {
            $(document).ready(function() {
              addDataToTable(result);
              setCheckedBox();
              sendDataToSp2dkRecom();
              sumDatainTableSp2dkRecom();
              setValueToSp2dkRecomField();
              formatNominal();

              const numberPageBtn = document.querySelectorAll(".nPBtn");
              const totalPage = Math.floor(result.sp2dk.data.length / 10) + 1;
              const prevBtn = document.getElementById('prevBtn');
              const nextBtn = document.getElementById('nextBtn');
              const chooseBtn = document.querySelector('.col-2 .form-group .btn.btn-primary');
              let month = document.querySelector('[name="month"]');
              let year = document.querySelector('[name="year"]');
              month.value = Number(new Date().getMonth()) + 1;
              year.value = Number(new Date().getFullYear());
              let search = document.querySelector('.input-search');

              search.addEventListener('change', function() {
                let searchValue = search.value;
                let sp2dk = result.sp2dk.data;
                let sp2dkFilteredByNo = (searchValue.length != 0) ? sp2dk.filter(item => item.no_sp2dk == searchValue) : sp2dk;
                let resultFiltered = {
                  sp2dk: {
                    status: result.sp2dk.status,
                    desc: result.sp2dk.desc,
                    data: sp2dkFilteredByNo
                  }
                }
                addDataToTable(resultFiltered);
                main(resultFiltered);
                setCheckedBox();
                formatNominal();
              })

              chooseBtn.addEventListener('click', function() {
                sendDataToSp2dkRecom(Number(month.value) - 1, Number(year.value));
              })

              prevBtn.addEventListener('click', function() {
                let curPage = document.querySelector('.pagination .active div');
                curPage = Number(curPage.innerHTML) - 1;
                if (curPage >= 1) {
                  document.querySelector('.pagination .active')?.classList.remove('active')
                  addDataToTable(result, curPage);
                  main(result);
                  setCheckedBox();
                  changePagiNum(curPage, totalPage, numberPageBtn);
                  formatNominal();
                }
              })

              nextBtn.addEventListener('click', function() {
                let curPage = document.querySelector('.pagination .active div');
                curPage = Number(curPage.innerHTML) + 1;
                
                if (curPage <= totalPage) {
                  document.querySelector('.pagination .active')?.classList.remove('active')
                  addDataToTable(result, curPage);
                  main(result);
                  setCheckedBox();
                  changePagiNum(curPage, totalPage, numberPageBtn);
                  formatNominal();
                }
              })

              numberPageBtn.forEach(element => {
                element.addEventListener("click", function() {
                  document.querySelector('.pagination .active')?.classList.remove('active')
                  let curPage = Number(element.innerHTML)
                  addDataToTable(result, curPage);
                  main(result);
                  setCheckedBox();
                  changePagiNum(curPage, totalPage, numberPageBtn);
                  formatNominal();
                })
              });
              main(result);
            })
          })
      </script>
      <form action="<?php echo base_url() ?>prognosa/prognosaArToDB" method="post">
        <div class="row">
          <div class="col">

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
                          <select name="month" class="form-control month-year">
                            <?php for ($m = 1; $m <= 12; ++$m) { ?>
                              <option value="<?php echo $m; ?>"><?php echo date('F', mktime(0, 0, 0, $m, 1)) ?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>

                      <div class="col-5">
                        <div class="form-group">
                          <select name="year" class="form-control month-year">
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
                          <div class="btn btn-primary">Pilih</div>
                        </div>
                      </div>

                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">

                <div class="card">
                  <div class="card-header">
                    <h4>Prognosa Penerimaan Masa</h4>
                  </div>
                  <div class="card-body">
                    <div class="">

                      <div class="form-group">
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <div class="input-group-text">
                              Rp
                            </div>
                          </div>
                          <input name="ppm" type="text" class="form-control currency">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="">Keterangan</label>
                        <textarea name="descPpm" class="form-control">-</textarea>
                      </div>

                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="card">
              <div class="card-header">
                <h4>Daftar SP2DK</h4>
                <div class="card-header-form">
                  <div class="input-group">
                    <input type="text" class="form-control input-search" placeholder="Search">
                    <div class="input-group-btn">
                      <div class="btn btn-primary"><i class="fas fa-search"></i></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table id="table-sp2dk" class="table table-hover">
                    <thead>
                      <tr>
                        <th class="text-center">
                          <div class="custom-checkbox custom-control">
                            <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad" class="custom-control-input" id="checkbox-all">
                            <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                          </div>
                        </th>
                        <th>No SP2DK</th>
                        <th>Nilai</th>
                      </tr>
                    </thead>
                    <tbody></tbody>
                  </table>
                </div>
              </div>
              <div class="card-footer text-right">
                <nav class="d-inline-block">
                  <ul class="pagination mb-0">
                    <li id="prevBtn" class="page-item disabled">
                      <div class="page-link" href="#" tabindex="-1"><i class="fas fa-chevron-left"></i></div>
                    </li>
                    <li class="page-item active">
                      <div class="page-link nPBtn">1</div>
                    </li>
                    <li class="page-item">
                      <div class="page-link nPBtn">2</div>
                    </li>
                    <li class="page-item">
                      <div class="page-link nPBtn">3</div>
                    </li>
                    <li id="nextBtn" class="page-item">
                      <div class="page-link"><i class="fas fa-chevron-right"></i></div>
                    </li>
                  </ul>
                </nav>
              </div>
            </div>

            <div class="card">
              <div class="card-header">
                <h4>Daftar SP2DK Usulan</h4>
              </div>
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table id="table-recom" class="table table-hover">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>No SP2DK</th>
                        <th>Nilai</th>
                        <th>Estimasi Terbayar</th>
                        <th class="w-15">Tanggal</th>
                        <th>Keterangan</th>
                        <th class="w-5">Aksi</th>
                      </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                      <tr>
                        <td colspan="3" class="text-center"><strong>Total</strong></td>
                        <td>
                          <input name="totalNominal" hidden type="number" value="0">
                          <div type="text" class="form-control text-right totalNominal nominal"></div>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>
            </div>

            <div class="card">
              <div class="card-body">
                <button class="btn btn-primary">Submit Prognosa</button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </section>
</div>
<?php $this->load->view('dist/_partials/footer'); ?>