<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Titip_setoran_detail_m extends MY_Model {

	protected $_table      = 'titip_setoran_detail';
	protected $_timestamps = true;
	protected $_order_by   = 'id';

	private $_fillable = array(
		'pasien_id',
		'dashboard_url', 
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'titip_setoran_detail.titip_setoran_id'	=> 'titip_setoran_id',
		'titip_setoran_detail.detail_id'		=> 'detail_id',
		'titip_setoran_detail.tipe_detail'		=> 'tipe_detail',
		'titip_setoran_detail.status'			=> 'status',
		'titip_setoran_detail.created_by'		=> 'created_by',
		'a.nama'								=> 'nama_user_created',
		'b.nama'								=> 'nama_user',
		'user_level.nama'						=> 'nama_user_level',
		'gudang_orang.nama'						=> 'nama_user_gudang',
	);

	protected $datatable_columns_pilih_titip_setoran_detail = array(
		//column di table  => alias
		'titip_setoran_detail.id'			    => 'id',
		'titip_setoran_detail.nama'			    => 'nama',
		'titip_setoran_detail.tempat_lahir'	    => 'tempat_lahir',
		'titip_setoran_detail.tanggal_lahir'	=> 'tanggal_lahir',
		'titip_setoran_detail.is_active'		=> 'active',
		'titip_setoran_detail_alamat.alamat'	=> 'alamat'
	);

	function __construct()
	{
		parent::__construct();
	}

		/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($status)
	{

		$join1 = array('user a', $this->_table.'.created_by = a.id','left');
		$join2 = array('user b', $this->_table.'.penerima_id = b.id','left');
		$join3 = array('user_level', $this->_table.'.penerima_id = user_level.id','left');
		$join4 = array('gudang_orang', $this->_table.'.penerima_id = gudang_orang.id','left');
		$join_tables = array($join1, $join2, $join3, $join4);

		$status_titip_setoran_detail = '';

		if ($status == 1) {

			$status_titip_setoran_detail = 1;
		
		} elseif ($status == 2) {

			$status_titip_setoran_detail = 2;
		}

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		// $this->db->where('left(tanggal, 7) =', $date);
		
		if ($status == 1) {

			$this->db->where($this->_table.'.status', 1);

		} elseif ($status == 2) {
			
			$this->db->where($this->_table.'.status', 2);

		}


		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		
		if ($status == 1) {

			$this->db->where($this->_table.'.status', 1);

		} elseif ($status == 2) {
			
			$this->db->where($this->_table.'.status', 2);

		}

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		
		if ($status == 1) {

			$this->db->where($this->_table.'.status', 1);

		} elseif ($status == 2) {
			
			$this->db->where($this->_table.'.status', 2);

		}

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
		// die_dump($result->records);
		return $result; 
	}

	public function get_datatable_history()
	{

		$join1 = array('user a', $this->_table.'.created_by = a.id','left');
		$join2 = array('user b', $this->_table.'.user_id = b.id','left');
		$join3 = array('user_level', $this->_table.'.user_id = user_level.id','left');
		$join4 = array('gudang_orang', $this->_table.'.user_id = gudang_orang.id','left');
		$join_tables = array($join1, $join2, $join3, $join4);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		// $this->db->where('left(tanggal, 7) =', $date);
		$this->db->where($this->_table.'is_lunas', 1);
		// $this->db->where('titip_setoran_detail_alamat.is_primary', 1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		// $this->db->where('left(tanggal, 7) =', $date);
		$this->db->where($this->_table.'.is_lunas', 1);
		// $this->db->where('titip_setoran_detail_alamat.is_primary', 1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		// $this->db->where('left(tanggal, 7) =', $date);
		$this->db->where($this->_table.'.is_lunas', 1);
		// $this->db->where('titip_setoran_detail_alamat.is_primary', 1);

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

	public function get_datatable_history_titip_setoran_detail($status, $date)
	{

		$join1 = array('poliklinik a', $this->_table.'.poliklinik_tujuan_id = a.id','left');
		$join2 = array('poliklinik b', $this->_table.'.poliklinik_asal_id = b.id','left');
		$join3 = array('pasien', $this->_table.'.pasien_id = pasien.id','left');

		$join_tables = array($join1, $join2, $join3);

		$status_titip_setoran_detail = '';

		if($status == 1) 
		{
			$status_titip_setoran_detail = 1;

		} elseif ($status == 2) 
		{

			$status_titip_setoran_detail = 0;

		} elseif ($status == 3)
		{
		
			$status_titip_setoran_detail = 0;
		
		}

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);

		

		if($status == 0) 
		{

			$this->db->where('left(tanggal_titip_setoran_detail, 10) <', $date);
			$this->db->where('titip_setoran_detail.is_active', 1);
			$this->db->where('selesai', $status_titip_setoran_detail);
			$this->db->or_where('left(tanggal_titip_setoran_detail, 10) >=', $date);
			$this->db->where('selesai', $status_titip_setoran_detail);
			$this->db->where('titip_setoran_detail.is_active', 1);
			$this->db->or_where('left(tanggal_titip_setoran_detail, 10) =', $date);
			$this->db->where('selesai', $status_titip_setoran_detail);
			$this->db->where('titip_setoran_detail.is_active', 1);
			$this->db->or_where('left(tanggal_titip_setoran_detail, 10) !=', $date);
			$this->db->where('selesai', $status_titip_setoran_detail);
			$this->db->where('titip_setoran_detail.is_active', 1);


		} elseif ($status == 1) {

			$this->db->where('left(tanggal_titip_setoran_detail, 10) >=', $date);
			$this->db->where('titip_setoran_detail.is_active', 1);
			$this->db->where('selesai', 1);

		} elseif ($status == 2) {

			$this->db->where('left(tanggal_titip_setoran_detail, 10) <', $date);
			$this->db->where('titip_setoran_detail.is_active', 1);
			$this->db->where('selesai', 0);
			

		} elseif ($status == 3) {

			$this->db->where('left(tanggal_titip_setoran_detail, 10) >=', $date);
			$this->db->where('titip_setoran_detail.is_active', 1);
			$this->db->where('selesai', 0);

		}

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		

		if($status == 0) 
		{

			$this->db->where('titip_setoran_detail.is_active', 1);
			$this->db->where('selesai', 1);
			$this->db->or_where('selesai', 0);
			$this->db->where('titip_setoran_detail.is_active', 1);

		} elseif ($status == 1) {

			$this->db->where('left(tanggal_titip_setoran_detail, 10) >=', $date);
			$this->db->where('titip_setoran_detail.is_active', 1);
			$this->db->where('selesai', 1);

		} elseif ($status == 2) {

			$this->db->where('left(tanggal_titip_setoran_detail, 10) <', $date);
			$this->db->where('titip_setoran_detail.is_active', 1);
			$this->db->where('selesai', 0);
			

		} elseif ($status == 3) {

			$this->db->where('left(tanggal_titip_setoran_detail, 10) >=', $date);
			$this->db->where('titip_setoran_detail.is_active', 1);
			$this->db->where('selesai', 0);

		}
		// $this->db->where('titip_setoran_detail_alamat.is_primary', 1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		

		if($status == 0) 
		{

			$this->db->where('titip_setoran_detail.is_active', 1);
			$this->db->where('selesai', 1);
			$this->db->or_where('selesai', 0);
			$this->db->where('titip_setoran_detail.is_active', 1);

		} elseif ($status == 1) {

			$this->db->where('left(tanggal_titip_setoran_detail, 10) >=', $date);
			$this->db->where('titip_setoran_detail.is_active', 1);
			$this->db->where('selesai', 1);

		} elseif ($status == 2) {

			$this->db->where('left(tanggal_titip_setoran_detail, 10) <', $date);
			$this->db->where('titip_setoran_detail.is_active', 1);
			$this->db->where('selesai', 0);
			

		} elseif ($status == 3) {

			$this->db->where('left(tanggal_titip_setoran_detail, 10) >=', $date);
			$this->db->where('titip_setoran_detail.is_active', 1);
			$this->db->where('selesai', 0);

		}
		// $this->db->where('titip_setoran_detail_alamat.is_primary', 1);

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

	public function get_datatable_kasir_biaya($titip_setoran_id)
	{
		$datatable_columns_pilih_user = array(
		//column di table  => alias
		'titip_setoran_detail.id'		 		=> 'id',
		'titip_setoran_detail.titip_setoran_id'	=> 'titip_setoran_id',
		'titip_setoran_detail.detail_id'	 	=> 'detail_id',
		'titip_setoran_detail.tipe_detail' 		=> 'tipe_detail', 
		'titip_setoran_detail.status' 			=> 'status', 
		'kasir_biaya.keterangan'				=> 'keterangan',
		'kasir_biaya.rupiah'					=> 'rupiah',
		'kasir_biaya.tanggal'					=> 'tanggal_kasir_biaya',
		'user.nama'				 	 			=> 'nama_user',
		'user.is_active'			 			=> 'is_active',
		'user_level.nama'			 			=> 'nama_user_level',
		'cabang.id'								=> 'cabang_id',
		);

		$join1 = array("titip_setoran", $this->_table . '.titip_setoran_id = titip_setoran.id', 'left');
		$join2 = array("kasir_biaya", $this->_table . '.detail_id = kasir_biaya.id', 'left');
		$join3 = array("user", 'titip_setoran.penerima_id = user.id', 'left');
		$join4 = array("user_level", 'user.user_level_id = user_level.id', 'left');
		$join5 = array("cabang", 'user.cabang_id = cabang.id', 'left');
		$join_tables = array($join1, $join2, $join3, $join4, $join5);

		// get params dari input postnya datatable
		$params = $this->datatable_param($datatable_columns_pilih_user);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.titip_setoran_id', $titip_setoran_id);
		$this->db->where($this->_table.'.tipe_detail', 1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.titip_setoran_id', $titip_setoran_id);
		$this->db->where($this->_table.'.tipe_detail', 1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.titip_setoran_id', $titip_setoran_id);
		$this->db->where($this->_table.'.tipe_detail', 1);

		// tentukan kolom yang mau diselect
		foreach ($datatable_columns_pilih_user as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $datatable_columns_pilih_user;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 

		return $result; 
	}

	public function get_datatable_pembayaran_pasien($titip_setoran_id)
	{
		$datatable_columns_pilih_user = array(
		//column di table  => alias
		'titip_setoran_detail.id'		 		=> 'id',
		'titip_setoran_detail.titip_setoran_id'	=> 'titip_setoran_id',
		'titip_setoran_detail.detail_id'	 	=> 'detail_id',
		'titip_setoran_detail.tipe_detail' 		=> 'tipe_detail', 
		'titip_setoran_detail.status' 			=> 'status', 
		'kasir_biaya.keterangan'				=> 'keterangan',
		'pembayaran_pasien.id'					=> 'pp_id',
		'pembayaran_pasien.no_faktur'			=> 'no_faktur',
		'pembayaran_pasien.bayar'				=> 'bayar',
		'pembayaran_pasien.tanggal'				=> 'tanggal',
		'user.nama'				 	 			=> 'nama_user',
		'user.is_active'			 			=> 'is_active',
		'user_level.nama'			 			=> 'nama_user_level',
		);
		$join1 = array("titip_setoran", $this->_table . '.titip_setoran_id = titip_setoran.id', 'left');
		$join2 = array("kasir_biaya", $this->_table . '.detail_id = kasir_biaya.id', 'left');
		$join3 = array("user", 'titip_setoran.penerima_id = user.id', 'left');
		$join4 = array("user_level", 'user.user_level_id = user_level.id', 'left');
		$join5 = array("pembayaran_pasien", $this->_table.'.detail_id = pembayaran_pasien.id', 'left');

		$join_tables = array($join1, $join2, $join3, $join4, $join5);

		// get params dari input postnya datatable
		$params = $this->datatable_param($datatable_columns_pilih_user);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.titip_setoran_id', $titip_setoran_id);
		$this->db->where($this->_table.'.tipe_detail', 2);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.titip_setoran_id', $titip_setoran_id);
		$this->db->where($this->_table.'.tipe_detail', 2);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.titip_setoran_id', $titip_setoran_id);
		$this->db->where($this->_table.'.tipe_detail', 2);

		// tentukan kolom yang mau diselect
		foreach ($datatable_columns_pilih_user as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $datatable_columns_pilih_user;
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
	
	function get_sisa($user_id,$tipe)
	{
		// $this->db->where('id',$id);
		$this->db->where('user_id',$user_id);
		$this->db->where('tipe_user',$tipe);
		$this->db->where('is_lunas',0);
		$this->db->order_by('tanggal', 'asc');
		return $this->db->get($this->_table); 
	} 


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
		$format = "SELECT MAX(SUBSTRING(`no_member`,16,4)) AS max_no_member FROM `titip_setoran_detail` WHERE SUBSTRING(`no_member`,14,2) = DATE_FORMAT(NOW(), '%y')";	
		return $this->db->query($format);
	}
}

/* End of file titip_setoran_detail_m.php */
/* Location: ./application/models/titip_setoran_detail_m.php */