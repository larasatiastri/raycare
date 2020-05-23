<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pengajuan_pembayaran_kasbon_m extends MY_Model {

	protected $_table        = 'pengajuan_pembayaran_kasbon';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'pengajuan_pembayaran_kasbon.id'      => 'id', 
		'pengajuan_pembayaran_kasbon.tanggal' => 'tanggal',
		'pengajuan_pembayaran_kasbon.subjek'  => 'subjek',
		'pengajuan_pembayaran_kasbon.nominal' => 'nominal',
		'pengajuan_pembayaran_kasbon.no_cek'  => 'no_cek',
		'pengajuan_pembayaran_kasbon.status'  => 'status',
		'bank.nob'                            => 'nama_bank',
		'bank.acc_name'                       => 'acc_name',
		'bank.acc_number'                     => 'acc_number'
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
		$join1 = array('bank', $this->_table.'.bank_id = bank.id', 'LEFT');
		
		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by']="pengajuan_pembayaran_kasbon.id";
		$params['sort_dir']="desc";

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('status <=', 2);
		$this->db->where('pengajuan_pembayaran_kasbon.is_active', 1);
		$this->db->or_where('status', 4);
		$this->db->where('pengajuan_pembayaran_kasbon.is_active', 1);
		
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('status <=', 2);
		$this->db->where('pengajuan_pembayaran_kasbon.is_active', 1);
		$this->db->or_where('status', 4);
		$this->db->where('pengajuan_pembayaran_kasbon.is_active', 1);
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();
		$this->db->where('status <=', 2);
		$this->db->where('pengajuan_pembayaran_kasbon.is_active', 1);
		$this->db->or_where('status', 4);
		$this->db->where('pengajuan_pembayaran_kasbon.is_active', 1);
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
	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable_history()
	{	
		$join1 = array('bank', $this->_table.'.bank_id = bank.id', 'LEFT');
		
		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by']="pengajuan_pembayaran_kasbon.id";
		$params['sort_dir']="desc";

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('status', 5);
		$this->db->or_where('status', 3);
		
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();

		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('status', 5);
		$this->db->or_where('status', 3);
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();
		$this->db->where('status', 5);
		$this->db->or_where('status', 3);
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

	public function get_datatable_persetujuan()
	{	
		$join1 = array('bank', $this->_table.'.diminta_oleh_id = bank.id', 'LEFT');
		// $join2 = array('pengajuan_pembayaran_kasbon_cetak', $this->_table.'.id = pengajuan_pembayaran_kasbon_cetak.pengajuan_pembayaran_kasbon_id', 'LEFT');
		// $join3 = array('pengajuan_pembayaran_kasbon_tipe', 'pengajuan_pembayaran_kasbon_tipe.pengajuan_pembayaran_kasbon_id = pengajuan_pembayaran_kasbon.id ', 'LEFT');
		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('`status`', 1);
		
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('`status`', 1);
		
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('`status`', 1);
		
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

	public function get_max_id_pengajuan()
	{
		$format = "SELECT MAX(SUBSTRING(`id`,12,4)) AS max_id FROM `pengajuan_pembayaran_kasbon` WHERE SUBSTR(`id`,4,7) = DATE_FORMAT(NOW(), '%m-%Y');";	
		return $this->db->query($format);
	}
	


}

/* End of file pengajuan_pembayaran_kasbon_m.php */
/* Location: ./application/models/keuangan/pengajuan_pembayaran_kasbon_m.php */