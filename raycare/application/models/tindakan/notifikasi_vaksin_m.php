<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notifikasi_vaksin_m extends MY_Model {

	protected $_table        = 'notifikasi_vaksin';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'notifikasi_vaksin.id'                   => 'id',
		'notifikasi_vaksin.tipe_transaksi'       => 'tipe_transaksi',
		'notifikasi_vaksin.transaksi_nomor'      => 'transaksi_nomor',
		'bank.nob'                             => 'bank_cek',
		'notifikasi_vaksin.nomor_tipe'           => 'nomor_tipe',
		'notifikasi_vaksin.tanggal'              => 'tanggal',
		'notifikasi_vaksin.jatuh_tempo'          => 'jatuh_tempo',
		'user.nama'                            => 'nama_buat',
		'notifikasi_vaksin.penerima'             => 'penerima',
		'notifikasi_vaksin.nama_bank'            => 'nama_bank',
		'notifikasi_vaksin.total'                => 'total',
		'bank.acc_name'                        => 'acc_name',
		'bank.acc_number'                      => 'acc_number',
		'notifikasi_vaksin.transaksi_id'         => 'transaksi_id',
		'notifikasi_vaksin.pembayaran_status_id' => 'pembayaran_status_id'
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($status)
	{	
		$join1 = array('bank', $this->_table.'.bank_id = bank.id','left');
		$join2 = array('user', $this->_table.'.created_by = user.id','left');
		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.status', $status);
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.status', $status);
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.status', $status);
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

	public function get_max_id()
	{
		$format = "SELECT MAX(`id`) AS max_id FROM `notifikasi_vaksin`";	
		return $this->db->query($format);
	}

	public function get_data($transaksi_id)
	{
		$format = "SELECT pembayaran_tipe, nomor_tipe, SUM(total) as total FROM `notifikasi_vaksin` WHERE transaksi_id = '$transaksi_id' GROUP BY pembayaran_tipe, nomor_tipe";
		return $this->db->query($format);
		
	}
}

/* End of file Permintaan_biaya.php */
/* Location: ./application/models/keuangan/Permintaan_biaya.php */