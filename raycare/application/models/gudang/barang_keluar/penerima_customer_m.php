<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Penerima_customer_m extends MY_Model {

	protected $_table        = 'customer';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'customer.id'					=> 'id',
		'customer.kode'				=> 'kode_customer',
		'customer.nama'				=> 'nama_customer',
		'customer.orang_bersangkutan'	=> 'orang_bersangkutan',
		'customer_telepon.nomor'		=> 'no_telp',
		'customer_alamat.alamat'		=> 'alamat',
		'customer_alamat.kota_id'		=> 'kota',
		'(SELECT region.nama FROM region WHERE region.tipe = 5 AND customer_alamat.kelurahan_id = region.id )' => 'kelurahan',
		'(SELECT region.nama FROM region WHERE region.tipe = 4 AND customer_alamat.kecamatan_id = region.id )' => 'kecamatan',
		'(SELECT region.nama FROM region WHERE region.tipe = 3 AND customer_alamat.kota_id = region.id )' => 'kota',
		'(SELECT region.nama FROM region WHERE region.tipe = 2 AND customer_alamat.propinsi_id = region.id )' => 'propinsi',
		'(SELECT region.nama FROM region WHERE region.tipe = 1 AND customer_alamat.negara_id = region.id )' => 'negara',
		
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
		$join1 = array('customer_alamat', $this->_table.'.id = customer_alamat.customer_id');
		$join2 = array('customer_telepon', $this->_table.'.id = customer_telepon.customer_id');
		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('customer_telepon.is_primary',1);
		$this->db->where($this->_table.'.is_active',1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('customer_telepon.is_primary',1);
		$this->db->where($this->_table.'.is_active',1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('customer_telepon.is_primary',1);
		$this->db->where($this->_table.'.is_active',1);

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
}

/* End of file cabang_m.php */
/* Location: ./application/models/master/cabang_m.php */