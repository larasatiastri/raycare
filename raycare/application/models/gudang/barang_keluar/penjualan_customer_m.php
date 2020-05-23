<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Penjualan_customer_m extends MY_Model {

	protected $_table        = 'customer';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		
		'customer.kode'                 => 'kode_customer',
		'customer.nama'                 => 'nama_customer',
		'customer.orang_bersangkutan'   => 'orang_bersangkutan',
		'customer_alamat.alamat'        => 'alamat',
		'inf_lokasi.nama_kelurahan'     => 'nama_kelurahan',
		'inf_lokasi.nama_kecamatan'     => 'nama_kecamatan',
		'inf_lokasi.nama_kabupatenkota' => 'nama_kabupatenkota',
		'customer_telepon.nomor'        => 'no_telp',
		'customer_alamat.id'            => 'customer_alamat_id',
		'customer.id'                   => 'id',
	);

	public function __construct()
	{
		parent::__construct();
	}


	public function get_datatable()
	{	
		$join1 = array('customer_alamat', $this->_table.'.id = customer_alamat.customer_id');
		$join2 = array('customer_telepon', $this->_table.'.id = customer_telepon.customer_id');
		$join3 = array('inf_lokasi', 'customer_alamat.kode_lokasi = inf_lokasi.lokasi_kode', 'left');
		$join_tables = array($join1, $join2, $join3);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('customer_telepon.is_primary',1);
		$this->db->where('customer_alamat.is_primary',1);
		$this->db->where($this->_table.'.is_active',1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('customer_telepon.is_primary',1);
		$this->db->where('customer_alamat.is_primary',1);
		$this->db->where($this->_table.'.is_active',1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('customer_telepon.is_primary',1);
		$this->db->where('customer_alamat.is_primary',1);
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

		return $result; 
	}
}
