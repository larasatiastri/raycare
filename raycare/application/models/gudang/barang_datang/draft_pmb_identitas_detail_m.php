<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Draft_pmb_identitas_detail_m extends MY_Model {

	protected $_table        = 'draft_pmb_identitas_detail';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

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
		$this->db->where('is_active',1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('is_active',1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('is_active',1);

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

	public function get_last_id()
	{
		$format = "SELECT MAX(draft_pmb_identitas_detail_id) as last_id FROM draft_pmb_identitas_detail";

		return $this->db->query($format);
	}

	public function update($identitas_id, $judul, $value)
	{
		$format = "UPDATE draft_pmb_identitas_detail SET identitas_id = $identitas_id, judul = $judul, value = $value";

		return $this->db->query($format);
	}

	public function get_item_identitas($id)
	{
		$format = "SELECT
					draft_pmb_identitas.draft_identitas_id as draft_identitas_id,
					draft_pmb_identitas.draft_pmb_actual_id,
					draft_pmb_actual.item_satuan_id,
					draft_pmb_identitas.jumlah,
					item_satuan.nama as satuan,
					draft_pmb_identitas_detail.draft_pmb_identitas_detail_id as id_detail,
					draft_pmb_identitas_detail.identitas_id,
					draft_pmb_identitas_detail.judul,
					draft_pmb_identitas_detail.`value`,
					identitas.tipe
					FROM
					draft_pmb_identitas
					LEFT JOIN draft_pmb_identitas_detail ON draft_pmb_identitas.draft_identitas_id = draft_pmb_identitas_detail.draft_pmb_identitas_id
					LEFT JOIN identitas ON draft_pmb_identitas_detail.identitas_id = identitas.id
					LEFT JOIN draft_pmb_actual ON draft_pmb_identitas.draft_pmb_actual_id = draft_pmb_actual.draft_pmb_actual_id
					LEFT JOIN item_satuan ON draft_pmb_actual.item_satuan_id = item_satuan.id
					WHERE
					draft_pmb_identitas.draft_identitas_id = $id
					";

		return $this->db->query($format);
	}
}

/* End of file draft_pmb_identitas_detail_m.php */
/* Location: ./application/models/gudang/barang_datang/draft_pmb_identitas_detail_m.php */