<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Penjualan_identitas_m extends MY_Model {

	protected $_table        = 'penjualan_identitas';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		
		//column di table         => alias
		'penjualan_detail.id'     => 'penjualan_id', 
		'penjualan_detail.jumlah' => 'jumlah', 
		'penjualan_detail.diskon' => 'diskon', 
		'item.id'                 => 'item_id', 
		'item.kode'               => 'item_kode', 
		'item.nama'               => 'item_nama', 
		'item_satuan.id'          => 'item_satuan_id', 
		'item_satuan.nama'        => 'item_satuan_nama', 
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($penjualan_id)
	{	
		$join1 = array('item', $this->_table.'.item_id = item.id');
		$join2 = array('item_satuan', $this->_table.'.item_satuan_id = item_satuan.id');
		$join_tables = array($join1, $join2);

		$wheres = array(
			'penjualan_detail.penjualan_id' => $penjualan_id
		);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($wheres);

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($wheres);

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
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

	public function get_data_item($id)
	{
		$this->db->select('penjualan_detail.id AS id, penjualan_detail.penjualan_id AS penjualan_id, item.id AS item_id, item.nama AS item_nama, item.kode AS item_kode, 
								item_satuan.id AS satuan_id, item_satuan.nama AS satuan, penjualan_detail.jumlah AS jumlah, penjualan_detail.diskon AS diskon');
		$this->db->join('item',$this->_table.'.item_id = item.id', 'left');
		$this->db->join('item_satuan',$this->_table.'.item_satuan_id = item_satuan.id', 'left');
		$this->db->where($this->_table.'.penjualan_id', $id);

		return $this->db->get($this->_table);
	}
}

