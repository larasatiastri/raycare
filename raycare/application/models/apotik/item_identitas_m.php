<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Item_identitas_m extends MY_Model {

	protected $_table        = 'item_identitas';
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

	public function data_item_identitas($item_id)
	{
		$format = "SELECT
					item_identitas.id,
					item_identitas.item_id,
					identitas.judul,
					identitas.tipe,
					identitas_detail.text,
					identitas_detail.`value`,
					identitas.maksimal_karakter,
					identitas_detail.id as identitas_detail_id,
					identitas.id as identitas_id
					FROM
					item_identitas
					LEFT JOIN identitas ON item_identitas.identitas_id = identitas.id
					LEFT JOIN identitas_detail ON identitas.id = identitas_detail.identitas_id
					WHERE
					item_identitas.item_id = $item_id
					GROUP BY item_identitas.id";

		return $this->db->query($format);
	}

	public function data_item_identitas_detail($item_id, $identitas_id)
	{
		$format = "SELECT
					item_identitas.id,
					item_identitas.item_id,
					identitas.judul,
					identitas.tipe,
					identitas_detail.text,
					identitas_detail.`value`,
					identitas.maksimal_karakter,
					identitas_detail.id AS identitas_detail_id,
					identitas.id AS identitas_id
					FROM
						item_identitas
					LEFT JOIN identitas ON item_identitas.identitas_id = identitas.id
					LEFT JOIN identitas_detail ON identitas.id = identitas_detail.identitas_id
					WHERE
					item_identitas.item_id = $item_id AND item_identitas.id = $identitas_id";

		return $this->db->query($format);
	}

	public function get_item_identitas($item_id)
	{
		$format = "SELECT
					item_identitas.id,
					item_identitas.item_id,
					item_identitas.identitas_id,
					identitas.judul,
					identitas.tipe,
					identitas.maksimal_karakter
					FROM
					item_identitas
					LEFT JOIN identitas ON item_identitas.identitas_id = identitas.id
					WHERE
					item_identitas.item_id = $item_id
					AND item_identitas.is_active = 1
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

}

