document.onload = setYearField();

// choose button clicked
// get data based on year field
let chooseBtn = document.querySelector(".col .btn.btn-primary");
chooseBtn.addEventListener('click', function () {
  const yearField = Number(document.querySelector('[name="year"]').value);
  const url = chooseBtn.dataset.url;
  // run function request
  getDataByNip9(url, yearField)
    .then((result) => {
      writeDataToTableListPrognosa(result.result.data);
    });
});

// function section
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

function getMonthName(monthNumber) {
  const date = new Date();
  date.setMonth(monthNumber - 1);

  return date.toLocaleString('en-US', { month: 'long' });
}

function writeDataToTableListPrognosa(data) {
  let tbodyTableListPrognosa = document.getElementById('table-list-prognosa').getElementsByTagName('tbody')[0];

  if (data === undefined || data.length == 0) {
    let row = `
      <tr>
        <td colspan="5" class="text-center"><h3><strong>Tidak Ada Data 😅</strong></h3></td>
      </tr>
    `;
    tbodyTableListPrognosa.innerHTML = row;
    return;
  }

  let totalPPMField = document.getElementById('totalPPM');
  let totalPKMField = document.getElementById('totalPKM');
  let totalPPM = 0;
  let totalPKM = 0;
  let rows = '';

  data.forEach((element, key) => {
    totalPPM += Number(element.ppm);
    totalPKM += Number((element.total_sp2dk_recom) ? element.total_sp2dk_recom : element.pkm);
    let row = `
      <tr>
        <th scope="row">${key + 1}</th>
        <td>${getMonthName(Number(element.month))}</td>
        <td class="nominal">${Number(element.ppm)}</td>
        <td class="nominal">${Number((element.total_sp2dk_recom) ? element.total_sp2dk_recom : element.pkm)}</td>
        <td>
          <div data-id-prognosa="${element.id_prognosa_ar}" class="btn btn-info"><i class="fas fa-info-circle"></i> Detail</div>
        </td>
      </tr>
    `
    rows += row;
  });

  tbodyTableListPrognosa.innerHTML = rows;
  totalPPMField.innerHTML = totalPPM;
  totalPKMField.innerHTML = totalPKM;

  formatNominal();
}

function setYearField() {
  let yearField = document.querySelector('[name="year"]');
  yearField.value = new Date().getFullYear();
}

async function getDataByNip9(url, yearP) {
  let formData = new FormData();
  formData.append('yearP', yearP);

  let result = await fetch(url, {
    method: "POST",
    body: formData
  })

  let data = await result.json();
  data = JSON.stringify(data);
  data = JSON.parse(data);

  return data;
}