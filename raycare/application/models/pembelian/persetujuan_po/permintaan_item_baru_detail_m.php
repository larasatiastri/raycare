<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permintaan_item_baru_detail_m extends MY_Model {

	protected $_table        = 'permintaan_item_baru_detail';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;


	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias 
		'persetujuan_pembelian.persetujuan_pembelian_id'   => 'id', 
		'user_level.nama'   => 'user_level', 
		'persetujuan_pembelian.order'   => '`order`', 
		'persetujuan_pembelian.status'   => '`status`',
		'persetujuan_pembelian.tanggal_baca'     => 'tanggal_baca',
		'user.nama'     => 'dibaca_oleh',
		'persetujuan_pembelian.tanggal_persetujuan'     => 'tanggal_persetujuan',
		'user.nama'     => 'disetujui_oleh',
		'persetujuan_pembelian.jumlah_persetujuan'     => 'jumlah'
		
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($id)
	{	

		$join1 = array('user_level', $this->_table.'.user_level_id = user_level.id');
		$join2 = array('item_satuan', $this->_table.'.satuan_id = item_satuan.id');
		$join_tables = array($join1, $join2);

		$datatable_columns = array(
		//column di table  => alias 
			'persetujuan_pembelian.persetujuan_pembelian_id' => 'id', 
			'user_level.nama'                                => 'user_level', 
			'persetujuan_pembelian.order'                    => '`order`', 
			'persetujuan_pembelian.status'                   => '`status`',
			'persetujuan_pembelian.tanggal_baca'             => 'tanggal_baca',
			'persetujuan_pembelian.tanggal_persetujuan'      => 'tanggal_persetujuan',
			'persetujuan_pembelian.jumlah_persetujuan'       => 'jumlah',
			'item_satuan.nama'                               => 'nama_satuan',
			'(SELECT `user`.nama FROM `user` WHERE persetujuan_pembelian.dibaca_oleh = `user`.id)' => 'dibaca_oleh',
			'(SELECT `user`.nama FROM `user` WHERE persetujuan_pembelian.disetujui_oleh = `user`.id)' => 'disetujui_oleh',
		);
		// get params dari input postnya datatable
		$params = $this->datatable_param($datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('pembelian_detail_id', $id);
		// $this->db->where('is_active',1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('pembelian_detail_id', $id);
		// $this->db->where('is_active',1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('pembelian_detail_id', $id);
		// $this->db->where('is_active',1);

		// tentukan kolom yang mau diselect
		foreach ($datatable_columns as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $datatable_columns;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function update_persetujuan($id)
	{
		$sekarang = date('Y-m-d H:i:s');
		$user = $this->session->userdata('user_id');

		$format = "UPDATE persetujuan_pembelian SET status = 2, tanggal_baca = '$sekarang', dibaca_oleh = $user WHERE pembelian_id = $id";

		return $this->db->query($format);
	}

	public function update($id, $jumlah, $satuan, $id_detail, $status, $user, $keterangan)
	{
		$sekarang = date('Y-m-d H:i:s');
		$user = $this->session->userdata('user_id');

		$format = "UPDATE persetujuan_pembelian SET status = $status, tanggal_persetujuan = '$sekarang', disetujui_oleh = $user, jumlah_persetujuan = $jumlah, satuan_id = $satuan, modified_by = $user, modified_date = '$sekarang', keterangan = '$keterangan'  WHERE pembelian_id = $id AND pembelian_detail_id = $id_detail AND user_level_id = $user";

		return $this->db->query($format);
	}

	public function get_data_persetujuan($id_beli, $id_beli_detail)
	{
		$format = "SELECT 
						persetujuan_pembelian.jumlah_persetujuan AS jumlah,
						persetujuan_pembelian.`status` As status
					FROM
						persetujuan_pembelian
					WHERE 
						persetujuan_pembelian.pembelian_id = $id_beli AND
						persetujuan_pembelian.pembelian_detail_id = $id_beli_detail";

		return $this->db->query($format);
	}

}

/* End of file Permintaan_item_baru_m.php */
/* Location: ./application/models/master/Permintaan_item_baru_m.php */