async function getAllSP2DKByNip(nip9, url) {

  let formData = new FormData();
  formData.append("nip9", nip9);

  const result = await fetch(url, {
    method: "POST",
    body: formData
  });

  let data = await result.json();
  data = JSON.stringify(data);
  data = JSON.parse(data);
  return data;
}

function addDataToTable(dataSp2dk, curPage = 1) {
  let tbodyTableSp2dk = document.getElementById('table-sp2dk').getElementsByTagName('tbody')[0];
  let { data } = dataSp2dk.sp2dk;
  let dataEachPage = 10;
  let rows = ''

  data
    .filter((elem, key) => {
      let dataStart = (curPage - 1) * dataEachPage;
      let dataEnd = curPage * dataEachPage;
      if (key >= dataStart && key < dataEnd) return true;
    })
    .forEach((elem, key) => {
      let row = `<tr>
                  <td class="p-0 text-center">
                    <div class="custom-checkbox custom-control">
                      <input type="checkbox" data-checkboxes="mygroup" data-id-sp2dk="${elem.id_sp2dk}" class="custom-control-input" id="checkbox-${key + 1}">
                      <label for="checkbox-${key + 1}" class="custom-control-label">&nbsp;</label>
                    </div>
                  </td>
                  <td>${elem.no_sp2dk}</td>
                  <td class="nominal">${elem.nominal}</td>
                </tr>`;

      rows += row;
    });

  tbodyTableSp2dk.innerHTML = rows;
}


function main(dataSp2dk) {
  const data10 = document.querySelectorAll('[data-id-sp2dk]');

  data10.forEach(element => {
    element.addEventListener('click', function () {
      let dataChecked = (localStorage.getItem('dataChecked')) ? JSON.parse(localStorage.getItem('dataChecked')) : [];
      const idSp2dk = element.dataset.idSp2dk;
      const sp2dk = dataSp2dk.sp2dk.data;
      const sp2dkClicked = sp2dk.find(item => item.id_sp2dk == idSp2dk);
      let monthField = document.querySelector('[name="month"]');
      let yearField = document.querySelector('[name="year"]');
      const month = Number(monthField.value) - 1;
      const year = Number(yearField.value);
      let detailSp2dk = {
        id_sp2dk: idSp2dk,
        no_sp2dk: sp2dkClicked.no_sp2dk,
        nominal: sp2dkClicked.nominal,
        estimatePay: 0,
        estimateDatePay: '',
        desc: '-'
      }

      // check if idSp2dk exist in dataChecked
      if (!dataChecked.find(item => item.id_sp2dk == idSp2dk)) {
        dataChecked.push(detailSp2dk);
        localStorage.setItem('dataChecked', JSON.stringify(dataChecked));
        sendDataToSp2dkRecom(month, year);
      } else {
        let indexOfDup = dataChecked.findIndex(item => item.id_sp2dk == idSp2dk);
        dataChecked.splice(indexOfDup, 1);
        localStorage.setItem('dataChecked', JSON.stringify(dataChecked));
        sendDataToSp2dkRecom(month, year);
      }

      if (jQuery().daterangepicker) {
        if ($(".date-picker").length) {
          const myDate = new GetDate();
          $('.date-picker').daterangepicker({
            locale: { format: 'YYYY-MM-DD' },
            singleDatePicker: true,
            "minDate": myDate.getDateNowFormatted(),
            "maxDate": myDate.getDateLastDay(month, year)
          })
        }
      }
    })
  });
}

function setCheckedBox() {
  const data10 = document.querySelectorAll('[data-id-sp2dk]');
  data10.forEach(elem => {
    const idSp2dk = elem.dataset.idSp2dk;
    let dataChecked = (localStorage.getItem('dataChecked')) ? JSON.parse(localStorage.getItem('dataChecked')) : [];

    if (dataChecked.find(item => item.id_sp2dk == idSp2dk)) {
      elem.checked = true;
    }
  })
}

function sendDataToSp2dkRecom(month = null, year = null) {
  let dataChecked = (localStorage.getItem('dataChecked')) ? JSON.parse(localStorage.getItem('dataChecked')) : [];
  let tbodyTableSp2dkRecom = document.getElementById('table-recom').getElementsByTagName('tbody')[0];
  let rows = '';

  dataChecked
    .forEach((elem, key) => {
      let row = `<tr>
                  <td>${key + 1}</td>
                  <td>${elem.no_sp2dk}</td>
                  <td class="nominal">${elem.nominal}</td>
                  <td>
                    <input name="idSp2dk[]" value="${elem.id_sp2dk}" hidden type="text">
                    <input name="estimatePay[]" data-id-sp2dk-field="${elem.id_sp2dk}" onChange="handleChange(this)" class="text-right form-control currency" type="text" placeholder="0">
                  </td>
                  <td>
                    <input name="estimateDatePay[]" data-id-sp2dk-field="${elem.id_sp2dk}" onChange="handleChange(this)" type="text" class="form-control text-right date-picker" value="${elem.estimateDatePay}">
                  </td>
                  <td>
                    <textarea name="desc[]" data-id-sp2dk-field="${elem.id_sp2dk}" onChange="handleChange(this)" class="form-control"></textarea>
                  </td>
                  <td>
                    <div data-id-sp2dk-cancel="${elem.id_sp2dk}" onClick="handleClickCancelSp2dk(this)" class="btn btn-danger">Batal</div>
                  </td>
                </tr>`;

      rows += row;
    });
  tbodyTableSp2dkRecom.innerHTML = rows;


  if (jQuery().daterangepicker) {
    if ($(".date-picker").length) {
      const myDate = new GetDate();
      $('.date-picker').daterangepicker({
        locale: { format: 'YYYY-MM-DD' },
        singleDatePicker: true,
        "minDate": myDate.getDateNowFormatted(),
        "maxDate": myDate.getDateLastDay(month, year)
      })
    }
  }

  $(".currency").toArray().forEach(element => {
    new Cleave(element, {
      numeral: true,
      numeralThousandsGroupStyle: "thousand"
    });
  });

  setValueToSp2dkRecomField();
  sumDatainTableSp2dkRecom();
  formatNominal();
}

