<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Box_paket_m extends MY_Model {

	protected $_table        = 'box_paket';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'box_paket.nama'			=> 'box_paket_nama',
		'box_paket.is_active'		=> 'is_active',
		'box_paket.id'				=> 'id'
		
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
		// $join1 = array('box_paket_detail', $this->_table.'.box_paket_id = box_paket_detail.id');
		// $join2 = array('item', 'box_paket_detail.item_id = item.id');
		// $join3 = array('item_satuan', 'box_paket_detail.item_satuan_id = item_satuan.id');
		// $join_tables = array($join1, $join2, $join3);
		
		$join_tables = array();

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		// $this->db->where('pmb.id', $id);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		// $this->db->where('pmb.id', $id);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		// $this->db->where('pmb.id', $id);
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

	public function get_data_head($id)
	{
		$format = "SELECT
					pmb_identitas_detail.judul
					FROM
					pmb_detail ,
					pmb ,
					pmb_identitas ,
					pmb_identitas_detail
					WHERE
					pmb.id = pmb_detail.pmb_id AND
					pmb.id = $id AND
					pmb_detail.id = pmb_identitas.pmb_detail_id AND
					pmb_identitas.id = pmb_identitas_detail.pmb_identitas_id
					GROUP BY identitas_id
					";

		return $this->db->query($format);
	}
}

/* End of file box_paket_m.php */
/* Location: ./application/models/master/box_paket_m.php */