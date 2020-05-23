<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Persetujuan_permintaan_biaya_m extends MY_Model {

	protected $_table        = 'persetujuan_permintaan_biaya';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'permintaan_biaya.id'                   => 'id', 
		'permintaan_biaya.transaksi_id'         => 'transaksi_id',
		'permintaan_biaya.user_tujuan_id'       => 'user_tujuan_id',
		'permintaan_biaya.tipe_transaksi'       => 'tipe_transaksi',
		'permintaan_biaya.tanggal'              => 'tanggal',
		'permintaan_biaya.subjek'               => 'subjek',
		'permintaan_biaya.keterangan'           => 'keterangan',
		'permintaan_biaya.`status`'             => '`status`',
		'permintaan_biaya.is_manual'            => 'is_manual',
		'permintaan_biaya.is_active'            => 'is_active',
		'user.nama'                             => 'nama_dibuat_oleh',
		'permintaan_biaya_cetak.no_cetak'       => 'no_cetak',
		'permintaan_biaya_tipe.bank_id'         => 'bank_id',
		'permintaan_biaya_tipe.pembayaran_tipe' => 'pembayaran_tipe',
		'permintaan_biaya_tipe.jumlah'          => 'rupiah',
		'permintaan_biaya_tipe.acc_name'        => 'acc_name',
		'permintaan_biaya_tipe.acc_number'      => 'acc_number',
		'permintaan_biaya_tipe.jatuh_tempo'     => 'jatuh_tempo',
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
		$join1 = array('`user`', $this->_table.'.user_tujuan_id = `user`.id', 'LEFT');
		$join2 = array('permintaan_biaya_cetak', $this->_table.'.id = permintaan_biaya_cetak.permintaan_biaya_id', 'LEFT');
		$join3 = array('permintaan_biaya_tipe', 'permintaan_biaya_tipe.permintaan_biaya_id = permintaan_biaya.id ', 'LEFT');
		$join_tables = array($join1, $join2, $join3);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

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

	public function get_max()
	{
		$sql = "select max(persetujuan_permintaan_biaya_id) as max_id from persetujuan_permintaan_biaya";
		return $this->db->query($sql);
	}

	

}

/* End of file Permintaan_biaya.php */
/* Location: ./application/models/keuangan/Permintaan_biaya.php */