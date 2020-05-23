<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pmb_detail_m extends MY_Model {

	protected $_table        = 'pmb_detail';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		
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

	public function get_pembelian_id($id){
		$format = "SELECT
					pmb_detail.pmb_id,
					pmb_po_detail.pmb_detail_id,
					pmb_po_detail.po_detail_id,
					pembelian_detail.pembelian_id
					FROM
					pmb_detail
					LEFT JOIN pmb_po_detail ON pmb_detail.id = pmb_po_detail.pmb_detail_id
					LEFT JOIN pembelian_detail ON pmb_po_detail.po_detail_id = pembelian_detail.id
					WHERE
					pmb_detail.pmb_id = '$id'
					GROUP BY pembelian_detail.pembelian_id
					ORDER BY
					pembelian_detail.pembelian_id ASC
					";

		return $this->db->query($format);
	}

	public function get_data_masuk($pmb_id){
		$format = "SELECT pmb_detail.pmb_id,
					pmb_detail.item_id as item_id,
					pmb_detail.bn_sn_lot as bn_sn_lot,
					pmb_detail.expire_date as expire_date,
					pmb_detail.jumlah_diterima as jumlah,
					item.kode as kode_item,
					item.nama as nama_item,
					item_satuan.nama as nama_item_satuan
					FROM
					pmb_detail
					LEFT JOIN item ON pmb_detail.item_id = item.id
					LEFT JOIN item_satuan ON pmb_detail.item_satuan_id = item_satuan.id
					WHERE
					pmb_detail.pmb_id = '$pmb_id'
					GROUP BY pmb_detail.item_id, pmb_detail.item_satuan_id, pmb_detail.bn_sn_lot, pmb_detail.expire_date
					ORDER BY
					pmb_detail.id ASC
					";

		return $this->db->query($format);
	}
	public function get_data_masuk_item($pmb_id, $item_id){
		$format = "SELECT pmb_detail.id,
					pmb_detail.pmb_id,
					item.kode as kode_item,
					item.nama as nama_item,
					SUM(jumlah_diterima) as jumlah,
					item_satuan.nama as nama_item_satuan
					FROM
					pmb_detail
					LEFT JOIN item ON pmb_detail.item_id = item.id
					LEFT JOIN item_satuan ON pmb_detail.item_satuan_id = item_satuan.id
					WHERE
					pmb_detail.pmb_id = '$pmb_id'
					AND pmb_detail.item_id = '$item_id'
					GROUP BY pmb_detail.item_id, pmb_detail.item_satuan_id 
					ORDER BY pmb_detail.id ASC
					";

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

	public function get_max_id_pmb_detail()
	{
		$format = "SELECT MAX(SUBSTRING(`id`,14,4)) AS max_id FROM `pmb_detail` WHERE SUBSTR(`id`,6,7) = DATE_FORMAT(NOW(), '%m-%Y');";	
		return $this->db->query($format);
	}

	
}

