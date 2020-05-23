<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory_api_history_detail_m extends MY_Model {

	protected $_table        = 'inventory_api_history_detail';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		
	);

	

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'inventory_api_history_detail.created_date'             => 'tanggal',
		'item.kode'                                         => 'kode_item',
		'item.id'                                         	=> 'id_item',
		'item.nama'                                         => 'nama_item',
		'item_satuan.nama'                                  => 'nama_satuan',
		'item_satuan.id'                                  	=> 'id_item_satuan',
		'SUM(inventory_api_history_detail.change_stock * (-1))' => 'jumlah',
		'inventory_api_history_detail.item_id'                  => 'item_id',
		'inventory_api_history_detail.item_satuan_id'           => 'item_satuan_id',
		'(select SUM(jumlah) from inventory_api where item_id = item.id and item_satuan_id = item_satuan.id GROUP BY item_id, item_satuan_id)' => 'stock'
	
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable_report($tgl_awal=null,$tgl_akhir=null,$tipe,$gudang_id,$kategori=null, $sub_kategori=null, $item_id=null)
	{		
		$join1 = array('item', $this->_table.'.item_id = item.id', 'left');
		$join2 = array('item_satuan', $this->_table.'.item_satuan_id = item_satuan.id', 'left');
		$join3 = array('item_sub_kategori', 'item.item_sub_kategori = item_sub_kategori.id');
		$join4 = array('item_kategori', 'item_sub_kategori.item_kategori_id = item_kategori.id');
		$join_tables = array($join1, $join2,$join3,$join4);

		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = $this->_table.'.created_date';
		$params['sort_dir'] = 'desc';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		if($tipe == 1){
			$this->db->where($this->_table.'.change_stock <= ',0);
		}if($tipe == 2){
			$this->db->where($this->_table.'.change_stock > ',0);
		}


		if($kategori=='null' && $sub_kategori=='' && $item_id == '')
		{
			$wheres = array('');
		}
		else if($kategori!=null && $sub_kategori!=null && $kategori!='null' && $sub_kategori!='null')
		{
			$wheres = array(
				'item.item_sub_kategori' => $sub_kategori,
				'item_sub_kategori.item_kategori_id' => $kategori
			);
		}
		else if($kategori!=null && $sub_kategori=='null')
		{
			$wheres = array(
				'item_sub_kategori.item_kategori_id' => $kategori
			);
		}	

		//die(dump($item_id));

		$this->db->where('date(inventory_api_history_detail.created_date) >= ',$tgl_awal);
		$this->db->where('date(inventory_api_history_detail.created_date) <= ',$tgl_akhir);
		$this->db->where('inventory_api_history_detail.gudang_id',$gudang_id);
		$this->db->where($wheres);
		if($item_id != '' && $item_id != 'null'){
			$item_array = explode('-', $item_id);
			$this->db->where_in($this->_table.'.item_id',$item_array);
		}
		$this->db->group_by($this->_table.'.item_id');
		$this->db->group_by($this->_table.'.item_satuan_id');
		$this->db->group_by('date(inventory_api_history_detail.created_date)');

		// dapatkan total row count;
		$query = $this->db->select('(1)')->get();
		$total_records = $query->num_rows();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		if($tipe == 1){
			$this->db->where($this->_table.'.change_stock <= ',0);
		}if($tipe == 2){
			$this->db->where($this->_table.'.change_stock > ',0);
		}
		$this->db->where('date(inventory_api_history_detail.created_date) >= ',$tgl_awal);
		$this->db->where('date(inventory_api_history_detail.created_date) <= ',$tgl_akhir);
		$this->db->where('inventory_api_history_detail.gudang_id',$gudang_id);
		$this->db->where($wheres);
		if($item_id != '' && $item_id != 'null'){
			$item_array = explode('-', $item_id);
			$this->db->where_in($this->_table.'.item_id',$item_array);
		}
		
		$this->db->group_by($this->_table.'.item_id');
		$this->db->group_by($this->_table.'.item_satuan_id');
		$this->db->group_by('date(inventory_api_history_detail.created_date)');

		// dapatkan total record filtered/search;
		$query = $this->db->select('(1)')->get();
		$total_display_records = $query->num_rows();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		if($tipe == 1){
			$this->db->where($this->_table.'.change_stock <= ',0);
		}if($tipe == 2){
			$this->db->where($this->_table.'.change_stock > ',0);
		}
		$this->db->where('date(inventory_api_history_detail.created_date) >= ',$tgl_awal);
		$this->db->where('date(inventory_api_history_detail.created_date) <= ',$tgl_akhir);
		$this->db->where('inventory_api_history_detail.gudang_id',$gudang_id);
		$this->db->where($wheres);
		if($item_id != '' && $item_id != 'null'){
			$item_array = explode('-', $item_id);
			$this->db->where_in($this->_table.'.item_id',$item_array);
		}
		$this->db->group_by($this->_table.'.item_id');
		$this->db->group_by($this->_table.'.item_satuan_id');
		$this->db->group_by('date(inventory_api_history_detail.created_date)');

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
		//die(dump($result->records));
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

