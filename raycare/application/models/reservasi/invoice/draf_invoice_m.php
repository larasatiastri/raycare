<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Draf_invoice_m extends MY_Model {

	protected $_table      = 'draf_invoice';
	protected $_timestamps = true;
	protected $_order_by   = 'id';

	private $_fillable = array(
		'pasien_id',
		'dashboard_url', 
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'draf_invoice.created_date'   => 'tanggal',
		'draf_invoice.waktu_tindakan' => 'waktu',
		'draf_invoice.no_invoice'     => 'no_invoice',
		'draf_invoice.jenis_invoice'     	 => 'jenis_invoice',
		'draf_invoice.nama_penjamin'  => 'nama_penjamin',
		'draf_invoice.nama_pasien'    => 'nama_pasien',
		'user.nama'              => 'nama_resepsionis',
		'draf_invoice.harga'          => 'harga',
		'draf_invoice.sisa_bayar'     => 'sisa_bayar',
		'draf_invoice.pasien_id'      => 'pasien_id',
		'draf_invoice.penjamin_id'      => 'penjamin_id',
		'draf_invoice.status'     	 => 'status',
		'draf_invoice.id'             => 'id',
	);


	protected $datatable_columns_laporan = array(
		//column di table  => alias
		'draf_invoice.id'             => 'id',
		'draf_invoice.created_date'   => 'tanggal',
		'draf_invoice.waktu_tindakan' => 'waktu',
		'draf_invoice.no_invoice'     => 'no_invoice',
		'user.nama'              => 'nama_resepsionis',
		'draf_invoice.nama_penjamin'  => 'nama_penjamin',
		'pasien.no_member'            => 'no_member',
		'pasien.nama'            => 'nama_pasien',
		'draf_invoice.harga'          => 'harga',
		'draf_invoice.pasien_id'      => 'pasien_id',
		'draf_invoice.sisa_bayar'     => 'sisa_bayar',
		'draf_invoice.penjamin_id'      => 'penjamin_id',
		'draf_invoice.status'     	 => 'status',
		'draf_invoice.jenis_invoice'     	 => 'jenis_invoice',
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
		
		$join1 = array("user", $this->_table . '.created_by = user.id', 'left');

		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = $this->_table.'.no_invoice';
		$params['sort_dir'] = 'desc';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active', 1);
		// $this->db->where($this->_table.'.pasien_id', $pasien_id);
		// $this->db->where('pembayaran_alamat.is_primary', 1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active', 1);
		// $this->db->where($this->_table.'.pasien_id', $pasien_id);
		// $this->db->where('pembayaran_alamat.is_primary', 1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active', 1);
		// $this->db->where($this->_table.'.pasien_id', $pasien_id);
		// $this->db->where('pembayaran_alamat.is_primary', 1);

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

	public function get_id_draf()
	{
		$format = "SELECT MAX(RIGHT(`id`,4)) AS max_id FROM `draf_invoice` WHERE SUBSTRING(`id`,4,7) = DATE_FORMAT(NOW(), '%m-%Y')";	
		return $this->db->query($format);
	}

	
	
}
