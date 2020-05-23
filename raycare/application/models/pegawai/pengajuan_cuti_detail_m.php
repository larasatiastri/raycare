<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pengajuan_cuti_detail_m extends MY_Model {

	protected $_table      = 'realnew_core.pengajuan_cuti_detail';
	protected $_timestamps = true;
	protected $_order_by   = 'id';

	private $_fillable = array(
		 
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'pengajuan_cuti_detail.id'                     => 'id',
		'pengajuan_cuti_detail.created_date'           => 'created_date',
		'pengajuan_cuti_detail.jenis'                  => 'jenis',
		'pengajuan_cuti_detail.peraturan_cuti_id'                  => 'peraturan_cuti_id',
		'peraturan_cuti.nama'                  => 'nama',
		'pengajuan_cuti_detail.tanggal_mulai_cuti'     => 'tanggal_mulai_cuti',
		'pengajuan_cuti_detail.lama_cuti'              => 'lama_cuti',
		'pengajuan_cuti_detail.status'                 => 'status'
	);

	function __construct()
	{
		parent::__construct();
	}

		/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable()
	{
		$join1 = array('peraturan_cuti', $this->_table.'.peraturan_cuti_id = peraturan_cuti.id', 'left');
		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = 'pengajuan_cuti_detail.tanggal_mulai_cuti';
		$params['sort_dir'] = 'desc';
  
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active', 1);
		// $this->db->where('pasien_alamat.is_primary', 1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active', 1);
		// $this->db->where('pasien_alamat.is_primary', 1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active', 1);
		// $this->db->where('pasien_alamat.is_primary', 1);

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

	/**
	 * [fillable_add description]
	 * @return [type] [description]
	 */
	public function fillable()
	{
		return $this->_fillable;
	}

	public function get_data_is_active()
    {
        $this->db->where("is_active", 1);
        return $this->db->get($this->_table);
    }

	public function get_id()
	{
		$format = "SELECT MAX(RIGHT(`id`,4)) AS max_id FROM `pengajuan_cuti_detail` WHERE SUBSTRING(`id`,5,7) = DATE_FORMAT(NOW(), '%m-%Y')";	
		return $this->db->query($format);
	}

	public function get_data_detail($pengajuan_cuti_id)
	{
		$format = "SELECT CASE WHEN pengajuan_cuti_detail.tipe = 1 THEN 'Cuti' 
						WHEN pengajuan_cuti_detail.tipe = 2 THEN 'Izin Tidak Hadir'
						WHEN pengajuan_cuti_detail.tipe = 3 THEN 'Tugas Luar'
						WHEN pengajuan_cuti_detail.tipe = 4 THEN 'Sakit'
						WHEN pengajuan_cuti_detail.tipe = 5 THEN 'Ganti Hari'
						WHEN pengajuan_cuti_detail.tipe = 6 THEN 'Izin Datang Terlambat'
						WHEN pengajuan_cuti_detail.tipe = 7 THEN 'Izin Pulang Cepat' END AS jenis , cuti_izin.nama, pengajuan_cuti_detail.tipe FROM pengajuan_cuti_detail
 						LEFT JOIN cuti_izin ON pengajuan_cuti_detail.cuti_izin_id = cuti_izin.id
 						WHERE pengajuan_cuti_detail.pengajuan_cuti_id = '$pengajuan_cuti_id' GROUP BY pengajuan_cuti_detail.tipe, pengajuan_cuti_detail.cuti_izin_id"; 
		return $this->db->query($format);
	}

	public function get_cuti($pegawai_id, $tanggal)
	{
		$SQL = "SELECT * FROM realnew_core.pengajuan_cuti_detail WHERE realnew_core.pengajuan_cuti_detail.pegawai_id = $pegawai_id AND realnew_core.pengajuan_cuti_detail.`status` = 3 AND realnew_core.pengajuan_cuti_detail.`is_active` = 1 AND realnew_core.pengajuan_cuti_detail.tipe IN (1,2,4,5) AND date(realnew_core.pengajuan_cuti_detail.tanggal) = '$tanggal'  OR realnew_core.pengajuan_cuti_detail.pegawai_id = $pegawai_id  AND realnew_core.pengajuan_cuti_detail.`status` = 5 AND realnew_core.pengajuan_cuti_detail.`is_active` = 1 AND realnew_core.pengajuan_cuti_detail.tipe IN (1,2,4,5) AND date(realnew_core.pengajuan_cuti_detail.tanggal) = '$tanggal'";

		return $this->db->query($SQL);
	}

	public function get_libur($tanggal)
	{
		$SQL = "SELECT * FROM realnew_core.`daftar_kegiatan` WHERE date(tanggal_mulai) = '$tanggal'";

		return $this->db->query($SQL);
	}

}

/* End of file user_level_m.php */
/* Location: ./application/models/user_level_m.php */