<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Grab_data extends CI_Controller {

	public function __construct()
	{		
		parent::__construct();

		$this->load->model('keuangan/tanda_terima_faktur/tanda_terima_faktur_m');
        $this->load->model('keuangan/tanda_terima_faktur/tanda_terima_faktur_detail_m');
        $this->load->model('keuangan/tanda_terima_faktur/pembelian_m');
        $this->load->model('keuangan/tanda_terima_faktur/pembelian_detail_m');
        $this->load->model('pembelian/pembelian_biaya_m');
        $this->load->model('pembelian/pembelian_invoice_m');

        $this->load->model('keuangan/tanda_terima_faktur/pembayaran_status_m');
        $this->load->model('keuangan/pembayaran_status/pembayaran_status_detail_m');

        $this->load->model('pembelian/supplier_m');
        $this->load->model('master/supplier/supplier_alamat_m');
        $this->load->model('master/supplier/supplier_telepon_m');
        $this->load->model('master/supplier/supplier_email_m');
        $this->load->model('master/item/item_satuan_m');
        $this->load->model('pembelian/penerima_cabang_m');

        $this->load->model('master/biaya_m');

        $this->load->model('others/kotak_sampah_m');
        $this->load->model('keuangan/perubahan_modal/o_s_hutang_m');
	}

	public function index()
	{
		// $data = get_data_website_supplier('http://simrhs.com/supplier/');
		$data = get_data_website_supplier('http://klinikraycare.com/supkld/');

		$ftpHost   = 'ftp.restupratamafarmasi.com';
		$ftpUsername = 'cloud@klinikraycare.com';
		$ftpPassword = 'P@ssw0rddesign';

		// die(dump($data));

		$response = new stdClass;
		$response->success = false;

		// open an FTP connection
		$connId = ftp_connect($ftpHost) or die("Couldn't connect to $ftpHost");


		// login to FTP server
		$ftpLogin = ftp_login($connId, $ftpUsername, $ftpPassword);
		ftp_pasv($connId, true);


		foreach ($data['data_ttf'] as $ttf) {
			
			$data_ttf_local = $this->tanda_terima_faktur_m->get_by(array('id' => $ttf['id']), true);

			if(count($data_ttf_local) == 0){
				$insert_ttf = $this->tanda_terima_faktur_m->add_data($ttf);
			}else{
				$update_ttf = $this->tanda_terima_faktur_m->edit_data($ttf, $ttf['id']);
			}

			$path_dokumen_ttf = '../cloud/'.config_item('site_dir').'pages/keuangan/tanda_terima_faktur/images/'.str_replace(' ', '_', $ttf['id']);
            if (!is_dir($path_dokumen_ttf)){mkdir($path_dokumen_ttf, 0777, TRUE);}

            $delete_ttf_detail = $this->tanda_terima_faktur_detail_m->delete_by(array('tanda_terima_faktur_id' => $ttf['id']));
		}

		foreach ($data['data_ttf_detail'] as $ttf_details) {

			foreach ($ttf_details as $ttf_detail) {


				$data_ttf_detail_local = $this->tanda_terima_faktur_detail_m->get_by(array('id' => $ttf_detail['id']), true);

				if(count($data_ttf_detail_local) == 0){
					$insert_ttf_detail = $this->tanda_terima_faktur_detail_m->add_data($ttf_detail);
				}else{
					$update_ttf_detail = $this->tanda_terima_faktur_detail_m->edit_data($ttf_detail, $ttf_detail['id']);
				}

				$localFilePath  = '../cloud/'.config_item('site_dir').'pages/keuangan/tanda_terima_faktur/images/'.str_replace(' ', '_', $ttf_detail['tanda_terima_faktur_id']).'/'.$ttf_detail['url_berkas'];
				$localFilePath1  = '../cloud/'.config_item('site_dir').'pages/keuangan/tanda_terima_faktur/images/'.str_replace(' ', '_', $ttf_detail['tanda_terima_faktur_id']).'/'.$ttf_detail['url_faktur_pajak'];

				$remoteFilePath = 'supkld/pages/transaksi/tanda_terima_faktur/images/'.str_replace(' ', '_', $ttf_detail['tanda_terima_faktur_id']).'/'.$ttf_detail['url_berkas'];
				$remoteFilePath1 = 'supkld/pages/transaksi/tanda_terima_faktur/images/'.str_replace(' ', '_', $ttf_detail['tanda_terima_faktur_id']).'/'.$ttf_detail['url_faktur_pajak'];

				// try to download a file from server
				if(ftp_get($connId, $localFilePath, $remoteFilePath, FTP_BINARY)){
				    echo "File transfer successful - $localFilePath";
				}if(ftp_get($connId, $localFilePath1, $remoteFilePath1, FTP_BINARY)){
				    //echo "File transfer successful - $localFilePath";
				}
			}
			
			
		}

		foreach ($data['data_po'] as $po) {
			
			$data_po_local = $this->pembelian_m->get_by(array('id' => $po['id']), true);

			if(count($data_po_local) == 0){
				$insert_po = $this->pembelian_m->add_data($po);
			}else{
				$update_po = $this->pembelian_m->edit_data($po, $po['id']);
			}

			$delete_pembelian_invoice = $this->pembelian_invoice_m->delete_by(array('pembelian_id' => $po['id']));
			
		}

		foreach ($data['data_po_detail'] as $po_detail) {
			
			$data_po_detail_local = $this->pembelian_detail_m->get_by(array('id' => $po_detail['id']), true);

			if(count($data_po_detail_local) == 0){
				$insert_po_detail = $this->pembelian_detail_m->add_data($po_detail);
			}else{
				$update_po_detail = $this->pembelian_detail_m->edit_data($po_detail, $po_detail['id']);
			}
		}

		foreach ($data['data_po_biaya'] as $po_biaya) {
			
			$data_po_biaya_local = $this->pembelian_biaya_m->get_by(array('id' => $po_biaya['id']), true);

			if(count($data_po_biaya_local) == 0){
				$insert_po_biaya = $this->pembelian_biaya_m->add_data($po_biaya);
			}else{
				$update_po_biaya = $this->pembelian_biaya_m->edit_data($po_biaya, $po_biaya['id']);
			}
		}

		foreach ($data['data_po_invoice'] as $po_invoice) {
			

			$data_po_invoice_local = $this->pembelian_invoice_m->get_by(array('id' => $po_invoice['id']), true);

			if(count($data_po_invoice_local) == 0){
				$insert_po_invoice = $this->pembelian_invoice_m->add_data($po_invoice);
			}else{
				$update_po_invoice = $this->pembelian_invoice_m->edit_data($po_invoice, $po_invoice['id']);
			}

			$path_dokumen_trans = '../cloud/'.config_item('site_dir').'pages/keuangan/pembayaran_transaksi/images/'.$po_invoice['pembayaran_status_id'];
            if (!is_dir($path_dokumen_trans)){mkdir($path_dokumen_trans, 0777, TRUE);}

            $localFilePath  = '../cloud/'.config_item('site_dir').'pages/keuangan/pembayaran_transaksi/images/'.$po_invoice['pembayaran_status_id'].'/'.$po_invoice['url'];
			$localFilePath1  = '../cloud/'.config_item('site_dir').'pages/keuangan/pembayaran_transaksi/images/'.$po_invoice['pembayaran_status_id'].'/'.$po_invoice['url_faktur_pajak'];

			$remoteFilePath = 'supkld/pages/keuangan/pembayaran_transaksi/images/'.$po_invoice['pembayaran_status_id'].'/'.$po_invoice['url'];
			$remoteFilePath1 = 'supkld/pages/keuangan/pembayaran_transaksi/images/'.$po_invoice['pembayaran_status_id'].'/'.$po_invoice['url_faktur_pajak'];

			// try to download a file from server
			if(ftp_get($connId, $localFilePath, $remoteFilePath, FTP_BINARY)){
			    //echo "File transfer successful - $localFilePath";
			}if(ftp_get($connId, $localFilePath1, $remoteFilePath1, FTP_BINARY)){
			    //echo "File transfer successful - $localFilePath";
			}


		}

		foreach ($data['data_bayar_status'] as $pembayaran_status) {
			
			$data_pembayaran_status_local = $this->pembayaran_status_m->get_by(array('id' => $pembayaran_status['id']), true);

			if(count($data_pembayaran_status_local) == 0){
				$insert_pembayaran_status = $this->pembayaran_status_m->add_data($pembayaran_status);
			}else{
				$update_pembayaran_status = $this->pembayaran_status_m->edit_data($pembayaran_status, $pembayaran_status['id']);
			}

		}

		foreach ($data['data_bayar_status_detail'] as $pembayaran_status_detail) {
			
			$data_pembayaran_status_detail_local = $this->pembayaran_status_detail_m->get_by(array('id' => $pembayaran_status_detail['id']), true);

			if(count($data_pembayaran_status_detail_local) == 0){
				$insert_pembayaran_status_detail = $this->pembayaran_status_detail_m->add_data($pembayaran_status_detail);
			}else{
				$update_pembayaran_status_detail = $this->pembayaran_status_detail_m->edit_data($pembayaran_status_detail, $pembayaran_status_detail['id']);
			}
		}


		// close the connection
		ftp_close($connId);

		$response->success = true;
		$response->msg = "Berhasil";



	}

	public function get_data_item(){
		
		$data = get_data_item_new('http://simrhs.com/raycareoffice/');

		$this->load->model('master/item/item_m');
        $this->load->model('master/item/item_satuan_m');
        $this->load->model('master/item/item_sub_kategori_m');
        $this->load->model('master/item/item_kategori_m');

        $response = new stdClass;
		$response->success = false;

		foreach ($data['data_item'] as $item_new) {			

			$data_item_new_local = $this->item_m->get_by(array('id' => $item_new['id']), true);

			if(count($data_item_new_local) == 0){
				$insert_item_new = $this->item_m->add_data($item_new);
			}else{
				$update_item_new = $this->item_m->edit_data($item_new, $item_new['id']);
			}
		}

		foreach ($data['data_item_satuan'] as $item_satuan_new) {	

			unset($item_satuan_new['tanggal']);
			unset($item_satuan_new['harga']);		

			$data_item_satuan_new_local = $this->item_satuan_m->get_by(array('id' => $item_satuan_new['id']), true);

			if(count($data_item_satuan_new_local) == 0){
				$insert_item_satuan_new = $this->item_satuan_m->add_data($item_satuan_new);
			}else{
				
				$update_item_satuan_new = $this->item_satuan_m->edit_data($item_satuan_new, $item_satuan_new['id']);
			}
		}

		foreach ($data['data_item_kategori'] as $item_kategori_new) {	


			$data_item_kategori_new_local = $this->item_kategori_m->get_by(array('id' => $item_kategori_new['id']), true);

			if(count($data_item_kategori_new_local) == 0){
				$insert_item_kategori_new = $this->item_kategori_m->add_data($item_kategori_new);
			}else{
				
				$update_item_kategori_new = $this->item_kategori_m->edit_data($item_kategori_new, $item_kategori_new['id']);
			}
		}

		foreach ($data['data_item_subkategori'] as $item_sub_kategori_new) {	


			$data_item_sub_kategori_new_local = $this->item_sub_kategori_m->get_by(array('id' => $item_sub_kategori_new['id']), true);

			if(count($data_item_sub_kategori_new_local) == 0){
				$insert_item_sub_kategori_new = $this->item_sub_kategori_m->add_data($item_sub_kategori_new);
			}else{
				
				$update_item_sub_kategori_new = $this->item_sub_kategori_m->edit_data($item_sub_kategori_new, $item_sub_kategori_new['id']);
			}
		}

		$response->success = true;
		$response->msg = "Berhasil";
	}

}

/* End of file grab_data.php */
/* Location: ./application/controllers/grab_data.php */