<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembelian_m extends MY_Model {

	protected $_table        = 'pembelian';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'pembelian.id' => 'id',
		'pembelian.no_pembelian' => 'no_pembelian',
		'pembelian.tanggal_pesan' => 'tanggal_pesan',
		'pembelian.tanggal_kadaluarsa' => 'tanggal_kadaluarsa',
		'pembelian.tanggal_kirim' => 'tanggal_kirim',
		'pembelian.keterangan' => 'keterangan',
		'pembelian.supplier_id' => 'supplier_id',
		'pembelian.`status`' => 'status',
		'supplier.nama' => 'supplier_nama',
		'supplier.kode' => 'supplier_kode'
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
		$join1 = array('supplier', 'pembelian.supplier_id = supplier.id', 'left');
		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('pembelian.is_active',1);
		$this->db->where('pembelian.status',3);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('pembelian.is_active',1);
		$this->db->where('pembelian.status',3);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('pembelian.is_active',1);
		$this->db->where('pembelian.status',3);

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

	public function get_data_gudang()
	{
		$format = "SELECT id as id, informasi as nama_gudang
					FROM gudang
					WHERE is_active = 1";

		return $this->db->query($format);
	}

}

/* End of file cabang_m.php */
/* Location: ./application/models/gudang/gudang_m.php */