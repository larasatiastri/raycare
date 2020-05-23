<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pengeluaran_barang_detail_m extends MY_Model {

	protected $_table        = 'pengeluaran_barang_detail';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
	);

	private $_fillable_edit = array(
		
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		
		
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
		$join_tables = array();

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);

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

	public function get_data($pengeluaran_id)
	{
		$format = "SELECT
						pengeluaran_barang_detail.id,
						pengeluaran_barang_detail.pengeluaran_barang_id,
						pengeluaran_barang_detail.item_id,
						pengeluaran_barang_detail.item_satuan_id,
						pengeluaran_barang_detail.bn_sn_lot,
						pengeluaran_barang_detail.expire_date,
						item.kode as kode,
						item.nama as nama,
						item_satuan.nama as nama_satuan,
						SUM(pengeluaran_barang_detail.jumlah) as jumlah
						FROM
						pengeluaran_barang_detail 
						JOIN item ON pengeluaran_barang_detail.item_id = item.id 
						JOIN item_satuan ON pengeluaran_barang_detail.item_satuan_id = item_satuan.id
						WHERE pengeluaran_barang_detail.pengeluaran_barang_id = $pengeluaran_id
						GROUP BY pengeluaran_barang_detail.item_id,pengeluaran_barang_detail.item_satuan_id,pengeluaran_barang_detail.bn_sn_lot, pengeluaran_barang_detail.expire_date";

		return $this->db->query($format);
	}

	public function get_data_detail($pengeluaran_id, $item_id, $item_satuan_id)
	{
		$format = "SELECT
						pengeluaran_barang_detail.id,
						pengeluaran_barang_detail.pengeluaran_barang_id,
						pengeluaran_barang_detail.item_id,
						pengeluaran_barang_detail.item_satuan_id,
						pengeluaran_barang_detail.bn_sn_lot,
						pengeluaran_barang_detail.expire_date,
						item.kode as kode,
						item.nama as nama,
						item_satuan.nama as nama_satuan,
						pengeluaran_barang_detail.jumlah
						FROM
						pengeluaran_barang_detail JOIN item
						ON pengeluaran_barang_detail.item_id = item.id JOIN item_satuan
						ON pengeluaran_barang_detail.item_satuan_id = item_satuan.id
						WHERE pengeluaran_barang_detail.pengeluaran_barang_id = $pengeluaran_id
						AND pengeluaran_barang_detail.item_id = $item_id
						AND pengeluaran_barang_detail.item_satuan_id = $item_satuan_id";

		return $this->db->query($format);
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

