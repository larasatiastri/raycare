<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Supplier_tipe_pembayaran_m extends MY_Model {

	protected $_table        = 'supplier_tipe_pembayaran';
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

	function get_id($id)
	{	
		$this->db->where('id', $id);
		$this->db->order_by('id', 'desc');
		$query = $this->db->query('SELECT id, kode FROM cabang_');
		
		return $query->row();
		return $this->db->get($this->_table);
	}

	public function get_pembayaran($id)
	{
		$sql = "SELECT master_tipe_bayar.nama as nama,
						supplier_tipe_pembayaran.lama_tempo as lama_tempo,
						supplier_tipe_pembayaran.id as id
				FROM supplier_tipe_pembayaran,
						master_tipe_bayar
				WHERE supplier_tipe_pembayaran.tipe_bayar_id = master_tipe_bayar.id 
					AND supplier_tipe_pembayaran.supplier_id = $id
		";

		return $this->db->query($sql);
	}
}

/* End of file supplier_tipe_pembayaran_m.php */
/* Location: ./application/models/master/supplier_tipe_pembayaran_m.php */