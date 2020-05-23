<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permintaan_pengepakan_detail_m extends MY_Model {

	protected $_table        = 'permintaan_pengepakan_detail';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		
	);

	

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'permintaan_pengepakan_detail.id'                       => 'id',
		'permintaan_pengepakan_detail.permintaan_pengepakan_id' => 'permintaan_pengepakan_id',
		'permintaan_pengepakan_detail.box_paket_id'             => 'box_paket_id',
		'permintaan_pengepakan_detail.jumlah_minta'             => 'jumlah_minta',
		'permintaan_pengepakan_detail.jumlah_proses'            => 'jumlah_proses',
		'permintaan_pengepakan_detail.created_by'               => 'created_by',
		'permintaan_pengepakan_detail.created_date'             => 'tanggal',
		'box_paket.nama'                                        => 'box_paket',
		'`user`.nama'                                           => 'user_nama'
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable()
	{		
		$join1 = array('box_paket', 'permintaan_pengepakan_detail.box_paket_id = box_paket.id', 'left');
		$join2 = array('`user`', 'permintaan_pengepakan_detail.created_by = `user`.id', 'left');

		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('permintaan_pengepakan_detail.jumlah_minta != 0');
		$this->db->where('permintaan_pengepakan_detail.is_active', 1);

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('permintaan_pengepakan_detail.jumlah_minta != 0');
		$this->db->where('permintaan_pengepakan_detail.is_active', 1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('permintaan_pengepakan_detail.jumlah_minta != 0');
		$this->db->where('permintaan_pengepakan_detail.is_active', 1);
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
		// die(dump($result->records));
		return $result; 
	}


	/**
	 * [fillable_add description]
	 * @return [type] [description]
	 */
	public function fillable_add()
	{
		return $this->_fillable_add;
	}
	/**
	 * [fillable_edit description]
	 * @return [type] [description]
	 */
	public function fillable_edit()
	{
		return $this->_fillable_edit;
	}

}

