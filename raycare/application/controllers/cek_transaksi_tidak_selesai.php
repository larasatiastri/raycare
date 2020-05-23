<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cek_transaksi_tidak_selesai extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('klinik_hd/pendaftaran_tindakan_m');
		$this->load->model('klinik_hd/pendaftaran_tindakan_history_m');
        $this->load->model('klinik_hd/tindakan_hd_m');
        $this->load->model('klinik_hd/tindakan_hd_history_m');
        $this->load->model('klinik_hd/tindakan_hd_penaksiran_m');
        $this->load->model('klinik_hd/tindakan_hd_penaksiran_history_m');
	}

	public function index(){

		$now = date('Y-m-d', strtotime("-1 day"));

		$data_pendaftaran_history = $this->pendaftaran_tindakan_history_m->get_by(array("date(created_date) < " => "'$now'", 'status' => 2));
		$data_pendaftaran_history = object_to_array($data_pendaftaran_history);

		foreach ($data_pendaftaran_history as $daftar_history) {
			$data['status'] = 3;
			$edit_daftar_history = $this->pendaftaran_tindakan_history_m->edit_data($data, $daftar_history['id']);
		}

		$data_pendaftaran = $this->pendaftaran_tindakan_m->get_by(array("date(created_date) < " => "'$now'"));
		$data_pendaftaran = object_to_array($data_pendaftaran);

		foreach ($data_pendaftaran as $daftar) {
			$hapus_daftar = $this->pendaftaran_tindakan_m->delete($daftar['id']);
		}

		$data_tindakan_history = $this->tindakan_hd_history_m->get_by(array("date(tanggal) < " => "'$now'", 'status' => 2));
		$data_tindakan_history = object_to_array($data_tindakan_history);

		foreach ($data_tindakan_history as $tindakan_history) {
			$data_tindakan['status'] = 3;
			$edit_tindaakan_history = $this->tindakan_hd_history_m->edit_data($data_tindakan, $tindakan_history['id']);
		}

		$data_tindakan_hd = $this->tindakan_hd_m->get_by(array("date(created_date) < " => "'$now'"));
		$data_tindakan_hd = object_to_array($data_tindakan_hd);

		foreach ($data_tindakan_hd as $tindakan) {
			$hapus_tindakan = $this->tindakan_hd_m->delete($tindakan['id']);
		}

	}
}