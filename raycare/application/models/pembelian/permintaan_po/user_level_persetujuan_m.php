<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_level_persetujuan_m extends MY_Model {

	protected $_table        = 'user_level_persetujuan';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'user_level_persetujuan.id'         => 'id', 
		'user_level_persetujuan.tanggal'    => 'tanggal', 
		'user.nama'   							=> 'user', 
		'user_level.nama'   					=> 'user_level',
		'user_level_persetujuan.subjek'     => 'subjek',
		'user_level_persetujuan.keterangan' => 'keterangan',
		'user_level_persetujuan.is_active'  => 'is_active',
		'user_level_persetujuan.is_finish'  => 'is_finish',
		'(SELECT COUNT(*) FROM user_level_persetujuan_detail WHERE user_level_persetujuan_detail.user_level_persetujuan_id = 2)'	=> 'jumlah'
	);

	protected $datatable_columns_proses = array(
		//column di table  => alias
		'user_level_persetujuan.id'         => 'id', 
		'user_level_persetujuan.tanggal'   => 'tanggal', 
		'user.nama'   => 'user', 
		'user_level.nama'   => 'user_level',
		'user_level_persetujuan.subjek'     => 'subjek',
		'user_level_persetujuan.keterangan'     => 'keterangan',
		'user_level_persetujuan.is_active'     => 'is_active',
		'user_level_persetujuan.is_finish'     => 'is_finish',
		'(SELECT COUNT(*) FROM order_permintaan_detail WHERE order_permintaan_detail.order_permintaan_id = user_level_persetujuan.id)'	=> 'jumlah_terdaftar',
		'(SELECT COUNT(*) FROM order_permintaan_detail_other WHERE order_permintaan_detail_other.order_permintaan_id = user_level_persetujuan.id)'	=> 'jumlah_tidak_terdaftar',
		'MAX(persetujuan_permintaan_pembelian.status)' => 'status_terakhir'
		);



	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_data_order($user_level, $tipe)
	{
		$this->db->where('user_level_id', $user_level);
		$this->db->where('tipe_persetujuan', $tipe);
		$this->db->where('is_active', 1);
		$this->db->order_by('level_order','asc');

		return $this->db->get($this->_table);


	}
}

/* End of file cabang_m.php */
/* Location: ./application/models/pembelian/daftar_permintaan_po_m.php */