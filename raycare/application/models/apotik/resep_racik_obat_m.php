<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Resep_racik_obat_m extends MY_Model {

	protected $_table        = 'resep_racik_obat';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		
	);

	

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'resep_racik_obat.id'         => 'id',
		'resep_racik_obat.nama'       => 'nama',
		'resep_racik_obat.keterangan' => 'keterangan',
		'resep_racik_obat.user_id'    => 'user_id',
		'resep_racik_obat.is_active'  => 'is_active',
		'(SELECT COUNT(*) FROM resep_racik_obat_detail WHERE resep_racik_obat_detail.resep_racik_obat_id = resep_racik_obat.id)' => 'jumlah_item'
	);

	protected $datatable_columns_info_item = array(
		//column di table  => alias
		'resep_racik_obat.id'                    => 'id',
		'resep_racik_obat.nama'                  => 'nama',
		'resep_racik_obat.keterangan'            => 'keterangan',
		'resep_racik_obat_detail.id'             => 'resep_racik_obat_detail_id',
		'resep_racik_obat_detail.item_id'        => 'item_id',
		'item.kode'                              => 'item_kode',
		'item.nama'                              => 'item_nama',
		'resep_racik_obat_detail.item_satuan_id' => 'item_satuan_id',
		'item_satuan.nama'                       => 'nama_satuan',
		'resep_racik_obat_detail.jumlah'         => 'jumlah'
	);

	protected $datatable_columns_item_digunakan = array(
		//column di table  => alias
		'resep_racik_obat.id'                    => 'id',
		'resep_racik_obat.nama'                  => 'nama',
		'resep_racik_obat_detail.item_id'        => 'item_id',
		'resep_racik_obat_detail.item_satuan_id' => 'item_satuan_id',
		'resep_racik_obat_detail.jumlah'         => 'jumlah',
		'item.kode'                              => 'item_kode',
		'item.nama'                              => 'item_nama',
		'item_satuan.nama'                       => 'satuan_nama',
		'item_harga.harga'                       => 'satuan_harga',
		'(SELECT COUNT(*) FROM resep_racik_obat_detail WHERE resep_racik_obat_detail.resep_racik_obat_id = resep_racik_obat.id)' => 'jumlah_item'
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
		$join = array('resep_racik_obat_detail', $this->_table.'.id = resep_racik_obat_detail.resep_racik_obat_id');
		$join_tables = array($join);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->group_by($this->_table.'.id');
		$this->db->where($this->_table.'.is_active', '1');
		

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->group_by($this->_table.'.id');
		$this->db->where($this->_table.'.is_active', '1');


		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->group_by($this->_table.'.id');
		$this->db->where($this->_table.'.is_active', '1');
		

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

	public function get_datatable_info_item($id)
	{		
		$join1 = array('resep_racik_obat_detail', $this->_table.'.id = resep_racik_obat_detail.resep_racik_obat_id');
		$join2 = array('item', 'item.id = resep_racik_obat_detail.item_id');
		$join3 = array('item_satuan', 'item_satuan.id = resep_racik_obat_detail.item_satuan_id');
		$join_tables = array($join1, $join2, $join3);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_info_item);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.id', $id);

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.id', $id);

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.id', $id);

		

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_info_item as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_info_item;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_datatable_item_digunakan($id)
	{		
		$join1 = array('resep_racik_obat_detail', $this->_table.'.id = resep_racik_obat_detail.resep_racik_obat_id');
		$join2 = array('item', 'item.id = resep_racik_obat_detail.item_id');
		$join3 = array('item_satuan', 'item_satuan.id = resep_racik_obat_detail.item_satuan_id');
		$join4 = array('item_harga', 'item_satuan.id = item_harga.item_satuan_id');
		$join_tables = array($join1, $join2, $join3, $join4);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_item_digunakan);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.id', $id);

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.id', $id);

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.id', $id);

		

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_item_digunakan as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_item_digunakan;
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

