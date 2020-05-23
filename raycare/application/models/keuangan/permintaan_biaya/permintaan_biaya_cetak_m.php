<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permintaan_biaya_cetak_m extends MY_Model {

	protected $_table        = 'permintaan_biaya_cetak';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'permintaan_biaya.id'              => 'id', 
		'permintaan_biaya.transaksi_id'    => 'transaksi_id',
		'permintaan_biaya.diminta_oleh_id' => 'diminta_oleh_id',
		'permintaan_biaya.tipe_transaksi'  => 'tipe_transaksi',
		'permintaan_biaya.tanggal'         => 'tanggal',
		'permintaan_biaya.nominal'         => 'nominal',
		'permintaan_biaya.keperluan'       => 'keperluan',
		'permintaan_biaya.`status`'        => '`status`',
		'permintaan_biaya.is_manual'       => 'is_manual',
		'permintaan_biaya.is_active'       => 'is_active',
		'user.nama'                        => 'nama_dibuat_oleh',
		'permintaan_biaya_cetak.no_cetak'  => 'no_cetak',
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
		$join1 = array('`user`', $this->_table.'.diminta_oleh_id = `user`.id', 'LEFT');
		$join2 = array('permintaan_biaya_cetak', $this->_table.'.id = permintaan_biaya_cetak.permintaan_biaya_id', 'LEFT');
		$join_tables = array($join1, $join2);

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

	public function get_nomor_cetak()
	{
		$format = "SELECT MAX(SUBSTRING(`no_cetak`,12,3)) AS max_nomor_cetak FROM `permintaan_biaya_cetak` WHERE SUBSTRING(`no_cetak`,5,4) = DATE_FORMAT(NOW(), '%y%m')";	
		return $this->db->query($format);
	}


}

/* End of file Permintaan_biaya.php */
/* Location: ./application/models/keuangan/Permintaan_biaya.php */