<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Calon_pasien_m extends MY_Model {

	protected $_table      = 'calon_pasien';
	protected $_timestamps = true;
	protected $_order_by   = 'id';

	private $_fillable = array(
		'nama',
		'dashboard_url', 
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'calon_pasien.id'                  => 'id',
		'calon_pasien.nama'                => 'nama',
		'calon_pasien.no_member'           => 'no_ktp',
		'calon_pasien.tempat_lahir'        => 'tempat_lahir',
		'calon_pasien.tanggal_lahir'       => 'tanggal_lahir',
		'calon_pasien_alamat.alamat'       => 'alamat',
		'cabang.nama'                => 'nama_cabang',
		'calon_pasien.url_photo'           => 'url_photo',
		'calon_pasien.is_active'           => 'active',	
		'calon_pasien_alamat.kota_id'      => 'kota_id',
		'region.nama'                => 'nama_kota',
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

		$join1 = array('calon_pasien_alamat', $this->_table.'.id = calon_pasien_alamat.calon_pasien_id','left');
		$join2 = array('cabang', $this->_table.'.cabang_id = cabang.id','left');
		
		$datatable_columns = array(
			//column di table  => alias
			'calon_pasien.id'				=> 'id',
			'calon_pasien.nama'			=> 'nama',
			'calon_pasien.no_member'		=> 'no_member',
			'cabang.nama'           => 'nama_cabang',
			'calon_pasien.gender'			=> 'gender',
			'calon_pasien.tempat_lahir'	=> 'tempat_lahir',
			'calon_pasien.tanggal_lahir'	=> 'tanggal_lahir',
			'calon_pasien.is_active'		=> 'active',
			'calon_pasien_alamat.alamat'	=> 'alamat',
			'(SELECT region.nama FROM region WHERE region.tipe = 5 AND calon_pasien_alamat.kelurahan_id = region.id )' => 'kelurahan',
			'(SELECT region.nama FROM region WHERE region.tipe = 4 AND calon_pasien_alamat.kecamatan_id = region.id )' => 'kecamatan',
			'(SELECT region.nama FROM region WHERE region.tipe = 3 AND calon_pasien_alamat.kota_id = region.id )' => 'kota',
			'(SELECT region.nama FROM region WHERE region.tipe = 2 AND calon_pasien_alamat.propinsi_id = region.id )' => 'propinsi',
			'(SELECT region.nama FROM region WHERE region.tipe = 1 AND calon_pasien_alamat.negara_id = region.id )' => 'negara',
			'calon_pasien.url_photo'		=> 'url_photo',
		);
		 
		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where('calon_pasien_alamat.is_primary', 1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where('calon_pasien_alamat.is_primary', 1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where('calon_pasien_alamat.is_primary', 1);

		// tentukan kolom yang mau diselect
		foreach ($datatable_columns as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $datatable_columns;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 

		return $result; 
	}

	public function get_datatable_pilih_calon_pasien()
	{
		$datatable_columns_pilih_calon_pasien = array(
		//column di table  => alias
		'calon_pasien.id'				=> 'id',
		'calon_pasien.no_member'		=> 'no_ktp',
		'calon_pasien.nama'			=> 'nama',
		'calon_pasien.gender'			=> 'gender',
		'calon_pasien.tempat_lahir'	=> 'tempat_lahir',
		'calon_pasien.tanggal_lahir'	=> 'tanggal_lahir',
		'calon_pasien.is_active'		=> 'active',
		'calon_pasien_alamat.alamat'	=> 'alamat',
		'calon_pasien_telepon.nomor'	=> 'nomor',
		'(SELECT region.nama FROM region WHERE region.tipe = 5 AND calon_pasien_alamat.kelurahan_id = region.id )' => 'kelurahan',
		'(SELECT region.nama FROM region WHERE region.tipe = 4 AND calon_pasien_alamat.kecamatan_id = region.id )' => 'kecamatan',
		'(SELECT region.nama FROM region WHERE region.tipe = 3 AND calon_pasien_alamat.kota_id = region.id )' => 'kota',
		'(SELECT region.nama FROM region WHERE region.tipe = 2 AND calon_pasien_alamat.propinsi_id = region.id )' => 'propinsi',
		'(SELECT region.nama FROM region WHERE region.tipe = 1 AND calon_pasien_alamat.negara_id = region.id )' => 'negara',
		'calon_pasien.url_photo'	=> 'url_photo',
		);

		$join1 = array('calon_pasien_alamat', $this->_table.'.id = calon_pasien_alamat.calon_pasien_id','left');
		$join2 = array('calon_pasien_telepon', $this->_table.'.id = calon_pasien_telepon.calon_pasien_id','left');
		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($datatable_columns_pilih_calon_pasien);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where("calon_pasien_telepon.is_primary", 1);
		$this->db->where("calon_pasien_alamat.is_primary", 1);
		$this->db->where($this->_table.'.is_meninggal', 0);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where("calon_pasien_telepon.is_primary", 1);
		$this->db->where("calon_pasien_alamat.is_primary", 1);
		$this->db->where($this->_table.'.is_meninggal', 0);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where("calon_pasien_telepon.is_primary", 1);
		$this->db->where("calon_pasien_alamat.is_primary", 1);
		$this->db->where($this->_table.'.is_meninggal', 0);

		// tentukan kolom yang mau diselect
		foreach ($datatable_columns_pilih_calon_pasien as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $datatable_columns_pilih_calon_pasien;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 

		return $result; 
	}

	public function get_datatable_pilih_calon_pasien_pindah_domisili()
	{
		$datatable_columns_pilih_calon_pasien = array(
			//column di table  => alias
			'calon_pasien.id'				=> 'id',
			'calon_pasien.no_member'		=> 'no_ktp',
			'calon_pasien.nama'			=> 'nama',
			'calon_pasien.gender'			=> 'gender',
			'calon_pasien.tempat_lahir'	=> 'tempat_lahir',
			'calon_pasien.tanggal_lahir'	=> 'tanggal_lahir',
			'calon_pasien_alamat.alamat'	=> 'alamat',
			'calon_pasien_telepon.nomor'	=> 'nomor',
			'(SELECT info_umum.nama FROM info_umum WHERE info_umum.tipe = 1 AND calon_pasien.agama_id = info_umum.id )' 			=> 'agama',
			'(SELECT info_umum.nama FROM info_umum WHERE info_umum.tipe = 2 AND calon_pasien.golongan_darah_id = info_umum.id )' 	=> 'gol_darah',
			'(SELECT info_umum.nama FROM info_umum WHERE info_umum.tipe = 3 AND calon_pasien.pendidikan_id = info_umum.id )' 		=> 'pendidikan',
			'(SELECT info_umum.nama FROM info_umum WHERE info_umum.tipe = 4 AND calon_pasien.pekerjaan_id = info_umum.id )' 		=> 'pekerjaan',
			'(SELECT region.nama FROM region WHERE region.tipe = 5 AND calon_pasien_alamat.kelurahan_id = region.id )' 	=> 'kelurahan',
			'(SELECT region.nama FROM region WHERE region.tipe = 4 AND calon_pasien_alamat.kecamatan_id = region.id )' 	=> 'kecamatan',
			'(SELECT region.nama FROM region WHERE region.tipe = 3 AND calon_pasien_alamat.kota_id = region.id )' 		=> 'kota',
			'(SELECT region.nama FROM region WHERE region.tipe = 2 AND calon_pasien_alamat.propinsi_id = region.id )' 	=> 'propinsi',
			'(SELECT region.nama FROM region WHERE region.tipe = 1 AND calon_pasien_alamat.negara_id = region.id )' 		=> 'negara',
			'cabang.nama'             => 'cabang',
			'cabang.id'            	  => 'cabang_id',
			'calon_pasien.penanggung_jawab' => 'penanggung_jawab',
			'calon_pasien.url_photo'        => 'url_photo',
			'calon_pasien.is_active'        => 'active',
		);

		$join1 = array('calon_pasien_alamat', $this->_table.'.id = calon_pasien_alamat.calon_pasien_id','left');
		$join2 = array('calon_pasien_telepon', $this->_table.'.id = calon_pasien_telepon.calon_pasien_id','left');
		$join3 = array('cabang', $this->_table.'.cabang_id = cabang.id','left');
		$join_tables = array($join1, $join2, $join3);

		// get params dari input postnya datatable
		$params = $this->datatable_param($datatable_columns_pilih_calon_pasien);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where("calon_pasien_telepon.is_primary", 1);
		$this->db->where("calon_pasien_alamat.is_primary", 1);
		$this->db->where($this->_table.'.is_meninggal', 0);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where("calon_pasien_telepon.is_primary", 1);
		$this->db->where("calon_pasien_alamat.is_primary", 1);
		$this->db->where($this->_table.'.is_meninggal', 0);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where("calon_pasien_telepon.is_primary", 1);
		$this->db->where("calon_pasien_alamat.is_primary", 1);
		$this->db->where($this->_table.'.is_meninggal', 0);

		// tentukan kolom yang mau diselect
		foreach ($datatable_columns_pilih_calon_pasien as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $datatable_columns_pilih_calon_pasien;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 

		return $result; 
	}

	public function get_datatable_pilih_calon_pasien_pembayaran()
	{
		$datatable_columns_pilih_calon_pasien = array(
		//column di table  => alias
		'calon_pasien.id'				=> 'id',
		'calon_pasien.no_member'		=> 'no_ktp',
		'calon_pasien.nama'			=> 'nama',
		'calon_pasien.gender'			=> 'gender',
		'calon_pasien.tempat_lahir'	=> 'tempat_lahir',
		'calon_pasien.tanggal_lahir'	=> 'tanggal_lahir',
		'calon_pasien.is_active'		=> 'active',
		'calon_pasien_alamat.alamat'	=> 'alamat',
		'calon_pasien_telepon.nomor'	=> 'nomor',
		'calon_pasien.cabang_id'		=> 'cabang_id',
		'calon_pasien.calon_pasien_id'		=> 'calon_pasien_id',
		'DATEDIFF(now(),tanggal_lahir)' => 'usia',
		'(SELECT region.nama FROM region WHERE region.tipe = 5 AND calon_pasien_alamat.kelurahan_id = region.id )' => 'kelurahan',
		'(SELECT region.nama FROM region WHERE region.tipe = 4 AND calon_pasien_alamat.kecamatan_id = region.id )' => 'kecamatan',
		'(SELECT region.nama FROM region WHERE region.tipe = 3 AND calon_pasien_alamat.kota_id = region.id )' => 'kota',
		'(SELECT region.nama FROM region WHERE region.tipe = 2 AND calon_pasien_alamat.propinsi_id = region.id )' => 'propinsi',
		'(SELECT region.nama FROM region WHERE region.tipe = 1 AND calon_pasien_alamat.negara_id = region.id )' => 'negara',
		);

		$join1 = array('calon_pasien_alamat', $this->_table.'.id = calon_pasien_alamat.calon_pasien_id','left');
		$join2 = array('calon_pasien_telepon', $this->_table.'.id = calon_pasien_telepon.calon_pasien_id','left');
		$join3 = array('cabang', $this->_table.'.cabang_id = cabang.id','left');
		$join3 = array('tindakan_hd', $this->_table.'.calon_pasien_id = tindakan_hd.id','left');
		$join_tables = array($join1, $join2, $join3);

		// get params dari input postnya datatable
		$params = $this->datatable_param($datatable_columns_pilih_calon_pasien);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where("calon_pasien_telepon.is_primary", 1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where("calon_pasien_telepon.is_primary", 1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where("calon_pasien_telepon.is_primary", 1);

		// tentukan kolom yang mau diselect
		foreach ($datatable_columns_pilih_calon_pasien as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $datatable_columns_pilih_calon_pasien;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 

		return $result; 
	}

		/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable_active()
	{

		$join_tables = array();

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('is_active',1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('is_active',1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('is_active',1);

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

    public function get_data_subjek($type)
	{
		$format = "SELECT id as id, nama as nama
				   FROM subjek
				   WHERE tipe = $type";

		return $this->db->query($format);
	}
	public function get_no_member()
	{
		$format = "SELECT MAX(SUBSTRING(`no_member`,16,4)) AS max_no_member FROM `calon_pasien` WHERE SUBSTRING(`no_member`,14,2) = DATE_FORMAT(NOW(), '%y')";	
		return $this->db->query($format);
	}


	public function getdatacalon_pasien($id){
		$sql= "select b.nama,c.alamat,b.gender,b.tanggal_lahir,DATEDIFF(now(),tanggal_lahir) AS usia,b.no_member from pendaftaran_tindakan a join calon_pasien b on a.calon_pasien_id=b.id left join calon_pasien_alamat c on c.calon_pasien_id=b.id where a.id=?";

		return $this->db->query($sql,$id);
	}

	public function get_data_calon_pasien_pindah_domisili($calon_pasien_id)
	{
		$format = "SELECT calon_pasien.id AS id, calon_pasien.no_member AS no_ktp, calon_pasien.nama AS nama, calon_pasien.gender AS gender, calon_pasien.tempat_lahir AS tempat_lahir, calon_pasien.tanggal_lahir AS tanggal_lahir, calon_pasien_alamat.alamat AS alamat, calon_pasien_telepon.nomor AS nomor, (SELECT info_umum.nama FROM info_umum WHERE info_umum.tipe = 1 AND calon_pasien.agama_id = info_umum.id ) AS agama, (SELECT info_umum.nama FROM info_umum WHERE info_umum.tipe = 2 AND calon_pasien.golongan_darah_id = info_umum.id ) AS gol_darah, (SELECT info_umum.nama FROM info_umum WHERE info_umum.tipe = 3 AND calon_pasien.pendidikan_id = info_umum.id ) AS pendidikan, (SELECT info_umum.nama FROM info_umum WHERE info_umum.tipe = 4 AND calon_pasien.pekerjaan_id = info_umum.id ) AS pekerjaan, (SELECT region.nama FROM region WHERE region.tipe = 5 AND calon_pasien_alamat.kelurahan_id = region.id ) AS kelurahan, (SELECT region.nama FROM region WHERE region.tipe = 4 AND calon_pasien_alamat.kecamatan_id = region.id ) AS kecamatan, (SELECT region.nama FROM region WHERE region.tipe = 3 AND calon_pasien_alamat.kota_id = region.id ) AS kota, (SELECT region.nama FROM region WHERE region.tipe = 2 AND calon_pasien_alamat.propinsi_id = region.id ) AS propinsi, (SELECT region.nama FROM region WHERE region.tipe = 1 AND calon_pasien_alamat.negara_id = region.id ) AS negara, cabang.nama AS cabang, cabang.id AS cabang_id, calon_pasien.penanggung_jawab AS penanggung_jawab, calon_pasien.url_photo AS url_photo, calon_pasien.is_active AS active
					FROM calon_pasien
					LEFT JOIN calon_pasien_alamat ON calon_pasien.id = calon_pasien_alamat.calon_pasien_id
					LEFT JOIN calon_pasien_telepon ON calon_pasien.id = calon_pasien_telepon.calon_pasien_id
					LEFT JOIN cabang ON calon_pasien.cabang_id = cabang.id
					WHERE calon_pasien.is_active =  1
					AND calon_pasien_telepon.is_primary =  1
					AND calon_pasien_alamat.is_primary =  1
					AND calon_pasien.is_meninggal =  0
					AND calon_pasien.id = ".$pasien_id."
		";	

		return $this->db->query($format);
	}

}

/* End of file user_level_m.php */
/* Location: ./application/models/user_level_m.php */