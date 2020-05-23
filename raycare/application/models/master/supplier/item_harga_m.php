<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Item_harga_m extends MY_Model {

	protected $_table      = 'item_harga';
	protected $_timestamps = true;
	protected $_order_by   = 'id';

	private $_fillable = array(
	
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias

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

		$join_tables = array();

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active', 1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active', 1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active', 1);

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

	public function get_harga_item($item, $satuan)
	{
		$cabang_id = $this->session->userdata('cabang_id');
		$cols = array(
            'item_harga.harga',
        );
		$result = $this->db
            ->select($cols)
            ->from('item_harga')
            ->where('item_harga.item_satuan_id', $satuan)
            ->where('item_harga.item_id', $item)
            ->where('item_harga.tanggal <=', date('Y-m-d H:i:s'))
            ->order_by('item_harga.tanggal', 'desc')
            ->where('item_harga.cabang_id', $cabang_id)
            ->get();

        return $result;
	}

}

/* End of file supplier_m.php */
/* Location: ./application/models/supplier_m.php */