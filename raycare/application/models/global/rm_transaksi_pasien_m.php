<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rm_transaksi_pasien_m extends MY_Model {

	protected $_table        = 'rm_transaksi_pasien';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		'tanggal',	
		'nama_cabang', 
		'nama_poliklinik', 
		'no_transaksi', 
		'nama_dokter',
		'keterangan',
		'is_active',
		'pasien_id',
		'transaksi_id',
		'poliklinik_id',
		'dokter_id'		
	);

	private $_fillable_edit = array(
		'tanggal',	
		'nama_cabang', 
		'nama_poliklinik', 
		'no_transaksi', 
		'nama_dokter',
		'keterangan',
		'is_active',
		'pasien_id',
		'transaksi_id',
		'poliklinik_id',
		'dokter_id'		
	);

	protected $datatable_columns = array(
		//column di table  => alias
		'rm_transaksi_pasien.id'              => 'id', 
		'rm_transaksi_pasien.tanggal'         => 'tanggal',	
		'rm_transaksi_pasien.nama_cabang'     => 'nama_cabang', 
		'rm_transaksi_pasien.nama_poliklinik' => 'nama_poliklinik', 
		'rm_transaksi_pasien.no_transaksi'    => 'no_transaksi', 
		'rm_transaksi_pasien.nama_dokter'     => 'nama_dokter',
		'rm_transaksi_pasien.keterangan'      => 'keterangan',
		'rm_transaksi_pasien.is_active'       => 'is_active',
		'rm_transaksi_pasien.pasien_id'       => 'pasien_id',
		'rm_transaksi_pasien.transaksi_id'    => 'transaksi_id',
		'rm_transaksi_pasien.poliklinik_id'   => 'poliklinik_id',
		'rm_transaksi_pasien.dokter_id'       => 'dokter_id',
		'rm_transaksi_pasien.cabang_id'       => 'cabang_id',
		'rm_transaksi_pasien.tipe'            => 'tipe'
	);


	public function __construct()
	{
		parent::__construct();
	}


	public function get_params()
	{
		$params = $this->datatable_param($this->datatable_columns);

		return $params;
	}


}

/* End of file Item_m.php */
/* Location: ./application/models/master/cabang_m.php */