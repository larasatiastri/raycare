<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Item_m extends MY_Model {

	protected $_table        = 'item';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'request_item_detail.id'     			=> 'id', 
		'request_item_detail.request_item_id'   => 'request_item_id', 
		'request_item_detail.item_id'   		=> 'item_id', 
		'request_item_detail.item_satuan_id'    => 'item_satuan_id', 
		'request_item_detail.jumlah' 			=> 'jumlah', 
		'item.kode'                  			=> 'kode', 
		'item.nama'                  			=> 'nama',
		'item_satuan.nama'           			=> 'nama_satuan',
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
		$join1 = array('request_item_detail', $this->_table.'.id = request_item_detail.item_id');
		$join2 = array('request_item','request_item.id = request_item_detail.request_item_id');
		$join3 = array('item_satuan','request_item_detail.item_satuan_id = item_satuan.id');
		$join_tables = array($join1, $join2, $join3);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('request_item_detail.request_item_id', $id);
		// $this->db->where('request_item_detail.item_id', $item_id);
		// $this->db->where('request_item_detail.item_satuan_id', $item_satuan_id);
		// $this->db->where('item_satuan.id = request_item_detail.item_satuan_id');
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('request_item_detail.request_item_id', $id);
		// $this->db->where('request_item_detail.item_id', $item_id);
		// $this->db->where('request_item_detail.item_satuan_id', $item_satuan_id);
		// $this->db->where('item_satuan.id = request_item_detail.item_satuan_id');

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('request_item_detail.request_item_id', $id);
		// $this->db->where('request_item_detail.item_id', $item_id);
		// $this->db->where('request_item_detail.item_satuan_id', $item_satuan_id);
		// $this->db->where('item_satuan.id = request_item_detail.item_satuan_id');

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

	public function get_data_gudang()
	{
		$format = "SELECT id as id, informasi as nama_gudang
					FROM gudang
					WHERE is_active = 1";

		return $this->db->query($format);
	}

	

}

/* End of file cabang_m.php */
/* Location: ./application/models/gudang/gudang_m.php */