const fileInput = document.querySelector('[name="fileUploaded"]');
const submitBtn = document.querySelector('#submitBtn');

// all event listener
submitBtn.addEventListener('click', function () {
  const uploadedFile = fileInput.files[0];
  const url = fileInput.dataset.url;

  showProgressBar();

  try {
    Papa.parse(uploadedFile, {
      header: true,
      skipEmptyLines: true,
      complete: function (datas) {
        for (i = 0; i < datas.data.length; i++) {
          let title = `Gagal menambah data pada baris-${i + 1}`;
          if (!validateNip9(datas.data[i].nip9)) {
            callToastr(false, title, "kolom nip9 harus 9 digit");
            continue;
          }
          if (!validatePosition(datas.data[i].id_position)) {
            callToastr(false, title, "kolom id_position harus >= 2 dan <= 5");
            continue;
          }
          if (!validateRole(datas.data[i].id_role)) {
            callToastr(false, title, "kolom id_role harus >= 2 dan <= 3");
            continue;
          }
          if (!validateSeksi(datas.data[i].id_seksi)) {
            callToastr(false, title, "kolom id_seksi harus >= 1 dan <= 6");
            continue;
          }
          // update progress bar
          addProgress(datas.data.length, i + 1);

          addUser(url, datas.data[i])
            .then((result) => {
              callToastr(result.status, (result.status) ? "Berhasil" : "Gagal", result.desc);
            })
            .catch((e) => {
              callToastr(false, "Gagal", e);
            });

        }
      }
    });
  } catch (e) {
    callToastr(false, "Gagal", "Pilih file terlebih dahulu");
  }
});

fileInput.addEventListener('change', function () {
  const uploadedFile = fileInput.files[0];
  const isCsv = checkCsvFile(uploadedFile.name);

  if (!isCsv) {
    callToastr(isCsv, 'Gagal', 'Bukan file csv');
    fileInput.value = "";
    return;
  }
});

// function 
function showProgressBar() {
  let parentProgressBar = document.querySelector('[hidden]');
  parentProgressBar.hidden = false;
}

function addProgress(total, currentStep) {
  let progresBar = document.querySelector('.progress-bar.bg-warning');
  let percentage = `${(currentStep / total) * 100}%`;
  progresBar.setAttribute('data-width', percentage);
  progresBar.style.width = percentage;
  progresBar.innerHTML = percentage;
}

function validateSeksi(id_seksi) {
  try {
    let seksi = Number(id_seksi)
    if (seksi >= 1 && seksi <= 6) {
      return true;
    }
  } catch (error) {
    return false;
  }
}

function validateRole(id_role) {
  try {
    let role = Number(id_role)
    if (role >= 2 && role <= 3) {
      return true;
    }
  } catch (error) {
    return false;
  }
}

function validatePosition(id_position) {
  try {
    let position = Number(id_position)
    if (position >= 2 && position <= 5) {
      return true;
    }
  } catch (error) {
    return false;
  }
}

function validateNip9(nip9) {
  if (nip9.length == 9) {
    return true;
  }
  return false;
}

async function addUser(url, dataCsv) {
  let formData = new FormData();
  formData.append('nip9', dataCsv.nip9);
  formData.append('name', dataCsv.name);
  formData.append('position', dataCsv.id_position);
  formData.append('role', dataCsv.id_role);
  formData.append('seksi', dataCsv.id_seksi);


  let result = await fetch(url, {
    method: "POST",
    body: formData
  })

  let data = await result.json();
  data = JSON.stringify(data);
  data = JSON.parse(data);

  return data;
}

function callToastr(status, title, desc) {
  if (status) {
    iziToast.success({
      title: title,
      message: desc,
      position: 'topRight'
    });
    return;
  }

  if (!status) {
    iziToast.error({
      title: title,
      message: desc,
      position: 'topRight'
    });
    return;
  }
}

function checkCsvFile(fileName) {
  const arrayFileName = fileName.split(".");
  const typeFile = arrayFileName.pop();
  if (typeFile == 'csv') {
    return true;
  }
  return false;
}