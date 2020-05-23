<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Item_m extends MY_Model {

	protected $_table        = 'item';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		
	);

	

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'item.id'                => 'id',
		'item.kode'              => 'item_kode',
		'item.nama'              => 'item_nama',
		'item_satuan.nama'       => 'satuan',
		'item_satuan.jumlah'     => 'jumlah',
		'item_satuan.is_primary' => 'is_primary',
		'item.is_active'         => 'is_active',
		'item_harga.harga'		 => 'item_harga'
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
		$join1 = array('item_satuan', $this->_table.'.id = item_satuan.item_id');
		$join2 = array('item_harga', 'item_satuan.id = item_harga.item_satuan_id');
		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('item_satuan.is_primary', 1);
		$this->db->where('item_harga.tanggal <=', date('Y-m-d'));
		$this->db->group_by('item.id');

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('item_satuan.is_primary', 1);
		$this->db->where('item_harga.tanggal <=', date('Y-m-d'));
		$this->db->group_by('item.id');



		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('item_satuan.is_primary', 1);
		$this->db->where('item_harga.tanggal <=', date('Y-m-d'));
		$this->db->group_by('item.id');
		

		

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

