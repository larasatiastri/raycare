<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Item_spesifikasi_m extends MY_Model {

	protected $_table        = 'item_spesifikasi';
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

	public function get_data_item_spesifikasi($item_id, $spesifikasi_id){
		$format = " SELECT
		item_spesifikasi.id,
		item_spesifikasi.item_id,
		item_spesifikasi.spesifikasi_id,
		item_spesifikasi.tipe,
		item_spesifikasi.judul,
		item_spesifikasi_detail.`value`,
		item_spesifikasi_detail.id as spesifikasi_detail_id
		FROM
		item_spesifikasi
		LEFT JOIN item_spesifikasi_detail ON item_spesifikasi.id = item_spesifikasi_detail.item_spesifikasi_id
		WHERE item_spesifikasi.item_id = $item_id AND item_spesifikasi.spesifikasi_id = $spesifikasi_id ";
		return $this->db->query($format);
	}

	public function get_data_item_spesifikasi_by_val($item_id, $spesifikasi_id, $value){
		$format = " SELECT
		item_spesifikasi.id,
		item_spesifikasi.item_id,
		item_spesifikasi.spesifikasi_id,
		item_spesifikasi.tipe,
		item_spesifikasi.judul,
		item_spesifikasi_detail.`value`,
		item_spesifikasi_detail.id as spesifikasi_detail_id
		FROM
		item_spesifikasi
		LEFT JOIN item_spesifikasi_detail ON item_spesifikasi.id = item_spesifikasi_detail.item_spesifikasi_id
		WHERE item_spesifikasi.item_id = $item_id AND item_spesifikasi.spesifikasi_id = $spesifikasi_id AND value = $value";
		return $this->db->query($format);
	}
	
	public function get_data_spesifikasi_detail($spesifikasi_id){
		//di pakai untuk controler pasien/show_claim
		$format = " SELECT
					spesifikasi.id,
					spesifikasi.judul,
					spesifikasi.tipe,
					spesifikasi.maksimal_karakter,
					spesifikasi.is_active,
					spesifikasi_detail.text,
					spesifikasi_detail.`value`
					FROM
					spesifikasi
					LEFT JOIN spesifikasi_detail ON spesifikasi.id = spesifikasi_detail.spesifikasi_id
					WHERE spesifikasi.id = $spesifikasi_id";

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

