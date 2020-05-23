<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cabang_telepon_m extends MY_Model {

	protected $_table        = 'cabang_telepon';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		
		'item.kode'                              => 'item_kode',
		'item.nama'                              => 'item_nama',
		'SUM(inventory.jumlah)'                  => 'stock',
		'pembelian_detail.jumlah_belum_diterima' => 'belum_diterima',
		'item_satuan.nama'                       => 'item_satuan',
		'item_satuan.id'                         => 'item_satuan_id',
		'item.id'                                => 'item_id',
	);

	public function __construct()
	{
		parent::__construct();
	}


	public function get_datatable()
	{	

		$join1 = array('item', $this->_table.'.item_id = item.id', 'right');
		$join2 = array('item_satuan', $this->_table.'.item_satuan_id = item_satuan.id', 'left');
		$join3 = array('pembelian_detail', $this->_table.'.item_id = pembelian_detail.item_id AND pembelian_detail.item_satuan_id = inventory.item_satuan_id', 'left');
		$join_tables = array($join1, $join2, $join3);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('item_satuan.is_primary', 1);
		$this->db->group_by('inventory.item_id');
		$this->db->group_by('inventory.item_satuan_id');

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('item_satuan.is_primary', 1);
		$this->db->group_by('inventory.item_id');
		$this->db->group_by('inventory.item_satuan_id');
		
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('item_satuan.is_primary', 1);
		$this->db->group_by('inventory.item_id');
		$this->db->group_by('inventory.item_satuan_id');

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

}