function deleteDataFromLocal(idSp2dk) {
  let dataChecked = (localStorage.getItem('dataChecked')) ? JSON.parse(localStorage.getItem('dataChecked')) : [];
  let indexOfDup = dataChecked.findIndex(item => item.id_sp2dk == idSp2dk);
  dataChecked.splice(indexOfDup, 1);
  localStorage.setItem('dataChecked', JSON.stringify(dataChecked));
  sendDataToSp2dkRecom();
}

function unCheckedData(idSp2dk) {
  const data10 = document.querySelectorAll('[data-id-sp2dk]');
  let dataChecked = (localStorage.getItem('dataChecked')) ? JSON.parse(localStorage.getItem('dataChecked')) : [];

  data10.forEach(elem => {

    if (dataChecked.find(item => item.id_sp2dk == idSp2dk)) {
      elem.checked = false;
    }
  })
}

function handleClickCancelSp2dk(e) {
  const idSp2dk = e.dataset.idSp2dkCancel;
  unCheckedData(idSp2dk);
  deleteDataFromLocal(idSp2dk);
  setValueToSp2dkRecomField();
  formatNominal();
}

function changePagiNum(curPage, totalPage, listBtnPage) {

  if ((curPage != 1) && (curPage != totalPage)) {
    listBtnPage[0].innerHTML = curPage - 1;
    listBtnPage[1].innerHTML = curPage;
    listBtnPage[2].innerHTML = curPage + 1;
    listBtnPage[1].parentElement.classList.add('active');
    document.querySelector('#nextBtn.disabled')?.classList.remove('disabled');
    document.querySelector('#prevBtn.disabled')?.classList.remove('disabled');
  }
  if (curPage == 1) {
    listBtnPage[0].parentElement.classList.add('active');
    document.querySelector('#prevBtn')?.classList.add('disabled');
  }
  if (curPage == totalPage) {
    listBtnPage[2].parentElement.classList.add('active');
    document.querySelector('#nextBtn')?.classList.add('disabled');
  }
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

function sumDatainTableSp2dkRecom() {
  let dataChecked = (localStorage.getItem('dataChecked')) ? JSON.parse(localStorage.getItem('dataChecked')) : [];
  let totalNominal = 0;
  let totalNominalField = document.querySelector('.totalNominal');
  let inputTotalNominal = document.querySelector('[name="totalNominal"]');

  dataChecked.forEach(element => {
    totalNominal += element.estimatePay;
  });

  totalNominalField.innerHTML = totalNominal;
  inputTotalNominal.value = totalNominal;
}

function handleChange(e) {
  let dataChecked = (localStorage.getItem('dataChecked')) ? JSON.parse(localStorage.getItem('dataChecked')) : [];
  let idSp2dk = e.dataset.idSp2dkField;
  let nameField = e.name;
  let sp2dkIndex = dataChecked.findIndex(item => {
    return item.id_sp2dk == idSp2dk
  });

  if (nameField == "estimatePay[]") {
    let estimatePayField = e.value;
    estimatePayField = parseInt(estimatePayField.replaceAll(',', ''));
    dataChecked[sp2dkIndex].estimatePay = estimatePayField;
    localStorage.setItem('dataChecked', JSON.stringify(dataChecked));
  }

  if (nameField == "estimateDatePay[]") {
    let estimateDatePay = e.value;
    dataChecked[sp2dkIndex].estimateDatePay = estimateDatePay;
    localStorage.setItem('dataChecked', JSON.stringify(dataChecked));
  }

  if (nameField == "desc[]") {
    let desc = e.value;
    dataChecked[sp2dkIndex].desc = desc;
    localStorage.setItem('dataChecked', JSON.stringify(dataChecked));
  }

  sumDatainTableSp2dkRecom();
  formatNominal();
}

function setValueToSp2dkRecomField() {
  let dataChecked = (localStorage.getItem('dataChecked')) ? JSON.parse(localStorage.getItem('dataChecked')) : [];

  dataChecked.forEach(element => {
    let inputEstimatePay = document.querySelector(`[name="estimatePay[]"][data-id-sp2dk-field="${element.id_sp2dk}"]`);
    let inputEstimateDatePay = document.querySelector(`[name="estimateDatePay[]"][data-id-sp2dk-field="${element.id_sp2dk}"]`);
    let inputDesc = document.querySelector(`[name="desc[]"][data-id-sp2dk-field="${element.id_sp2dk}"]`);

    inputEstimatePay.value = element.estimatePay.toLocaleString('en-US');
    inputEstimateDatePay.value = element.estimateDatePay;
    inputDesc.value = element.desc;
  });
}

function deleteAllDataFromLocal() {
  localStorage.removeItem('dataChecked');
}
