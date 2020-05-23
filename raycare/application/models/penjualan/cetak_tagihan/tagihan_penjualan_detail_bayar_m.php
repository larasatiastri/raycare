<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tagihan_penjualan_detail_bayar_m extends MY_Model {

	protected $_table      = 'tagihan_penjualan_detail_bayar';
	protected $_timestamps = true;
	protected $_order_by   = 'id';

	private $_fillable = array(
	
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		
	);

	function __construct()
	{
		parent::__construct();
	}

		/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable()
	{

		// $join1 = array('customer_alamat', 'customer.id = customer_alamat.customer_id','left');
		// $join2 = array('region', 'customer_alamat.kota_id = region.id','left');
		// $join3 = array('customer_telepon', 'customer_telepon.customer_id = customer.id');
		// $join_tables = array($join1, $join2, $join3);
		$join_tables = array();

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		// $this->db->where($this->_table.'.is_active', 1);
		// $this->db->where('customer_alamat.is_primary', 1);
		// $this->db->where('customer_telepon.is_primary', 1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		// $this->db->where($this->_table.'.is_active', 1);
		// $this->db->where('customer_alamat.is_primary', 1);
		// $this->db->where('customer_telepon.is_primary', 1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		// $this->db->where($this->_table.'.is_active', 1);
		// $this->db->where('customer_alamat.is_primary', 1);
		// $this->db->where('customer_telepon.is_primary', 1);

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

	/**
	 * [fillable_add description]
	 * @return [type] [description]
	 */
	
}

/* End of file Tagihan_penjualan_detail_bayar_m.php */
/* Location: ./application/models/Tagihan_penjualan_detail_bayar_m.php */