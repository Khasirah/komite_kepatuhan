// when document loaded
$(document).ready(function () {
  const month = document.querySelector('[name="month"]');
  const year = document.querySelector('[name="year"]');
  month.value = new Date().getMonth() + 1;
  year.value = new Date().getFullYear();
})

// when choose button clicked
const chooseBtn = document.getElementById('chooseBtn');

chooseBtn.addEventListener('click', function () {
  const month = document.querySelector('[name="month"]');
  const year = document.querySelector('[name="year"]');
  const seksi = document.querySelector('[name="seksi"]');
  const url = document.querySelector('#linkGetDataPrognosa');

  getDataPrognosaArBySeksi(url.value, seksi.value, month.value, year.value)
    .then((result) => {
      writeDataToTableReference(result);
      onLoadModalBtn();
    });

})

// other function
async function getDetailsPrognosaById(idPrognosaAr) {
  const url = document.querySelector('#linkGetDetailsPrognosa');
  let formData = new FormData();
  formData.append('idPrognosaAr', idPrognosaAr)

  let result = await fetch(url.value, {
    method: "POST",
    body: formData
  })

  let data = await result.json();
  data = JSON.stringify(data);
  data = JSON.parse(data);

  return data;
}

function tableOnModal(resultFromGetDetails) {
  let sp2dkRecom = resultFromGetDetails.result.data;
  let tableHtml = `
          <table id="table-referenece" class="table table-hover">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">No SP2DK</th>
                <th scope="col">Nominal</th>
                <th scope="col">Estimasi Bayar</th>
                <th scope="col">Estimasi Tanggal Bayar</th>
                <th scope="col">Keterangan</th>
              </tr>
            </thead>
            <tbody>
  `;
  let rows = '';
  sp2dkRecom.forEach((element,key) => {
    let row = `
        <tr>
          <th scope="row">${key + 1}</th>
          <td>${element.no_sp2dk}</td>
          <td class="nominal">${element.nominal}</td>
          <td class="nominal">${element.estimate_pay}</td>
          <td>${element.estimate_date_pay}</td>
          <td>${element.desc_sp2dk}</td>
        </tr>
    `;
    rows += row;
  });
  tableHtml += rows;
  let endTabelHtml = `
            </tbody>
          </table>
  `;
  tableHtml += endTabelHtml;

  return tableHtml;
}

function onLoadModalBtn() {
  const modalBtns = document.querySelectorAll('.modalBtn');
  const modal = document.querySelector("[data-modal]");
  const modalDiv = modal.getElementsByTagName('div')[0];
  const closeBtn = document.querySelector('[data-close-modal]')
  modalBtns.forEach(element => {
    element.addEventListener("click", function () {
      const idPrognosaAr = element.dataset.idPrognosaAr;
      modal.showModal();
      // fetch data from API
      getDetailsPrognosaById(idPrognosaAr)
        .then((result) => {
          modalDiv.innerHTML = tableOnModal(result);
          formatNominal();
        });
    });
    closeBtn.addEventListener('click', function () {
      modal.close();
    })
  });
}

function formatNominal() {
  let listNominal = document.querySelectorAll('.nominal');

  listNominal
    .forEach(element => {
      let nominal = element.innerHTML;
      if (!nominal.includes(',')) {
        nominal = Number(nominal);
        const formatted = nominal.toLocaleString('en-US');
        element.innerHTML = formatted;
      }
    });
}

function writeDataToTableReference(result) {
  // serach table reference
  const tbodyTableReference = document.getElementById('table-referenece').getElementsByTagName('tbody')[0];
  // search total ppm and pkm
  const totalPPMField = document.getElementById('totalPPM');
  const totalPKMField = document.getElementById('totalPKM');
  const totalPPMAr = document.getElementById('totalPPMAr');
  const totalPKMAr = document.getElementById('totalPKMAr');
  let totalPPM = 0;
  let totalPKM = 0;
  let { data } = result.result;
  let rows = '';

  // iterate data
  data.forEach((element, key) => {
    totalPPM += Number(element.ppm);
    totalPKM += Number(element.total_sp2dk_recom);
    let row = `
        <tr>
          <th scope="row">${key + 1}</th>
          <td>${element.name}</td>
          <td>${element.month}-${element.year}</td>
          <td class="nominal">${element.ppm}</td>
          <td class="nominal">${element.total_sp2dk_recom}</td>
          <td>
            <div data-id-prognosa-ar="${element.id_prognosa_ar}" class="modalBtn btn btn-icon btn-left btn-outline-info btn-sm"><i class="fas fa-info-circle"></i> Detail</div>
          </td>
        </tr>
        `
    rows += row;
  });

  // add data to tbody
  tbodyTableReference.innerHTML = rows;
  totalPPMField.innerHTML = totalPPM;
  totalPKMField.innerHTML = totalPKM;
  totalPPMAr.innerHTML = totalPPM;
  totalPKMAr.innerHTML = totalPKM;

  // format thousand
  formatNominal();
}

async function getDataPrognosaArBySeksi(url, seksi, month, year) {
  let formData = new FormData();
  formData.append('seksi', seksi);
  formData.append('month', month);
  formData.append('year', year);

  let result = await fetch(url, {
    method: "POST",
    body: formData
  })

  let data = await result.json();
  data = JSON.stringify(data);
  data = JSON.parse(data);

  return data;
}