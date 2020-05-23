<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rekening_koran_m extends MY_Model {

	protected $_table        = 'rekening_koran';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'keuangan_arus_kas.tanggal'      => 'tanggal',
		'rekening_koran.id'           => 'id',
		'keuangan_arus_kas.id'           => 'keuangan_arus_kas_id',
		'rekening_koran.nomor'       => 'nomor',
		'rekening_koran.nomor_cek'       => 'nomor_cek',
		'keuangan_arus_kas.keterangan'   => 'keterangan',
		'keuangan_arus_kas.debit_credit' => 'debit_credit',
		'keuangan_arus_kas.rupiah'          => 'jumlah',
		'keuangan_arus_kas.saldo'        => 'saldo',
		'user.nama'                      => 'nama',
		'keuangan_arus_kas.bank_id'      => 'bank_id',
		'keuangan_arus_kas.status'       => 'status',
		'keuangan_arus_kas.tipe'       => 'tipe',
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
		$join1 = array('keuangan_arus_kas', $this->_table.'.keuangan_arus_kas_id = keuangan_arus_kas.id','right');
		$join2 = array('user', 'keuangan_arus_kas.created_by = user.id','left');
		$join_tables = array($join1,$join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = 'keuangan_arus_kas.tanggal';
		$params['sort_dir'] = 'asc';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where("keuangan_arus_kas.bank_id", $bank_id);
		$this->db->where("DATE(keuangan_arus_kas.tanggal) >= ", $tgl_awal);
		$this->db->where("DATE(keuangan_arus_kas.tanggal) <= ", $tgl_akhir);
		//$this->db->where("rekening_koran.is_active",1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where("keuangan_arus_kas.bank_id", $bank_id);
		$this->db->where("DATE(keuangan_arus_kas.tanggal) >= ", $tgl_awal);
		$this->db->where("DATE(keuangan_arus_kas.tanggal) <= ", $tgl_akhir);
		//$this->db->where("rekening_koran.is_active",1);


		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where("keuangan_arus_kas.bank_id", $bank_id);
		$this->db->where("DATE(keuangan_arus_kas.tanggal) >= ", $tgl_awal);
		$this->db->where("DATE(keuangan_arus_kas.tanggal) <= ", $tgl_akhir);
		//$this->db->where("rekening_koran.is_active",1);
		

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


	public function get_max_id_rekening_koran()
	{
		$format = "SELECT MAX(SUBSTRING(`id`,12,4)) AS max_id FROM `rekening_koran` WHERE SUBSTR(`id`,4,7) = DATE_FORMAT(NOW(), '%m-%Y');";	
		return $this->db->query($format);
	}

}

/* End of file keuangan_arus_kas.php */
/* Location: ./application/models/gudang/keuangan_arus_kas.php */