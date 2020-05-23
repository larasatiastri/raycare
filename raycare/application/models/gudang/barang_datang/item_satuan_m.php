<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Item_satuan_m extends MY_Model {

	protected $_table        = 'item_satuan';
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

	public function get_jumlah($item_id, $satuan_awal, $satuan_konvert)
	{
		$format = "SELECT *
					FROM item_satuan
					WHERE item_id =  '$item_id'
					AND parent_id BETWEEN $satuan_awal AND $satuan_konvert
					ORDER BY id";

		return $this->db->query($format);
	}

	public function get_jumlah_up_convert($item_id, $satuan_awal, $satuan_konvert)
	{
		$format = "SELECT *
					FROM item_satuan
					WHERE item_id =  '$item_id'
					AND id BETWEEN $satuan_awal AND $satuan_konvert
					ORDER BY id";

		return $this->db->query($format);
	}

	public function get_satuan_terkecil($item_id){

    	$format = "SELECT * FROM item_satuan
					WHERE item_id = '$item_id'
					AND id NOT IN (SELECT parent_id FROM item_satuan WHERE parent_id IS NOT NULL AND parent_id != '' AND item_id = '$item_id')";

		return $this->db->query($format);

    }

    public function get_data_konversi($item_id, $satuan_terkecil_id){

    	$format = "SELECT * FROM item_satuan WHERE id = '$satuan_terkecil_id'";
    	$return = $this->db->query($format)->result_array();

    	$data_konversi[] = $return[0];
    	$parent_id = $data_konversi[0]['parent_id'];

    	$selectable = false;
    	if ($data_konversi[0]['is_primary']) {
    		$selectable = true;
    	}

    	$nilai_konversi = NULL;
    	if ($selectable) {
    		if ($data_konversi[0]['is_primary']) {
    			$nilai_konversi = 1;
    		}
    		else{
    			$nilai_konversi = $nilai_konversi * $data_konversi[0]['jumlah'];
    		}
    	}
	    	
	    $data_konversi[0]['selectable'] = $selectable;
	    $data_konversi[0]['nilai_konversi'] = $nilai_konversi;


	    $i = 1;
    	while ($parent_id != NULL) 
    	{
			$format          = "SELECT * FROM item_satuan WHERE id = '$parent_id'";
			$return          = $this->db->query($format)->result_array();

			$data_konversi[] = $return[0];
			$parent_id       = $data_konversi[$i]['parent_id'];

			if ($data_konversi[$i]['is_primary']) {
	    		$selectable = true;
	    	}

	    	if ($selectable) {
	    		if ($data_konversi[$i]['is_primary']) {
	    			$nilai_konversi = 1;
	    		}
	    		else{
	    			$nilai_konversi = $nilai_konversi * $data_konversi[$i-1]['jumlah'];
	    		}
	    	}

	    	$data_konversi[$i]['selectable'] = $selectable;
	    	$data_konversi[$i]['nilai_konversi'] = $nilai_konversi;

	    	$i++;
    	}

		return $data_konversi;

    }
}

