<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pendaftaran_tindakan_history_m extends MY_Model {

	protected $_table        = 'pendaftaran_tindakan_history';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
				
	);

	private $_fillable_edit = array(
		
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'pendaftaran_tindakan_history.id'           => 'id', 
		'pendaftaran_tindakan_history.created_date' => 'tanggal', 
		'pendaftaran_tindakan_history.shift' => 'shift', 
		'pasien.nama'                       => 'nama_pasien', 
		'cabang.nama'                       => 'nama_cabang',
		'poliklinik.nama'                   => 'nama_poli',
		'a.nama'                            => 'nama_dokter',
		'penjamin.nama'                     => 'nama_penjamin',
		'pasien_penjamin.no_kartu'          => 'no_kartu',
		'pasien.ref_kode_rs_rujukan'		=> 'asal_rujukan',
		'pasien.ref_nomor_rujukan'			=> 'no_rujukan',
		'pasien_penjamin.no_kartu'          => 'no_kartu',
		'b.nama'                            => 'nama_fo',
		'pendaftaran_tindakan_history.keterangan'   => 'keterangan', 
		'pendaftaran_tindakan_history.status'       => 'status',
		'pendaftaran_tindakan_history.status_verif' => 'status_verif',
		'pasien.no_member'                  => 'no_member', 
		'pasien.url_photo'                  => 'url_photo', 
		'pendaftaran_tindakan_history.pasien_id'    => 'pasien_id',  
		'pendaftaran_tindakan_history.penjamin_id'  => 'penjamin_id',  
		
		
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns_daftar = array(
		//column di table  => alias
		'pendaftaran_tindakan_history.id'           => 'id', 
		'pendaftaran_tindakan_history.created_date' => 'tanggal', 
		'pendaftaran_tindakan_history.shift' 		=> 'shift', 
		'pasien.nama'                       => 'nama_pasien', 
		'cabang.nama'                       => 'nama_cabang',
		'poliklinik.nama'                   => 'nama_poli',
		'a.nama'                            => 'nama_dokter',
		'penjamin.nama'                     => 'nama_penjamin',
		'pasien_penjamin.no_kartu'          => 'no_kartu',
		'b.nama'                            => 'nama_fo',
		'pendaftaran_tindakan_history.keterangan'   => 'keterangan', 
		'pendaftaran_tindakan_history.status'       => 'status',
		'pendaftaran_tindakan_history.status_verif' => 'status_verif',
		'pasien.no_member'                  => 'no_member', 
		'pasien.url_photo'                  => 'url_photo', 
		'pendaftaran_tindakan_history.pasien_id'    => 'pasien_id',  
		'pendaftaran_tindakan_history.penjamin_id'  => 'penjamin_id',  
		'pendaftaran_tindakan_history.dokter_id'  => 'dokter_id',  
		
	);

	protected $datatable_columns_antri = array(
		//column di table  => alias
		'pendaftaran_tindakan_history.shift'        => 'shift',
		'pasien.nama'                       => 'nama', 
		'pasien.tempat_lahir'               => 'tempat_lahir', 
		'pasien.tanggal_lahir'              => 'tanggal_lahir',
		'pasien.url_photo'                  => 'url_photo',
		'f.alamat'                          => 'alamat',
		'inf_lokasi.nama_kecamatan'         => 'kecamatan',
		'inf_lokasi.nama_kelurahan'         => 'kelurahan',
		'inf_lokasi.nama_kabupatenkota'     => 'kota',
		'pendaftaran_tindakan_history.created_date' => 'created_date',
		'pendaftaran_tindakan_history.antrian'      => 'antrian',
		'pendaftaran_tindakan_history.id'           => 'id',
		'pendaftaran_tindakan_history.keterangan'   => 'keterangan',
		'pasien.no_member'                  => 'no_member',
		'pendaftaran_tindakan_history.dokter_id'  => 'dokter_id',  
		'pendaftaran_tindakan_history.berat_badan'  => 'berat_badan',  
		'pendaftaran_tindakan_history.tekanan_darah'  => 'tekanan_darah',  
		
		
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($date_awal,$date_akhir)
	{	
		$cabang_id = $this->session->userdata('cabang_id');


		$join1 = array('pasien', $this->_table.'.pasien_id = pasien.id','left');
		$join2 = array('cabang', $this->_table.'.cabang_id = cabang.id','left');
		$join3 = array('poliklinik', $this->_table.'.poliklinik_id = poliklinik.id','left');
		$join4 = array('user a', $this->_table.'.dokter_id = a.id','left');
		$join5 = array('user b', $this->_table.'.created_by = b.id','left');
		$join6 = array('penjamin', $this->_table.'.penjamin_id = penjamin.id','left');
		$join7 = array('pasien_penjamin', 'pendaftaran_tindakan_history.pasien_id = pasien_penjamin.pasien_id AND pendaftaran_tindakan_history.penjamin_id = pasien_penjamin.penjamin_id', 'left');
		$join_tables = array($join1, $join2, $join3, $join4, $join5, $join6, $join7);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = 'pendaftaran_tindakan_history.created_date';
		$params['sort_dir'] = 'desc';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('pasien_penjamin.is_active',1);
		$this->db->where($this->_table.'.cabang_id',$cabang_id);
		$this->db->where($this->_table.'.is_active',1);
		
		$this->db->where('date(pendaftaran_tindakan_history.created_date) >= ', $date_awal); 
		$this->db->where('date(pendaftaran_tindakan_history.created_date) <= ', $date_akhir);

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('pasien_penjamin.is_active',1);
		$this->db->where($this->_table.'.cabang_id',$cabang_id);
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where('date(pendaftaran_tindakan_history.created_date) >= ', $date_awal); 
		$this->db->where('date(pendaftaran_tindakan_history.created_date) <= ', $date_akhir);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('pasien_penjamin.is_active',1);
		$this->db->where($this->_table.'.cabang_id',$cabang_id);
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where('date(pendaftaran_tindakan_history.created_date) >= ', $date_awal); 
		$this->db->where('date(pendaftaran_tindakan_history.created_date) <= ', $date_akhir);

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

	public function get_datatable_antri($cabang_id=null)
	{	
 
		 $join1 = array('pasien', $this->_table . '.pasien_id = pasien.id');
		 $join2 = array('(select alamat,pasien_id, kode_lokasi from pasien_alamat WHERE is_primary =  1) f', 'f.pasien_id = pasien.id','left');
		 $join3 = array('inf_lokasi','f.kode_lokasi = inf_lokasi.lokasi_kode','left');
		  
		 $join_tables = array($join1,$join2,$join3);
		//$join_tables = array();

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_antri);
		$params['sort_by']  = 'pendaftaran_tindakan_history.id';
		$params['sort_dir'] = 'asc';
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('poliklinik_id',1);
		$this->db->where('pendaftaran_tindakan_history.cabang_id',$cabang_id);
		$this->db->where('pendaftaran_tindakan_history.status',4);
		$this->db->where('pendaftaran_tindakan_history.status_verif',2);
		 
		 
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('poliklinik_id',1);
		$this->db->where('pendaftaran_tindakan_history.cabang_id',$cabang_id);
		$this->db->where('pendaftaran_tindakan_history.status',4);
		$this->db->where('pendaftaran_tindakan_history.status_verif',2);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('poliklinik_id',1);
		$this->db->where('pendaftaran_tindakan_history.cabang_id',$cabang_id);
		$this->db->where('pendaftaran_tindakan_history.status',4);
		$this->db->where('pendaftaran_tindakan_history.status_verif',2);
		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_antri as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_antri;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
 // die(dump($result->records));
		return $result; 
	}

	public function get_datatable_antri_history($cabang_id=null)
	{	
 
		 $join1 = array('pasien', $this->_table . '.pasien_id = pasien.id');
		 $join2 = array('(select alamat,pasien_id, kode_lokasi from pasien_alamat WHERE is_primary =  1) f', 'f.pasien_id = pasien.id','left');
		 $join3 = array('inf_lokasi','f.kode_lokasi = inf_lokasi.lokasi_kode','left');
		  
		 $join_tables = array($join1,$join2,$join3);
		//$join_tables = array();

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_antri);
		$params['sort_by']  = 'pendaftaran_tindakan_history.id';
		$params['sort_dir'] = 'desc';
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('poliklinik_id',1);
		$this->db->where('pendaftaran_tindakan_history.cabang_id',$cabang_id);
		$this->db->where('pendaftaran_tindakan_history.status',1);
		$this->db->where('pendaftaran_tindakan_history.status_verif',2);

		$this->db->or_where('poliklinik_id',1);
		$this->db->where('pendaftaran_tindakan_history.cabang_id',$cabang_id);
		$this->db->where('pendaftaran_tindakan_history.status',2);
		$this->db->where('pendaftaran_tindakan_history.status_verif',2);
		 
		 
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('poliklinik_id',1);
		$this->db->where('pendaftaran_tindakan_history.cabang_id',$cabang_id);
		$this->db->where('pendaftaran_tindakan_history.status',1);
		$this->db->where('pendaftaran_tindakan_history.status_verif',2);

		$this->db->or_where('poliklinik_id',1);
		$this->db->where('pendaftaran_tindakan_history.cabang_id',$cabang_id);
		$this->db->where('pendaftaran_tindakan_history.status',2);
		$this->db->where('pendaftaran_tindakan_history.status_verif',2);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('poliklinik_id',1);
		$this->db->where('pendaftaran_tindakan_history.cabang_id',$cabang_id);
		$this->db->where('pendaftaran_tindakan_history.status',1);
		$this->db->where('pendaftaran_tindakan_history.status_verif',2);

		$this->db->or_where('poliklinik_id',1);
		$this->db->where('pendaftaran_tindakan_history.cabang_id',$cabang_id);
		$this->db->where('pendaftaran_tindakan_history.status',2);
		$this->db->where('pendaftaran_tindakan_history.status_verif',2);
		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_antri as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_antri;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
 // die(dump($result->records));
		return $result; 
	}

	public function get_data_poli($cabang_id)
	{
		$this->db->select("cabang_poliklinik.id as id,
							poliklinik.nama as nama,
							cabang_poliklinik.jam_buka as jam_buka,
							cabang_poliklinik.jam_tutup as jam_tutup");
		$this->db->join("poliklinik", $this->_table.".poliklinik_id = poliklinik.id");
		$this->db->where($this->_table.".cabang_id", $cabang_id);

		return $this->db->get($this->_table);
	}
	/**
	 * [fillable_add description]
	 * @return [type] [description]
	 */
	public function fillable_add()
	{
		return $this->_fillable_add;
	}
	/**
	 * [fillable_edit description]
	 * @return [type] [description]
	 */
	public function fillable_edit()
	{
		return $this->_fillable_edit;
	}

	public function get_cabang_id($cabang_id){

		$this->db->select('cabang_poliklinik.id AS id, cabang_poliklinik.cabang_id AS cabang_id, cabang_poliklinik.poliklinik_id AS poliklinik_id, poliklinik.kode AS kode, poliklinik.nama AS nama');
		$this->db->join('poliklinik', $this->_table.'.poliklinik_id = poliklinik.id','left');
		// $this->db->join('poliklinik_paket',$this->_table.'.poliklinik_id = poliklinik_paket.id','left');
		$this->db->where('cabang_poliklinik.cabang_id',$cabang_id);
		// $this->db->group_by('cabang_poliklinik.poliklinik_id');

		return $this->db->get($this->_table);

	}

	public function get_data_by_id($id)
	{
		$this->db->where($this->_table.'.id',$id);
		
		return $this->db->get($this->_table);
	}

	public function get_data_by_status($poliklinik_id,$pasien_id)
	{
		$this->db->where($this->_table.'.poliklinik_id',$poliklinik_id);
		$this->db->where($this->_table.'.pasien_id',$pasien_id);
		$this->db->where($this->_table.'.status',4);
		$this->db->where($this->_table.'.status_verif != ',3);
		$this->db->or_where($this->_table.'.poliklinik_id',$poliklinik_id);
		$this->db->where($this->_table.'.pasien_id',$pasien_id);
		$this->db->where($this->_table.'.status',2);
		$this->db->where($this->_table.'.status_verif != ',3);
		$this->db->or_where($this->_table.'.poliklinik_id',$poliklinik_id);
		$this->db->where($this->_table.'.pasien_id',$pasien_id);
		$this->db->where($this->_table.'.status',1);
		$this->db->where($this->_table.'.status_verif != ',3);
		
		return $this->db->get($this->_table);
	}

	public function get_data_antrian()
	{
		$cabang_id = $this->session->userdata('cabang_id');
		$this->db->select('pasien.nama as nama_pasien, a.nama as nama_dokter, penjamin.nama as nama_penjamin, pendaftaran_tindakan_history.status, pendaftaran_tindakan_history.status_verif, pendaftaran_tindakan_history.created_date as created_date');

		$this->db->join('pasien', $this->_table.'.pasien_id = pasien.id','left');
		$this->db->join('user a', $this->_table.'.dokter_id = a.id','left');
		$this->db->join('penjamin', $this->_table.'.penjamin_id = penjamin.id','left');


		$this->db->where($this->_table.'.cabang_id',$cabang_id);
		$this->db->where($this->_table.'.status != ',3);
		$this->db->where($this->_table.'.status != ',0);
		$this->db->where($this->_table.'.status_verif != ',3);
		$this->db->where('date(pendaftaran_tindakan_history.created_date)',date('Y-m-d'));

		$this->db->order_by($this->_table.'.id','desc');
		
		return $this->db->get($this->_table)->result_array();
	}

	public function get_max_antrian($shift, $dokter_id)
	{
		$cabang_id = $this->session->userdata('cabang_id');
		$SQL = "SELECT MAX(antrian_dokter) as antrian_dokter FROM pendaftaran_tindakan_history WHERE shift = $shift AND dokter_id = $dokter_id AND date(created_date) = '".date('Y-m-d')."' AND cabang_id = $cabang_id;";

		return $this->db->query($SQL);
	}

	public function get_max_id_pendaftaran()
	{
		$format = "SELECT MAX(RIGHT(`id`,4)) AS max_id FROM `pendaftaran_tindakan_history` WHERE SUBSTR(`id`,4,6) = DATE_FORMAT(NOW(), '%m%Y');";	
		return $this->db->query($format);
	}

}

/* End of file cabang_m.php */
/* Location: ./application/models/master/cabang_m.php */