<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Keuangan_arus_kas_m extends MY_Model {

	protected $_table        = 'keuangan_arus_kas';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'keuangan_arus_kas.id'           => 'id', 
		'keuangan_arus_kas.tanggal'      => 'tanggal',
		'keuangan_arus_kas.tipe'     	  => 'tipe',
		'keuangan_arus_kas.keterangan'   => 'keterangan',
		'keuangan_arus_kas.bank_id'      => 'bank_id',
		'keuangan_arus_kas.debit_credit' => 'debit_credit',
		'keuangan_arus_kas.rupiah'       => 'rupiah',
		'keuangan_arus_kas.saldo'     	  => 'saldo',
		'keuangan_arus_kas.status'       => 'status',
		'user.nama'						=> 'nama'
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($tgl_awal,$tgl_akhir,$bank_id)
	{	
		$join1 = array('user', $this->_table.'.created_by = user.id','left');
		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = 'keuangan_arus_kas.tanggal';
		$params['sort_dir'] = 'asc';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where("bank_id", $bank_id);
		$this->db->where("DATE(tanggal) >= ", $tgl_awal);
		$this->db->where("DATE(tanggal) <= ", $tgl_akhir);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where("bank_id", $bank_id);
		$this->db->where("DATE(tanggal) >= ", $tgl_awal);
		$this->db->where("DATE(tanggal) <= ", $tgl_akhir);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where("bank_id", $bank_id);
		$this->db->where("DATE(tanggal) >= ", $tgl_awal);
		$this->db->where("DATE(tanggal) <= ", $tgl_akhir);

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

	public function get_saldo_before($tanggal, $bank_id)
	{
		$SQL = "SELECT saldo FROM keuangan_arus_kas WHERE date(tanggal) <= '$tanggal' AND bank_id = $bank_id ORDER BY tanggal DESC, created_date DESC LIMIT 1";

		return $this->db->query($SQL);
	}

	public function get_after_after($tanggal, $bank_id)
	{
		$SQL = "SELECT id, saldo FROM keuangan_arus_kas WHERE date(tanggal) >= '$tanggal' AND bank_id = $bank_id ORDER BY created_date ASC";

		return $this->db->query($SQL);
	}


}

/* End of file keuangan_arus_kas.php */
/* Location: ./application/models/gudang/keuangan_arus_kas.php */