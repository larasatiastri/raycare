<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Os_pembayaran_tindakan_m extends MY_Model {

	protected $_table      = 'o_s_pembayaran_tindakan';
	protected $_timestamps = true;
	protected $_order_by   = 'id';

	private $_fillable = array(
		'pasien_id',
		'dashboard_url', 
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'o_s_pembayaran_tindakan.id'					=> 'id',
		'o_s_pembayaran_tindakan.tindakan_id'		=> 'tindakan_id',
		'o_s_pembayaran_tindakan.cabang_id'			=> 'cabang_id',
		'o_s_pembayaran_tindakan.pasien_id'			=> 'pasien_id',
		'o_s_pembayaran_tindakan.tipe'				=> 'tipe',
		'o_s_pembayaran_tindakan.diskon'				=> 'diskon',
		'o_s_pembayaran_tindakan.rupiah'				=> 'rupiah',
		'o_s_pembayaran_tindakan.is_active'			=> 'is_active',
		'o_s_pembayaran_tindakan.is_pay'				=> 'is_pay',
		'tindakan_hd.no_transaksi'				=> 'no_transaksi',
		'tindakan_hd.penjamin_id'				=> 'penjamin_id',
		'poliklinik.id'					=> 'poliklinik_id',
		'poliklinik.nama'				=> 'nama_poli',
		'penjamin.nama'					=> 'nama_penjamin',
		'cabang.nama'					=> 'nama_cabang',
	);

	protected $datatable_columns_pilih_pembayaran = array(
		//column di table  => alias
		'pembayaran.id'				=> 'id',
		'pembayaran.nama'			=> 'nama',
		'pembayaran.tempat_lahir'	=> 'tempat_lahir',
		'pembayaran.tanggal_lahir'	=> 'tanggal_lahir',
		'pembayaran.is_active'		=> 'active',
		'pembayaran_alamat.alamat'	=> 'alamat'
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
		
		$join1 = array("tindakan_hd", $this->_table . '.tindakan_id = tindakan_hd.id', 'left');
		$join2 = array("penjamin", 'tindakan_hd.penjamin_id = penjamin.id', 'left');
		$join3 = array("poliklinik", $this->_table . '.poliklinik_id = poliklinik.id', 'left');
		$join4 = array("cabang", $this->_table . '.cabang_id = cabang.id', 'left');

		$join_tables = array($join1, $join2, $join3, $join4);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active', 1);
		// $this->db->where($this->_table.'.pasien_id', $pasien_id);
		// $this->db->where('pembayaran_alamat.is_primary', 1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active', 1);
		// $this->db->where($this->_table.'.pasien_id', $pasien_id);
		// $this->db->where('pembayaran_alamat.is_primary', 1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active', 1);
		// $this->db->where($this->_table.'.pasien_id', $pasien_id);
		// $this->db->where('pembayaran_alamat.is_primary', 1);

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

	public function get_datatable_history_pembayaran($status, $date)
	{

		$join1 = array('poliklinik a', $this->_table.'.poliklinik_tujuan_id = a.id','left');
		$join2 = array('poliklinik b', $this->_table.'.poliklinik_asal_id = b.id','left');
		$join3 = array('pasien', $this->_table.'.pasien_id = pasien.id','left');

		$join_tables = array($join1, $join2, $join3);

		$status_pembayaran = '';

		if($status == 1) 
		{
			$status_pembayaran = 1;

		} elseif ($status == 2) 
		{

			$status_pembayaran = 0;

		} elseif ($status == 3)
		{
		
			$status_pembayaran = 0;
		
		}

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);

		

		if($status == 0) 
		{

			$this->db->where('left(tanggal_pembayaran, 10) <', $date);
			$this->db->where('pembayaran.is_active', 1);
			$this->db->where('selesai', $status_pembayaran);
			$this->db->or_where('left(tanggal_pembayaran, 10) >=', $date);
			$this->db->where('selesai', $status_pembayaran);
			$this->db->where('pembayaran.is_active', 1);
			$this->db->or_where('left(tanggal_pembayaran, 10) =', $date);
			$this->db->where('selesai', $status_pembayaran);
			$this->db->where('pembayaran.is_active', 1);
			$this->db->or_where('left(tanggal_pembayaran, 10) !=', $date);
			$this->db->where('selesai', $status_pembayaran);
			$this->db->where('pembayaran.is_active', 1);


		} elseif ($status == 1) {

			$this->db->where('left(tanggal_pembayaran, 10) >=', $date);
			$this->db->where('pembayaran.is_active', 1);
			$this->db->where('selesai', 1);

		} elseif ($status == 2) {

			$this->db->where('left(tanggal_pembayaran, 10) <', $date);
			$this->db->where('pembayaran.is_active', 1);
			$this->db->where('selesai', 0);
			

		} elseif ($status == 3) {

			$this->db->where('left(tanggal_pembayaran, 10) >=', $date);
			$this->db->where('pembayaran.is_active', 1);
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

			$this->db->where('pembayaran.is_active', 1);
			$this->db->where('selesai', 1);
			$this->db->or_where('selesai', 0);
			$this->db->where('pembayaran.is_active', 1);

		} elseif ($status == 1) {

			$this->db->where('left(tanggal_pembayaran, 10) >=', $date);
			$this->db->where('pembayaran.is_active', 1);
			$this->db->where('selesai', 1);

		} elseif ($status == 2) {

			$this->db->where('left(tanggal_pembayaran, 10) <', $date);
			$this->db->where('pembayaran.is_active', 1);
			$this->db->where('selesai', 0);
			

		} elseif ($status == 3) {

			$this->db->where('left(tanggal_pembayaran, 10) >=', $date);
			$this->db->where('pembayaran.is_active', 1);
			$this->db->where('selesai', 0);

		}
		// $this->db->where('pembayaran_alamat.is_primary', 1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		

		if($status == 0) 
		{

			$this->db->where('pembayaran.is_active', 1);
			$this->db->where('selesai', 1);
			$this->db->or_where('selesai', 0);
			$this->db->where('pembayaran.is_active', 1);

		} elseif ($status == 1) {

			$this->db->where('left(tanggal_pembayaran, 10) >=', $date);
			$this->db->where('pembayaran.is_active', 1);
			$this->db->where('selesai', 1);

		} elseif ($status == 2) {

			$this->db->where('left(tanggal_pembayaran, 10) <', $date);
			$this->db->where('pembayaran.is_active', 1);
			$this->db->where('selesai', 0);
			

		} elseif ($status == 3) {

			$this->db->where('left(tanggal_pembayaran, 10) >=', $date);
			$this->db->where('pembayaran.is_active', 1);
			$this->db->where('selesai', 0);

		}
		// $this->db->where('pembayaran_alamat.is_primary', 1);

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

	public function get_datatable_pilih_pembayaran()
	{

		$join1 = array('pembayaran_alamat', $this->_table.'.id = pembayaran_alamat.pembayaran_id','left');
		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_pilih_pembayaran);

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
		foreach ($this->datatable_columns_pilih_pembayaran as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_pilih_pembayaran;
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
		$format = "SELECT MAX(SUBSTRING(`no_member`,16,4)) AS max_no_member FROM `pembayaran` WHERE SUBSTRING(`no_member`,14,2) = DATE_FORMAT(NOW(), '%y')";	
		return $this->db->query($format);
	}
}

/* End of file user_level_m.php */
/* Location: ./application/models/user_level_m.php */