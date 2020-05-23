<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer_tipe_pembayaran_m extends MY_Model {

	protected $_table        = 'customer_tipe_pembayaran';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		
		'customer_tipe_pembayaran.id' => 'id',
	);

	public function __construct()
	{
		parent::__construct();
	}


	public function get_datatable()
	{	

		$join = array('item', $this->_table.'.item_id = item.id', 'right');
		$join_tables = array();

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();

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

		return $result; 
	}

	public function get_tipe_pembayaran($customer_id, $tipe_customer) {

		$this->db->select('customer_tipe_pembayaran.id AS id, customer_tipe_pembayaran.customer_id AS customer_id, customer_tipe_pembayaran.tipe_bayar_id AS tipe_bayar_id, 
							customer_tipe_pembayaran.lama_tempo AS lama_tempo, master_tipe_bayar.nama AS tipe_bayar');
		$this->db->join('master_tipe_bayar', $this->_table.'.tipe_bayar_id = master_tipe_bayar.id');
		$this->db->where(array('customer_tipe_pembayaran.customer_id' => $customer_id, 'tipe_customer' => $tipe_customer, 'customer_tipe_pembayaran.is_active' => 1));
		// $this->db->group_by('tipe_bayar_id');

		return $this->db->get($this->_table)->result_array();
	}

	public function get_tempo($customer_id, $tipe_bayar_id) {

		$sql = "SELECT
					customer_tipe_pembayaran.id AS id,
					customer_tipe_pembayaran.customer_id AS customer_id,
					customer_tipe_pembayaran.tipe_bayar_id AS tipe_bayar_id,
					customer_tipe_pembayaran.lama_tempo AS lama_tempo
				FROM
					customer_tipe_pembayaran
				JOIN master_tipe_bayar ON customer_tipe_pembayaran.tipe_bayar_id = master_tipe_bayar.id
				WHERE
					customer_tipe_pembayaran.customer_id = $customer_id
				AND tipe_bayar_id = $tipe_bayar_id
				AND customer_tipe_pembayaran.is_active = 1
				ORDER BY
					lama_tempo ASC";

		return $this->db->query($sql)->result_array();
	}

	public function get_data($id) {

		$sql = "SELECT
					customer_tipe_pembayaran.id AS id,
					customer_tipe_pembayaran.customer_id AS customer_id,
					customer_tipe_pembayaran.tipe_bayar_id AS tipe_bayar_id,
					customer_tipe_pembayaran.lama_tempo AS lama_tempo
				FROM
					customer_tipe_pembayaran
				JOIN master_tipe_bayar ON customer_tipe_pembayaran.tipe_bayar_id = master_tipe_bayar.id
				WHERE
					customer_tipe_pembayaran.customer_id = $customer_id
				AND customer_tipe_pembayaran.id = $id ";

		return $this->db->query($sql)->result_array();
	}

}
