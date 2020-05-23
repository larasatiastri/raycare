<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tagihan_penjualan_m extends MY_Model {

	protected $_table      = 'tagihan_penjualan';
	protected $_timestamps = true;
	protected $_order_by   = 'id';

	private $_fillable = array(
	
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'tagihan_penjualan.id' => 'id',
		'tagihan_penjualan.tanggal' => 'tanggal',
		'tagihan_penjualan.no_invoice' => 'no_invoice',
		'tagihan_penjualan.customer_id' => 'customer_id',
		'tagihan_penjualan.tipe_customer' => 'tipe_customer',
		'tagihan_penjualan.total' => 'total',
		// 'tagihan_penjualan.status_keuangan' => 'status_keuangan',
		'tagihan_penjualan.is_lunas' => 'is_lunas',
		'tagihan_penjualan.is_active' => 'is_active',
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

	public function get_nomor_invoice()
	{
		$format = "SELECT MAX(SUBSTRING(`no_invoice`,10,3)) AS max_nomor_invoice FROM `tagihan_penjualan` WHERE SUBSTRING(`no_invoice`,5,4) = DATE_FORMAT(NOW(), '%y%m')";	
		return $this->db->query($format);
	}

	public function get_nama_cabang($customer_id)
	{

		$format = "SELECT
						tagihan_penjualan.id AS id,
						tagihan_penjualan.tanggal AS tanggal,
						tagihan_penjualan.no_invoice AS no_invoice,
						tagihan_penjualan.customer_id AS customer_id,
						tagihan_penjualan.tipe_customer AS tipe_customer,
						tagihan_penjualan.total AS total,
						tagihan_penjualan.is_lunas AS is_lunas,
						tagihan_penjualan.is_active AS is_active,
						cabang.nama AS nama_cabang,
						cabang.kode AS kode_cabang
						FROM
						tagihan_penjualan
						LEFT JOIN cabang ON tagihan_penjualan.customer_id = cabang.id
						WHERE
						tagihan_penjualan.customer_id = $customer_id
						ORDER BY
							tagihan_penjualan.id ASC
						LIMIT 10
						";

		return $this->db->query($format);

	}

	public function get_nama_customer($customer_id)
	{

		$format = "SELECT
					tagihan_penjualan.id AS id,
					tagihan_penjualan.tanggal AS tanggal,
					tagihan_penjualan.no_invoice AS no_invoice,
					tagihan_penjualan.customer_id AS customer_id,
					tagihan_penjualan.tipe_customer AS tipe_customer,
					customer.nama AS nama_customer,
					customer.kode AS kode_customer
					FROM
					tagihan_penjualan
					LEFT JOIN customer ON tagihan_penjualan.customer_id = customer.id
					WHERE
					tagihan_penjualan.customer_id = $customer_id
					ORDER BY
						tagihan_penjualan.id ASC
					LIMIT 10
					";

		return $this->db->query($format);

	}

	/**
	 * [fillable_add description]
	 * @return [type] [description]
	 */
	
}

/* End of file Tagihan_penjualan_m.php */
/* Location: ./application/models/Tagihan_penjualan_m.php */