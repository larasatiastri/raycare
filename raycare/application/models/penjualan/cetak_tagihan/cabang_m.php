<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cabang_m extends MY_Model {

	protected $_table        = 'cabang';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		'kode',
		'nama',
		'alamat', 
		'nomor_telepon1', 
		'nomor_telepon2', 
		'nomor_fax', 		
	);

	private $_fillable_edit = array(
		'kode',
		'nama',
		'alamat', 
		'nomor_telepon1', 
		'nomor_telepon2', 
		'nomor_fax', 
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'cabang.id'         => 'id', 
		'cabang.kode'   => 'kode', 
		'cabang.nama'   => 'nama', 
		'cabang_alamat.alamat'   => 'alamat',
		'cabang_telepon.nomor'     => 'nomor_tlp',
		'cabang.keterangan'     => 'keterangan',
		'cabang.is_active'     => 'is_active'
		
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
		$join1 = array('cabang_alamat', $this->_table.'.id = cabang_alamat.cabang_id','left');
		$join2 = array('cabang_telepon', $this->_table.'.id = cabang_telepon.cabang_id','left');
		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where('cabang_telepon.is_primary', 1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where('cabang_telepon.is_primary', 1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where('cabang_telepon.is_primary', 1);

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
	
	public function get_datatable_cabang_cetak_tagihan()
	{	
		$join1 = array('cabang_alamat', $this->_table.'.id = cabang_alamat.cabang_id','left');
		$join2 = array('cabang_telepon', $this->_table.'.id = cabang_telepon.cabang_id','left');
		$join3 = array('cabang_email', 'cabang_email.cabang_id = cabang.id', 'LEFT');
		$join_tables = array($join1, $join2, $join3);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where($this->_table.'.id', != 1);
		$this->db->where($this->_table.'.id', != 2);
		$this->db->where('cabang_alamat.is_primary', 1);
		$this->db->where('cabang_telepon.is_primary', 1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where($this->_table.'.id', != 1);
		$this->db->where($this->_table.'.id', != 2);
		$this->db->where('cabang_alamat.is_primary', 1);
		$this->db->where('cabang_telepon.is_primary', 1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where($this->_table.'.id', != 1);
		$this->db->where($this->_table.'.id', != 2);
		$this->db->where('cabang_alamat.is_primary', 1);
		$this->db->where('cabang_telepon.is_primary', 1);

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


	public function get_data_sub($tipe)
	{
		$format = "SELECT id as id, nama as nama
				   FROM subjek
				   WHERE tipe = $tipe";

		return $this->db->query($format, $tipe);
	}

	public function get_data_sub_view($id_subjek)
	{
		$format = "SELECT nama as nama
				   FROM subjek
				   WHERE tipe = 1 AND id = $id_subjek ";

		return $this->db->query($format, $id_subjek);
	}

	public function get_data_sub_telp()
	{
		$format = "SELECT id as id, nama as nama
				   FROM subjek
				   WHERE tipe = 2";

		return $this->db->query($format);
	}

	public function get_data_sub_telp_view($id_telepon)
	{
		$format = "SELECT nama as nama
				   FROM subjek
				   WHERE tipe = 2 AND id = $id_telepon";

		return $this->db->query($format, $id_telepon);
	}

	public function get_data_poliklinik()
	{
		$format = "SELECT id as id, nama as nama
				   FROM poliklinik
				   WHERE is_active = 1";

		return $this->db->query($format);
	}

	public function get_data_poliklinik_view($id)
	{
		$format = "SELECT nama as nama
				   FROM poliklinik
				   WHERE is_active = 1 AND id = $id";

		return $this->db->query($format, $id);
	}

	public function get_data_negara()
	{
		$format_negara = "SELECT id as id, nama as format_negara
						  FROM region 
						  WHERE tipe = 1";

		return $this->db->query($format_negara);
	}

	public function get_data_negara_view($id_form_negara)
	{
		$format_negara = "SELECT nama as negara
						  FROM region 
						  WHERE tipe = 1 AND id = $id_form_negara";

		return $this->db->query($format_negara);
	}

	public function get_data_provinsi($id_negara)
	{
		$format_provinsi = "SELECT id as id, nama as format_provinsi
						  FROM region 
						  WHERE parent = $id_negara";

		return $this->db->query($format_provinsi);
	}

	public function get_data_kota_kabupaten($id_provinsi)
	{
		$format_kota = "SELECT id as id, nama as format_kota
						  FROM region 
						  WHERE parent = $id_provinsi";

		return $this->db->query($format_kota);
	}

	public function get_data_kecamatan($id_kota)
	{
		$format_kecamatan = "SELECT id as id, nama as format_kecamatan
						  FROM region 
						  WHERE parent = $id_kota";

		return $this->db->query($format_kecamatan);
	}

	public function get_data_kelurahan($id_kecamatan)
	{
		$format_kelurahan = "SELECT id as id, nama as format_kelurahan
						  FROM region 
						  WHERE parent = $id_kecamatan";

		return $this->db->query($format_kelurahan);
	}

	public function get_data_dokter()
	{
		$format_dokter = "SELECT id as id, nama as dokter
						FROM user
						WHERE user_level_id = 10";

		return $this->db->query($format_dokter);
	}

	public function get_data_dokter_pendaftaran($id_poliklinik)
	{
		$format_dokter = "SELECT user.nama as nama
						  FROM
							   cabang_poliklinik_dokter ,
							   user ,
							   cabang_poliklinik
						  WHERE
							   cabang_poliklinik_dokter.dokter_id = user.id AND
							   cabang_poliklinik_dokter.cabang_poliklinik_id = cabang_poliklinik.id AND
							   cabang_poliklinik.id = $id_poliklinik";

		return $this->db->query($format_dokter, $id_poliklinik);
	}

	public function get_data_perawat()
	{
		$format_perawat = "SELECT id as id, nama as perawat
						FROM user
						WHERE user_level_id = 18";

		return $this->db->query($format_perawat);
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

}

/* End of file cabang_m.php */
/* Location: ./application/models/master/cabang_m.php */