<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Penjualan_m extends MY_Model {

	protected $_table        = 'penjualan';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(

		//column di table  => alias
		'penjualan.no_penjualan'   => 'no_penjualan', 
		'cabang.nama'              => 'customer_nama', 
		'cabang.kode'              => 'customer_kode', 
		'penjualan.tanggal_pesan'  => 'tanggal_pesan',
		'penjualan.tanggal_kirim'  => 'tanggal_kirim',
		'penjualan.keterangan'     => 'keterangan',
		'penjualan.status'         => 'status',
		'penjualan_cetak.no_cetak' => 'no_cetak', 
		'penjualan.id'             => 'id',
	);

	protected $datatable_columns_customer = array(

		//column di table  => alias
		'penjualan.no_penjualan'   => 'no_penjualan', 
		'customer.nama'            => 'customer_nama', 
		'customer.kode'            => 'customer_kode', 
		'penjualan.tanggal_pesan'  => 'tanggal_pesan',
		'penjualan.tanggal_kirim'  => 'tanggal_kirim',
		'penjualan.keterangan'     => 'keterangan',
		'penjualan.status'         => 'status',
		'penjualan_cetak.no_cetak' => 'no_cetak', 
		'penjualan.id'             => 'id',
	);

	public function __construct()
	{
		parent::__construct();
	}


	public function get_datatable($type, $history)
	{	
		$join1 = array('cabang', $this->_table.'.customer_id = cabang.id', 'left');
		$join2 = array('penjualan_cetak', $this->_table.'.id = penjualan_cetak.penjualan_id', 'left');
		$join_tables = array($join1, $join2);

		$wheres = array(
			'penjualan.is_active'     => 1,
			'penjualan.tipe_customer' => $type,
		);

		$status = array(3,4,5);
		if ($history) 
		{
			$status = array(6,7);
		}

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($wheres);
		$this->db->where_in('status', $status);
		$this->db->group_by('penjualan.id');

		// dapatkan total row count;
		// $total_records = $this->db->count_all_results();
		$query = $this->db->select('(1)')->get();
		$total_records = $query->num_rows();

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($wheres);
		$this->db->where_in('status', $status);
		$this->db->group_by('penjualan.id');

		// dapatkan total record filtered/search;
		$query = $this->db->select('(1)')->get();
		$total_display_records = $query->num_rows();
		// $total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($wheres);
		$this->db->where_in('status', $status);
		$this->db->group_by('penjualan.id');

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

	public function get_datatable2($type, $history)
	{
		$join1 = array('customer', $this->_table.'.customer_id = customer.id', 'left');
		$join2 = array('penjualan_cetak', $this->_table.'.id = penjualan_cetak.penjualan_id', 'left');
		$join_tables = array($join1, $join2);

		$wheres = array(
			'penjualan.is_active'     => 1,
			'penjualan.tipe_customer' => $type,
		);

		$status = array(3,4,5);
		if ($history) 
		{
			$status = array(6,7);
		}

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($wheres);
		$this->db->where_in('status', $status);
		$this->db->group_by('penjualan.id');

		// dapatkan total row count;
		// $total_records = $this->db->count_all_results();
		$query = $this->db->select('(1)')->get();
		$total_records = $query->num_rows();

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($wheres);
		$this->db->where_in('status', $status);
		$this->db->group_by('penjualan.id');

		// dapatkan total record filtered/search;
		// $total_display_records = $this->db->count_all_results();
		$query = $this->db->select('(1)')->get();
		$total_display_records = $query->num_rows();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($wheres);
		$this->db->where_in('status', $status);
		$this->db->group_by('penjualan.id');

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

	public function get_no_penjualan()
	{
		$format = "SELECT MAX(SUBSTRING(`no_penjualan`,5,3)) AS max_no_penjualan FROM `penjualan` WHERE RIGHT(`no_penjualan`,4) = DATE_FORMAT(NOW(), '%Y');";	
		return $this->db->query($format);
	}

	public function get_max_id_penjualan()
	{
		$format = "SELECT MAX(SUBSTRING(`id`,12,4)) AS max_id FROM `penjualan` WHERE SUBSTR(`id`,4,7) = DATE_FORMAT(NOW(), '%m-%Y');";	
		return $this->db->query($format);
	}

	public function get_penjualan_cetak($penjualan_id)
	{
		$sql = "SELECT penjualan_cetak.no_cetak, `user`.nama, penjualan_cetak.created_date
				FROM penjualan_cetak
				JOIN `user` ON `user`.id = penjualan_cetak.created_by
				WHERE penjualan_cetak.penjualan_id = ".$penjualan_id." ";	
		
		return $this->db->query($sql);
	}
	
}

