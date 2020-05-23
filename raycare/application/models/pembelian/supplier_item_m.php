<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Supplier_item_m extends MY_Model {

	protected $_table        = 'supplier_item';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'supplier_item.id'                    => 'id',
		'supplier_item.supplier_id'           => 'supplier_id',
		'supplier_item.item_id'               => 'item_id',
		'supplier_item.item_satuan_id'        => 'item_satuan_id',
		'supplier_item.is_supply'             => 'is_supply',
		'supplier_item.minimum_order'         => 'minimum_order',
		'supplier_item.kelipatan_order'       => 'kelipatan_order',
		'supplier_item.is_active'             => 'is_active',
		'supplier_harga_item.id'              => 'supplier_harga_item_id',
		'supplier_harga_item.harga'           => 'harga',
		'supplier_harga_item.tanggal_efektif' => 'tanggal_efektif',
		'item.kode'                           => 'item_kode',
		'item.nama'                           => 'item_nama',
		'item_satuan.nama'                    => 'satuan_nama',
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($supplier_id)
	{	
		$join1 = array('supplier_harga_item', 'supplier_item.id = supplier_harga_item.supplier_item_id', 'left');
		$join2 = array('item', 'supplier_item.item_id = item.id', 'left');
		$join3 = array('item_satuan', 'supplier_item.item_satuan_id = item_satuan.id', 'left');
		$join_tables = array($join1,$join2, $join3);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where_in('supplier_item.supplier_id',$supplier_id);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where_in('supplier_item.supplier_id',$supplier_id);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where_in('supplier_item.supplier_id',$supplier_id);

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
	//	  die(dump($result->records));
		return $result; 
	}

	public function get_data_harga($supplier_id,$item_id,$item_satuan_id,$date)
	{
		$this->db->select('supplier_item.id, supplier_harga_item.id as id_harga, supplier_harga_item.harga, supplier_harga_item.tanggal_efektif');
		$this->db->join('supplier_harga_item', 'supplier_item.id = supplier_harga_item.supplier_item_id', 'left');
		$this->db->where('supplier_id', $supplier_id);
		$this->db->where('item_id', $item_id);
		$this->db->where('item_satuan_id', $item_satuan_id);
		$this->db->where('date(supplier_harga_item.tanggal_efektif) <=', $date);
		$this->db->order_by('supplier_harga_item.tanggal_efektif','desc');

		return $this->db->get($this->_table);
	}

	public function get_data($item_id, $supplier_id)
	{
		$this->db->where('item_id', $item_id);
		$this->db->where('supplier_id', $supplier_id);
		$this->db->order_by('id','desc');

		return $this->db->get($this->_table);
	}

	public function get_harga_terbaru($item_id, $item_satuan_id)
	{
		$this->db->where('item_id', $item_id);
		$this->db->where('item_satuan_id', $item_satuan_id);
		$this->db->order_by('tanggal_efektif','desc');

		return $this->db->get($this->_table);
	}


}

/* End of file cabang_m.php */
/* Location: ./application/models/gudang/gudang_m.php */