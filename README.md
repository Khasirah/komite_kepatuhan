<h1 align="center">Aplikasi Komite Kepatuhan Using Stisla for CodeIgniter</h1>

<p align="center">
  Stisla is Free Bootstrap Admin Template and will help you to speed up your project, design your own dashboard UI and the users will love it.
</p>

<p align="center">
  this template I use for <strong>App Komite Kepatuhan</strong> at <strong>KPP Pratama Jakarta Menteng Dua</strong>
</p>


## Table of contents

- [Table of contents](#table-of-contents)
- [Feature](#feature)
- [Link Stisla](#link-stisla)
- [Installation](#installation)
- [Usage](#usage)
- [Contact Me](#contact-me)
- [License](#license)

## Feature
- User Management
  - Create
  - Read
  - Update
  - Delete
- Prognosa Menu
  - Daftar Prognosa
  - Prognosa AR
    - Choose SP2DK with estimate pay and estimate date pay
  - Prognosa Kasi
    - Table reference of prognosa ar based on seksi

## Link Stisla
- Homepage: [getstisla.com](https://getstisla.com)
- Repository: [github.com/stisla/stisla](https://github.com/stisla/stisla)
- Documentation: [getstisla.com/docs](https://getstisla.com/docs)

## Installation
- [Download the latest release](https://github.com/KhidirDotID/stisla-codeigniter/archive/v1.0.0.zip).
or clone the repo :
```
https://github.com/KhidirDotID/stisla-codeigniter.git
```

## Usage
- Create a new Controller at `application/controllers` then put like this:
```
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Controller_name extends CI_Controller {

	public function index() {
		$data = array(
			'title' => "Your title"
		);
		$this->load->view('View_name', $data);
	}
}
?>
```
- Create a new View at `application/views` then put like this:
```
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('dist/_partials/header'); ?>

      <!-- Main Content -->

<?php
$this->load->view('dist/_partials/footer'); ?>
```

## Contact Me
Ahmad Haris Kurniawan
ahmadharisk@gmail.com
or you can send letter to <strong>KPP Pratama Jakarta Menteng Dua</strong>


## License

Stisla is under the [MIT License](LICENSE).
