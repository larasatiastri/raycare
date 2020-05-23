<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permintaan_biaya_bon_m extends MY_Model {

	protected $_table        = 'permintaan_biaya_bon';
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
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);

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

	public function get_invoice_user($user_id)
	{
		$this->db->select('permintaan_biaya_bon.id, permintaan_biaya_bon.permintaan_biaya_id, permintaan_biaya_bon.biaya_id, biaya.nama as nama_biaya, permintaan_biaya_bon.no_bon, permintaan_biaya_bon.total_bon, permintaan_biaya_bon.tgl_bon, permintaan_biaya_bon.isian_1, permintaan_biaya_bon.isian_2, permintaan_biaya_bon.keterangan, permintaan_biaya_bon.url, permintaan_biaya_bon.status, permintaan_biaya.status as status_permintaan_biaya');
		$this->db->join('permintaan_biaya', $this->_table.'.permintaan_biaya_id = permintaan_biaya.id');
		$this->db->join('biaya', $this->_table.'.biaya_id = biaya.id','left');
		$this->db->where('permintaan_biaya.status', 20);
		$this->db->where('permintaan_biaya.diminta_oleh_id', $user_id);
		$this->db->or_where('permintaan_biaya.status', 17);
		$this->db->where('permintaan_biaya.diminta_oleh_id', $user_id);

		return $this->db->get($this->_table);


	}
}

/* End of file Permintaan_biaya.php */
/* Location: ./application/models/keuangan/Permintaan_biaya.php */