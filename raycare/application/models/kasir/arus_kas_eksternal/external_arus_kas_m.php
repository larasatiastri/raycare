<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class External_arus_kas_m extends MY_Model {

	protected $_table        = 'external_arus_kas';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'external_arus_kas.id'           => 'id', 
		'external_arus_kas.tanggal'      => 'tanggal',
		'user.nama'      => 'nama',
		'external_arus_kas.tipe'     	  => 'tipe',
		'external_arus_kas.keterangan'   => 'keterangan',
		'external_arus_kas.user_id'      => 'user_id',
		'external_arus_kas.debit_credit' => 'debit_credit',
		'external_arus_kas.rupiah'       => 'rupiah',
		'external_arus_kas.saldo'     	  => 'saldo',
		'external_arus_kas.status'       => 'status',
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($tgl_awal,$tgl_akhir,$kasir_id)
	{	
		$join1 = array('user', $this->_table.'.created_by = user.id','left');
		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where("DATE(tanggal) >= ", $tgl_awal);
		$this->db->where("DATE(tanggal) <= ", $tgl_akhir);
		if($kasir_id != 0){
			$this->db->where("user_id", $kasir_id);
		}
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where("DATE(tanggal) >= ", $tgl_awal);
		$this->db->where("DATE(tanggal) <= ", $tgl_akhir);
		if($kasir_id != 0){
			$this->db->where("user_id", $kasir_id);
		}
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where("DATE(tanggal) >= ", $tgl_awal);
		$this->db->where("DATE(tanggal) <= ", $tgl_akhir);
		if($kasir_id != 0){
			$this->db->where("user_id", $kasir_id);
		}

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

	public function get_saldo_before($tanggal)
	{
		$SQL = "SELECT saldo FROM external_arus_kas WHERE date(tanggal) <= '$tanggal' ORDER BY tanggal DESC, created_date DESC LIMIT 1";

		return $this->db->query($SQL);
	}

	public function get_after_after($tanggal)
	{
		$SQL = "SELECT id, saldo FROM external_arus_kas WHERE date(tanggal) > '$tanggal' ORDER BY created_date ASC";

		return $this->db->query($SQL);
	}


}

/* End of file external_arus_kas.php */
/* Location: ./application/models/gudang/external_arus_kas.php */