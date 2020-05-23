<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tindakan_lab_detail_m extends MY_Model {

	protected $_table        = 'tindakan_lab_detail';
	protected $_order_by     = 'tanggal';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'tindakan_lab_detail.id'               => 'id',
		'tindakan_lab_detail.pasien_nama'      => 'nama_pasien',
		'master_vaksin.nama'               => 'nama_vaksin',
		'tindakan_lab_detail.tanggal'          => 'tanggal',
		'a.nama'                           => 'nama_dokter',
		'b.nama'                           => 'nama_perawat',
		'cabang.nama'                      => 'nama_cabang',
		'tindakan_lab_detail.master_vaksin_id' => 'master_vaksin_id',
		'tindakan_lab_detail.cabang_id'        => 'cabang_id',
		'tindakan_lab_detail.tipe_pasien'      => 'tipe_pasien',
		'tindakan_lab_detail.pasien_id'        => 'pasien_id',
		'tindakan_lab_detail.dokter_id'        => 'dokter_id',
		'tindakan_lab_detail.perawat_id'       => 'perawat_id'
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
		$join1 = array('pasien', $this->_table.'.pasien_id = pasien.id','left');
		$join2 = array('master_vaksin', $this->_table.'.master_vaksin_id = master_vaksin.id','left');
		$join3 = array('cabang', $this->_table.'.cabang_id = cabang.id','left');
		$join4 = array('user a', $this->_table.'.dokter_id = a.id','left');
		$join5 = array('user b', $this->_table.'.perawat_id = b.id','left');
		$join_tables = array($join1, $join2, $join3, $join4, $join5);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = $this->_table.'.tanggal';
		$params['sort_dir'] = 'desc';
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
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

	/**
	 * [get_datatable_history description]
	 * @return [type] [description]
	 */
	public function get_datatable_history($pasien_id = null, $vaksin_id = null)
	{	
		$join1 = array('pasien', $this->_table.'.pasien_id = pasien.id','left');
		$join2 = array('master_vaksin', $this->_table.'.master_vaksin_id = master_vaksin.id','left');
		$join3 = array('cabang', $this->_table.'.cabang_id = cabang.id','left');
		$join4 = array('user a', $this->_table.'.dokter_id = a.id','left');
		$join5 = array('user b', $this->_table.'.perawat_id = b.id','left');
		$join_tables = array($join1, $join2, $join3, $join4, $join5);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = $this->_table.'.tanggal';
		$params['sort_dir'] = 'desc';
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
			$this->db->where($this->_table.'.pasien_id', $pasien_id);
			$this->db->where($this->_table.'.master_vaksin_id', $vaksin_id);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		// $this->db->where('user_id', $user_id);
			$this->db->where($this->_table.'.pasien_id', $pasien_id);
			$this->db->where($this->_table.'.master_vaksin_id', $vaksin_id);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		// $this->db->where('user_id', $user_id);
			$this->db->where($this->_table.'.pasien_id', $pasien_id);
			$this->db->where($this->_table.'.master_vaksin_id', $vaksin_id);

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
		$format = "SELECT MAX(SUBSTRING(`id`,13,4)) AS max_id FROM `tindakan_lab_detail` WHERE SUBSTR(`id`,5,7) = DATE_FORMAT(NOW(), '%m-%Y');";	
		return $this->db->query($format);
	}

	public function get_data($transaksi_id)
	{
		$format = "SELECT pembayaran_tipe, nomor_tipe, SUM(total) as total FROM `tindakan_lab_detail` WHERE transaksi_id = '$transaksi_id' GROUP BY pembayaran_tipe, nomor_tipe";
		return $this->db->query($format);
		
	}
}

/* End of file Permintaan_biaya.php */
/* Location: ./application/models/keuangan/Permintaan_biaya.php */