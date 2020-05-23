<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembayaran_cetak_m extends MY_Model {

	protected $_timestamps = true;
	protected $_table      = 'pembayaran_cetak';
	protected $_order_by   = 'id';

	private $_fillable = array();

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(

		//column di table  => alias
		'pembayaran.no_pembayaran'  => 'no_pembayaran',
		'pembayaran.nama_pasien'    => 'pasien',
		'user.nama'                 => 'kasir',
		'pembayaran.tipe_transaksi' => 'tipe_transaksi',
		'pembayaran_cetak.no_cetak' => 'no_cetak',
		'pembayaran.biaya_tunai'    => 'biaya_tunai',
		'pembayaran.biaya_klaim'    => 'biaya_klaim',
		'pembayaran.diskon'         => 'diskon',
		'pembayaran.pph'            => 'pph',
		'pembayaran.pembulatan'     => 'pembulatan',
		'pembayaran.id'             => 'id',
	);


	function __construct()
	{
		parent::__construct();
	}

		/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable()
	{
		
		// $join1 = array("pasien", $this->_table .'.pasien_id = pasien.id', 'left');
		$join2 = array("user", $this->_table .'.kasir_id = user.id', 'left');
		$join3 = array("pembayaran_cetak", $this->_table .'.id = pembayaran_cetak.pembayaran_id', 'left');
		$join_tables = array($join2, $join3);

		$wheres = array(
			'pembayaran.cabang_id'	=> $this->session->userdata('cabang_id')
		);
		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'is_active', 1);
		$this->db->where($wheres);

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($wheres);

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($wheres);

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 

		return $result; 
	}

	/**
	 * [fillable_add description]
	 * @return [type] [description]
	 */
	public function fillable()
	{
		return $this->_fillable;
	}

	

}
