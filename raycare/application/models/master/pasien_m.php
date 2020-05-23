<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pasien_m extends MY_Model {

	protected $_table      = 'pasien';
	protected $_timestamps = true;
	protected $_order_by   = 'id';

	private $_fillable = array(
		'nama',
		'dashboard_url', 
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'pasien.id'                     => 'id',
		'pasien.nama'                   => 'nama',
		'pasien.no_member'              => 'no_member',
		'pasien.tanggal_lahir'          => 'tanggal_lahir',
		'pasien_alamat.alamat'          => 'alamat',
		'cabang.nama'                   => 'nama_cabang',
		'pasien.tempat_lahir'           => 'tempat_lahir',
		'pasien.gender'                 => 'gender',
		'pasien.is_active'              => 'active',
		'inf_lokasi.nama_kelurahan'     => 'kelurahan',
		'inf_lokasi.nama_kecamatan'     => 'kecamatan',
		'inf_lokasi.nama_kabupatenkota' => 'kota',
		'inf_lokasi.nama_propinsi'      => 'propinsi',
		'pasien.url_photo'              => 'url_photo',
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

		$join1 = array('pasien_alamat', $this->_table.'.id = pasien_alamat.pasien_id','left');
		$join2 = array('inf_lokasi', 'pasien_alamat.kode_lokasi = inf_lokasi.lokasi_kode','left');
		$join3 = array('cabang', $this->_table.'.cabang_id = cabang.id','left');
		
		
		 
		$join_tables = array($join1, $join2, $join3);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = 'pasien.id';
		$params['sort_dir'] = 'desc';


		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where('pasien_alamat.is_primary', 1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where('pasien_alamat.is_primary', 1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where('pasien_alamat.is_primary', 1);

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

	public function get_datatable_pilih_pasien()
	{
		$datatable_columns_pilih_pasien = array(
			//column di table  => alias
			'pasien.id'                     => 'id',
			'pasien.no_member'              => 'no_ktp',
			'pasien.nama'                   => 'nama',
			'pasien.gender'                 => 'gender',
			'pasien.tempat_lahir'           => 'tempat_lahir',
			'pasien.tanggal_lahir'          => 'tanggal_lahir',
			'pasien.is_active'              => 'active',
			'pasien_alamat.alamat'          => 'alamat',
			'pasien_telepon.nomor'          => 'nomor',
			'pasien.is_meninggal'           => 'is_meninggal',
			'pasien.tanggal_registrasi'     => 'tanggal_registrasi',
			'inf_lokasi.nama_kelurahan'     => 'kelurahan',
			'inf_lokasi.nama_kecamatan'     => 'kecamatan',
			'inf_lokasi.nama_kabupatenkota' => 'kota',
			'inf_lokasi.nama_propinsi'      => 'propinsi',
			'a.nama'      => 'nama_pekerjaan',
			'b.nama'      => 'gol_darah',
			'pasien.url_photo'                                                          => 'url_photo',
			"DATEDIFF(CURRENT_DATE, STR_TO_DATE(pasien.tanggal_lahir, '%Y-%m-%d'))/365" =>'umur'
		);

		$join1 = array('pasien_alamat', $this->_table.'.id = pasien_alamat.pasien_id','left');
		$join2 = array('inf_lokasi', 'pasien_alamat.kode_lokasi = inf_lokasi.lokasi_kode','left');
		$join3 = array('info_umum a', $this->_table.'.pekerjaan_id = a.id','left');
		$join4 = array('info_umum b', $this->_table.'.golongan_darah_id = b.id','left');
		$join5 = array('pasien_telepon', $this->_table.'.id = pasien_telepon.pasien_id','left');
		$join_tables = array($join1, $join2, $join3,$join4,$join5);

		// get params dari input postnya datatable
		$params = $this->datatable_param($datatable_columns_pilih_pasien);
		$params['sort_by'] = 'pasien.id';
		$params['sort_dir'] = 'asc';
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where("pasien_alamat.is_primary", 1);
		$this->db->where("pasien_telepon.is_primary", 1);			
		$this->db->where($this->_table.'.is_meninggal', 0);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where("pasien_alamat.is_primary", 1);
		$this->db->where("pasien_telepon.is_primary", 1);			
		$this->db->where($this->_table.'.is_meninggal', 0);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where("pasien_alamat.is_primary", 1);
		$this->db->where("pasien_telepon.is_primary", 1);			
		$this->db->where($this->_table.'.is_meninggal', 0);

		// tentukan kolom yang mau diselect
		foreach ($datatable_columns_pilih_pasien as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $datatable_columns_pilih_pasien;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 

		return $result; 
	}

	public function get_datatable_pilih_pasien_pindah_domisili()
	{
		$datatable_columns_pilih_pasien = array(
			//column di table  => alias
			'pasien.id'				=> 'id',
			'pasien.no_member'		=> 'no_ktp',
			'pasien.nama'			=> 'nama',
			'pasien.gender'			=> 'gender',
			'pasien.tempat_lahir'	=> 'tempat_lahir',
			'pasien.tanggal_lahir'	=> 'tanggal_lahir',
			'pasien_alamat.alamat'	=> 'alamat',
			'pasien_telepon.nomor'	=> 'nomor',
			'(SELECT info_umum.nama FROM info_umum WHERE info_umum.tipe = 1 AND pasien.agama_id = info_umum.id )' 			=> 'agama',
			'(SELECT info_umum.nama FROM info_umum WHERE info_umum.tipe = 2 AND pasien.golongan_darah_id = info_umum.id )' 	=> 'gol_darah',
			'(SELECT info_umum.nama FROM info_umum WHERE info_umum.tipe = 3 AND pasien.pendidikan_id = info_umum.id )' 		=> 'pendidikan',
			'(SELECT info_umum.nama FROM info_umum WHERE info_umum.tipe = 4 AND pasien.pekerjaan_id = info_umum.id )' 		=> 'pekerjaan',
			'inf_lokasi.nama_kelurahan'     => 'kelurahan',
			'inf_lokasi.nama_kecamatan'     => 'kecamatan',
			'inf_lokasi.nama_kabupatenkota' => 'kota',
			'inf_lokasi.nama_propinsi'      => 'propinsi',
			'cabang.nama'                   => 'cabang',
			'cabang.id'                     => 'cabang_id',
			'pasien.penanggung_jawab'       => 'penanggung_jawab',
			'pasien.url_photo'              => 'url_photo',
			'pasien.is_active'              => 'active',
		);

		$join1 = array('pasien_alamat', $this->_table.'.id = pasien_alamat.pasien_id','left');
		$join2 = array('inf_lokasi', 'pasien_alamat.kode_lokasi = inf_lokasi.lokasi_kode','left');
		$join3 = array('pasien_telepon', $this->_table.'.id = pasien_telepon.pasien_id','left');
		$join4 = array('cabang', $this->_table.'.cabang_id = cabang.id','left');
		$join_tables = array($join1, $join2, $join3, $join4);

		// get params dari input postnya datatable
		$params = $this->datatable_param($datatable_columns_pilih_pasien);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where("pasien_telepon.is_primary", 1);
		$this->db->where("pasien_alamat.is_primary", 1);
		$this->db->where($this->_table.'.is_meninggal', 0);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where("pasien_telepon.is_primary", 1);
		$this->db->where("pasien_alamat.is_primary", 1);
		$this->db->where($this->_table.'.is_meninggal', 0);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where("pasien_telepon.is_primary", 1);
		$this->db->where("pasien_alamat.is_primary", 1);
		$this->db->where($this->_table.'.is_meninggal', 0);

		// tentukan kolom yang mau diselect
		foreach ($datatable_columns_pilih_pasien as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $datatable_columns_pilih_pasien;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 

		return $result; 
	}

	public function get_datatable_pilih_pasien_penjualan_obat()
	{
		$this->load->model('master/pasien_alamat_m');
		$this->load->model('master/pasien_telepon_m');
		$datatable_columns_pilih_pasien = array(
			//column di table  => alias
			'pasien.id'                 => 'id',
			'pasien.no_member'          => 'no_member',
			'pasien.nama'               => 'nama',
			'pasien.gender'             => 'gender',
			'pasien.tempat_lahir'       => 'tempat_lahir',
			'pasien.tanggal_lahir'      => 'tanggal_lahir',
			'pasien.is_active'          => 'active',
			'pasien_alamat.alamat'      => 'alamat',
			'pasien_telepon.nomor'      => 'nomor',
			'pasien.is_meninggal'       => 'is_meninggal',
			'pasien.tanggal_registrasi' => 'tanggal_registrasi',
			'inf_lokasi.nama_kelurahan' => 'kelurahan',
			'inf_lokasi.nama_kecamatan' => 'kecamatan',
			'inf_lokasi.nama_kabupatenkota' => 'kota',
			'inf_lokasi.nama_propinsi' => 'propinsi',
			'pasien.url_photo'                                                          => 'url_photo',
			"DATEDIFF(CURRENT_DATE, STR_TO_DATE(pasien.tanggal_lahir, '%Y-%m-%d'))/365" =>'umur'
		);

		$join1 = array('pasien_alamat', $this->_table.'.id = pasien_alamat.pasien_id','left');
		$join2 = array('inf_lokasi', 'pasien_alamat.kode_lokasi = inf_lokasi.lokasi_kode','left');
		$join3 = array('pasien_telepon', $this->_table.'.id = pasien_telepon.pasien_id','left');
		$join_tables = array($join1, $join2, $join3);

		$pasien_alamat = $this->pasien_alamat_m->get_by(array('pasien_id' => $this->_table.'.id', 'is_primary' => 1));
		$pasien_telepon = $this->pasien_telepon_m->get_by(array('pasien_id' => $this->_table.'.id', 'is_primary' => 1));
		// get params dari input postnya datatable
		$params = $this->datatable_param($datatable_columns_pilih_pasien);
		$params['sort_by'] = 'pasien.id';
		$params['sort_dir'] = 'asc';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.is_meninggal', 0);
		$this->db->where("pasien_alamat.is_primary", 1);	
		$this->db->where("pasien_telepon.is_primary", 1);			
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.is_meninggal', 0);
		$this->db->where("pasien_alamat.is_primary", 1);	
		$this->db->where("pasien_telepon.is_primary", 1);			
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.is_meninggal', 0);
		$this->db->where("pasien_alamat.is_primary", 1);	
		$this->db->where("pasien_telepon.is_primary", 1);			

		// tentukan kolom yang mau diselect
		foreach ($datatable_columns_pilih_pasien as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $datatable_columns_pilih_pasien;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_datatable_pilih_pasien_pembayaran()
	{
		$datatable_columns_pilih_pasien = array(
		//column di table  => alias
			'pasien.id'                     => 'id',
			'pasien.no_member'              => 'no_ktp',
			'pasien.nama'                   => 'nama',
			'pasien.gender'                 => 'gender',
			'pasien.tempat_lahir'           => 'tempat_lahir',
			'pasien.tanggal_lahir'          => 'tanggal_lahir',
			'pasien.tanggal_daftar'         => 'tanggal_registrasi',
			'pasien.is_active'              => 'active',
			'pasien_alamat.alamat'          => 'alamat',
			'pasien_telepon.nomor'          => 'nomor',
			'pasien.cabang_id'              => 'cabang_id',
			'pasien.url_photo'              => 'url_photo',
			'pasien.pasien_id'              => 'pasien_id',
			'DATEDIFF(now(),tanggal_lahir)' => 'usia',
			'inf_lokasi.nama_kelurahan'     => 'kelurahan',
			'inf_lokasi.nama_kecamatan'     => 'kecamatan',
			'inf_lokasi.nama_kabupatenkota' => 'kota',
			'inf_lokasi.nama_propinsi'      => 'propinsi',
		);

		$join1 = array('pasien_alamat', $this->_table.'.id = pasien_alamat.pasien_id','left');
		$join2 = array('inf_lokasi', 'pasien_alamat.kode_lokasi = inf_lokasi.lokasi_kode','left');
		$join3 = array('pasien_telepon', $this->_table.'.id = pasien_telepon.pasien_id','left');
		$join4 = array('cabang', $this->_table.'.cabang_id = cabang.id','left');
		$join_tables = array($join1, $join2, $join3, $join4);

		// get params dari input postnya datatable
		$params = $this->datatable_param($datatable_columns_pilih_pasien);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active', 1);
		
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active', 1);
		
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active', 1);
		

		// tentukan kolom yang mau diselect
		foreach ($datatable_columns_pilih_pasien as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $datatable_columns_pilih_pasien;
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
		$format = "SELECT MAX(SUBSTRING(`no_member`,16,4)) AS max_no_member FROM `pasien` WHERE SUBSTRING(`no_member`,14,2) = DATE_FORMAT(NOW(), '%y')";	
		return $this->db->query($format);
	}


	public function getdatapasien($id){
		$sql= "select b.nama,c.alamat,b.gender,b.tanggal_lahir,DATEDIFF(now(),tanggal_lahir) AS usia,b.no_member from pendaftaran_tindakan a join pasien b on a.pasien_id=b.id left join pasien_alamat c on c.pasien_id=b.id where a.id=?";

		return $this->db->query($sql,$id);
	}

	public function get_data_pasien_pindah_domisili($pasien_id)
	{
		$format = "SELECT pasien.id AS id, pasien.no_member AS no_ktp, pasien.nama AS nama, pasien.gender AS gender, pasien.tempat_lahir AS tempat_lahir, pasien.tanggal_lahir AS tanggal_lahir, pasien_alamat.alamat AS alamat, pasien_telepon.nomor AS nomor, (SELECT info_umum.nama FROM info_umum WHERE info_umum.tipe = 1 AND pasien.agama_id = info_umum.id ) AS agama, (SELECT info_umum.nama FROM info_umum WHERE info_umum.tipe = 2 AND pasien.golongan_darah_id = info_umum.id ) AS gol_darah, (SELECT info_umum.nama FROM info_umum WHERE info_umum.tipe = 3 AND pasien.pendidikan_id = info_umum.id ) AS pendidikan, (SELECT info_umum.nama FROM info_umum WHERE info_umum.tipe = 4 AND pasien.pekerjaan_id = info_umum.id ) AS pekerjaan, (SELECT region.nama FROM region WHERE region.tipe = 5 AND pasien_alamat.kelurahan_id = region.id ) AS kelurahan, (SELECT region.nama FROM region WHERE region.tipe = 4 AND pasien_alamat.kecamatan_id = region.id ) AS kecamatan, (SELECT region.nama FROM region WHERE region.tipe = 3 AND pasien_alamat.kota_id = region.id ) AS kota, (SELECT region.nama FROM region WHERE region.tipe = 2 AND pasien_alamat.propinsi_id = region.id ) AS propinsi, (SELECT region.nama FROM region WHERE region.tipe = 1 AND pasien_alamat.negara_id = region.id ) AS negara, cabang.nama AS cabang, cabang.id AS cabang_id, pasien.penanggung_jawab AS penanggung_jawab, pasien.url_photo AS url_photo, pasien.is_active AS active
					FROM pasien
					LEFT JOIN pasien_alamat ON pasien.id = pasien_alamat.pasien_id
					LEFT JOIN pasien_telepon ON pasien.id = pasien_telepon.pasien_id
					LEFT JOIN cabang ON pasien.cabang_id = cabang.id
					WHERE pasien.is_active =  1
					AND pasien_telepon.is_primary =  1
					AND pasien_alamat.is_primary =  1
					AND pasien.is_meninggal =  0
					AND pasien.id = ".$pasien_id."
		";	

		return $this->db->query($format);
	}

	public function get_pasien_by_nomor($no_member, $tipe)
	{
		$SQL = "SELECT pasien.id AS id, pasien.no_member AS no_ktp, pasien.no_ktp AS no_ktp_real, pasien.no_bpjs AS no_bpjs, pasien.nama AS nama, pasien.gender AS gender, pasien.tempat_lahir AS tempat_lahir, pasien.tanggal_lahir AS tanggal_lahir, pasien.is_active AS active, pasien_alamat.alamat AS alamat, pasien_telepon.nomor AS nomor, DATEDIFF(CURRENT_DATE, STR_TO_DATE(pasien.tanggal_lahir, '%Y-%m-%d'))/365 AS umur, inf_lokasi.nama_kelurahan AS kelurahan, inf_lokasi.nama_kecamatan AS kecamatan, inf_lokasi.nama_kabupatenkota AS kota, inf_lokasi.nama_propinsi AS propinsi, pasien.url_photo AS url_photo, pasien.tanggal_registrasi AS tanggal_registrasi, pekerjaan.nama as nama_pekerjaan, gol_dar.nama as nama_gol_dar,
						(SELECT COUNT(rm_transaksi_pasien.pasien_id) FROM rm_transaksi_pasien WHERE pasien.id = rm_transaksi_pasien.pasien_id) AS count_transaksi,
			 			(SELECT COUNT(o_s_pembayaran_transaksi.pasien_id) FROM o_s_pembayaran_transaksi WHERE pasien.id = o_s_pembayaran_transaksi.pasien_id) AS count_tagihan
				FROM pasien
				LEFT JOIN pasien_alamat ON pasien.id = pasien_alamat.pasien_id
				LEFT JOIN inf_lokasi ON pasien_alamat.kode_lokasi = inf_lokasi.lokasi_kode
				LEFT JOIN pasien_telepon ON pasien.id = pasien_telepon.pasien_id
				LEFT JOIN info_umum pekerjaan ON pasien.pekerjaan_id = pekerjaan.id
				LEFT JOIN info_umum gol_dar ON pasien.golongan_darah_id = gol_dar.id ";

		if($tipe == 1){
			$SQL .= "WHERE pasien.no_member = '$no_member' ";
		}if($tipe == 2){
			$SQL .= "WHERE pasien.no_ktp = '$no_member' ";
		}if($tipe == 3){
			$SQL .= "WHERE pasien.no_bpjs = '$no_member' ";
		}

		$SQL .= "AND pasien.is_active =  1
				AND pasien_telepon.is_primary =  1
				AND pasien_alamat.is_primary =  1
				AND pasien.is_meninggal =  0";

		return $this->db->query($SQL);
	}


	public function get_datatable_pilih_pasien_all()
	{
		$datatable_columns_pilih_pasien = array(
			//column di table  => alias
			'pasien.id'                     => 'id',
			'pasien.no_member'              => 'no_ktp',
			'pasien.nama'                   => 'nama',
			'pasien.gender'                 => 'gender',
			'pasien.tempat_lahir'           => 'tempat_lahir',
			'pasien.tanggal_lahir'          => 'tanggal_lahir',
			'pasien.is_active'              => 'active',
			'pasien_alamat.alamat'          => 'alamat',
			'pasien_telepon.nomor'          => 'nomor',
			'pasien.is_meninggal'           => 'is_meninggal',
			'pasien.tanggal_registrasi'     => 'tanggal_registrasi',
			'inf_lokasi.nama_kelurahan'     => 'kelurahan',
			'inf_lokasi.nama_kecamatan'     => 'kecamatan',
			'inf_lokasi.nama_kabupatenkota' => 'kota',
			'inf_lokasi.nama_propinsi'      => 'propinsi',
			'pasien.url_photo'                                                          => 'url_photo',
			"DATEDIFF(CURRENT_DATE, STR_TO_DATE(pasien.tanggal_lahir, '%Y-%m-%d'))/365" =>'umur'
		);

		$join1 = array('pasien_alamat', $this->_table.'.id = pasien_alamat.pasien_id','left');
		$join2 = array('inf_lokasi', 'pasien_alamat.kode_lokasi = inf_lokasi.lokasi_kode','left');
		$join3 = array('pasien_telepon', $this->_table.'.id = pasien_telepon.pasien_id','left');
		$join_tables = array($join1, $join2, $join3);

		// get params dari input postnya datatable
		$params = $this->datatable_param($datatable_columns_pilih_pasien);
		$params['sort_by'] = 'pasien.id';
		$params['sort_dir'] = 'asc';
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where("pasien_alamat.is_primary", 1);
		$this->db->where('pasien.id IN (SELECT pasien_id from invoice WHERE invoice.status = 1 AND invoice.tipe_pasien != 2 AND invoice.penjamin_id = 1 OR invoice.status = 3 AND invoice.tipe_pasien != 2 AND invoice.penjamin_id = 1 )');
		$this->db->group_by('pasien.id');
		// dapatkan total row count;
		$query = $this->db->select('(1)')->get();
		$total_records = $query->num_rows();
		// $total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where("pasien_alamat.is_primary", 1);
		$this->db->where('pasien.id IN (SELECT pasien_id from invoice WHERE invoice.status = 1 AND invoice.tipe_pasien != 2 AND invoice.penjamin_id = 1 OR invoice.status = 3 AND invoice.tipe_pasien != 2 AND invoice.penjamin_id = 1 )');
		$this->db->group_by('pasien.id');
		// dapatkan total record filtered/search;
		$query = $this->db->select('(1)')->get();
		$total_display_records = $query->num_rows();
		// $total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where("pasien_alamat.is_primary", 1);
		$this->db->where('pasien.id IN (SELECT pasien_id from invoice WHERE invoice.status = 1 AND invoice.tipe_pasien != 2 AND invoice.penjamin_id = 1 OR invoice.status = 3 AND invoice.tipe_pasien != 2 AND invoice.penjamin_id = 1 )');
		$this->db->group_by('pasien.id');

		// tentukan kolom yang mau diselect
		foreach ($datatable_columns_pilih_pasien as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $datatable_columns_pilih_pasien;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 

		return $result; 
	}
}

/* End of file user_level_m.php */
/* Location: ./application/models/user_level_m.php */