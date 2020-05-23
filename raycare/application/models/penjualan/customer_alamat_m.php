<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer_alamat_m extends MY_Model {

	protected $_table        = 'customer_alamat';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		
		'customer_alamat.id'            => 'id',
		'customer_alamat.alamat'        => 'alamat',
		'inf_lokasi.lokasi_kode'        => 'lokasi_kode',
		'inf_lokasi.nama_kelurahan'     => 'nama_kelurahan',
		'inf_lokasi.nama_kecamatan'     => 'nama_kecamatan',
		'inf_lokasi.nama_kabupatenkota' => 'nama_kabupatenkota',
	);

	public function __construct()
	{
		parent::__construct();
	}


	public function get_datatable($customer_id)
	{	

		$join1 = array('inf_lokasi', $this->_table.'.kode_lokasi = inf_lokasi.lokasi_kode', 'left');
		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('customer_alamat.customer_id', $customer_id);

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('customer_alamat.customer_id', $customer_id);
		
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('customer_alamat.customer_id', $customer_id);

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

	public function get_datatable_penjualan($customer_id, $tgl_kirim)
	{	

		$join1 = array('inf_lokasi', $this->_table.'.kode_lokasi = inf_lokasi.lokasi_kode', 'left');
		$join2 = array('penjualan', 'penjualan.tujuan_id = customer_alamat.id');
		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('penjualan.customer_id', $customer_id);
		$this->db->where('penjualan.tanggal_kirim', $tgl_kirim);

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('penjualan.customer_id', $customer_id);
		$this->db->where('penjualan.tanggal_kirim', $tgl_kirim);
		
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('penjualan.customer_id', $customer_id);
		$this->db->where('penjualan.tanggal_kirim', $tgl_kirim);

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

	public function get_data($tujuan_id){

		$format = "SELECT
						customer_alamat.id,
						customer_alamat.alamat,
						customer_alamat.kode_lokasi,
						inf_lokasi.nama_kelurahan,
						inf_lokasi.nama_kecamatan,
						inf_lokasi.nama_kabupatenkota,
						inf_lokasi.nama_propinsi
					FROM
						customer_alamat
					JOIN inf_lokasi ON inf_lokasi.lokasi_kode = customer_alamat.kode_lokasi
					WHERE
						customer_alamat.id = ".$tujuan_id." ";

		return $this->db->query($format);

	}


	public function get_alamat($customer_id){

		$this->db->select('customer_alamat.subjek_id AS subjek, customer_alamat.alamat AS alamat, customer_alamat.rt_rw AS rt_rw, customer_alamat.kode_pos AS kode_pos, 
							customer_alamat.kode_lokasi AS kode_lokasi, inf_lokasi.lokasi_nama AS lokasi_nama, inf_lokasi.nama_propinsi AS nama_propinsi, 
							inf_lokasi.nama_kabupatenkota AS nama_kabupatenkota, inf_lokasi.nama_kecamatan AS nama_kecamatan, inf_lokasi.nama_kelurahan AS nama_kelurahan');
		
		$this->db->join('inf_lokasi', $this->_table.'.kode_lokasi = inf_lokasi.lokasi_kode');

		$this->db->where('id', $customer_id);
		$this->db->where('is_active', 1);
		// $this->db->where('is_primary', 1); 

		return $this->db->get($this->_table)->result_array();
	}

}
