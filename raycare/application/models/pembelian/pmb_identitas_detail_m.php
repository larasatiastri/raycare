<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pmb_identitas_detail_m extends MY_Model {

	protected $_table        = 'pmb_identitas_detail';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'item.nama'		=> 'item_nama',
		'item.kode'		=> 'item_kode',	
		'item_satuan.nama'	=> 'item_satuan',
		'pmb_detail.jumlah_diterima'	=> 'item_jumlah',
		'pmb_detail.id'	=> 'id_detail',
		'pmb.id'	=> 'id',
		'pmb_detail.harga_beli'		=> 'item_harga'
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($id)
	{	
		$join1 = array('pmb', $this->_table.'.pmb_id = pmb.id');
		$join2 = array('item', $this->_table.'.item_id = item.id');
		$join3 = array('item_satuan', $this->_table.'.item_satuan_id = item_satuan.id');
		$join_tables = array($join1, $join2, $join3);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('pmb.id', $id);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('pmb.id', $id);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('pmb.id', $id);
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

/* End of file cabang_m.php */
/* Location: ./application/models/master/cabang_m.php */