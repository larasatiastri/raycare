<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembayaran_pasien_m extends MY_Model {

	protected $_table      = 'pembayaran';
	protected $_timestamps = true;
	protected $_order_by   = 'id';

	private $_fillable = array(
		'pasien_id',
		'dashboard_url', 
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'pembayaran.id'			=> 'id',
		'pembayaran.tindakan_id'	=> 'tindakan_id',
		'pembayaran.pasien_id'		=> 'pasien_id',
		'pembayaran.cabang_id'		=> 'cabang_id',
		'pembayaran.tipe'			=> 'tipe',
		'pembayaran.item_id'		=> 'item_id',
		'pembayaran.jumlah'		=> 'jumlah',
		'pembayaran.harga'			=> 'harga',
		'pembayaran.diskon'		=> 'diskon',
		'pembayaran.is_active'		=> 'is_active',
		'item.kode'							=> 'kode',
		'item.nama'							=> 'nama_item',
		'tindakan_hd.penjamin_id'			=> 'penjamin_id',
		'penjamin.nama'						=> 'nama_penjamin',
		// 'penjamin.nama'						=> 'nama_penjamin',
		'cabang.nama'						=> 'nama_cabang',
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
		
		$join1 = array("item", $this->_table . '.item_id = item.id', 'left');
		$join2 = array("tindakan_hd", $this->_table . '.tindakan_id = tindakan_hd.id', 'left');
		$join3 = array("penjamin", 'tindakan_hd.penjamin_id = penjamin.id', 'left');
		$join4 = array("cabang", $this->_table . '.cabang_id = cabang.id', 'left');

		$join_tables = array($join1, $join2, $join3,	 $join4);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'is_active', 1);
		// $this->db->where('pembayaran_alamat.is_primary', 1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active', 1);
		// $this->db->where('pembayaran_alamat.is_primary', 1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active', 1);
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

	public function get_datatable_setoran_invoice()
	{
		$datatable_columns_setoran_invoice = array(
		//column di table  => alias
		'pembayaran.id'				=> 'id',
		'pembayaran.pasien_id'		=> 'pasien_id',
		'pembayaran.cabang_id'		=> 'cabang_id',
		'pembayaran.bayar'			=> 'bayar',
		'pembayaran.tanggal'			=> 'tanggal',
		'pembayaran.diskon'			=> 'diskon',
		'pembayaran.no_faktur'			=> 'no_faktur',
		'pembayaran.is_active'		=> 'is_active',
		'pasien.nama'						=> 'nama_pasien',
		'cabang.nama'						=> 'nama_cabang',
		'kasir_biaya.keterangan'			=> 'keterangan',
		'kasir_biaya.kasir_terima_uang_id'	=> 'kasir_terima_uang_id',
		);

		$join1 = array("pasien", $this->_table . '.pasien_id = pasien.id', 'left');
		$join2 = array("cabang", $this->_table . '.cabang_id = cabang.id', 'left');	
		$join3 = array("kasir_biaya", $this->_table . '.id = kasir_biaya.id', 'left');	

		$join_tables = array($join1, $join2, $join3);

		// get params dari input postnya datatable
		$params = $this->datatable_param($datatable_columns_setoran_invoice);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.is_done', 0);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.is_done', 0);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.is_done', 0);

		// tentukan kolom yang mau diselect
		foreach ($datatable_columns_setoran_invoice as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $datatable_columns_setoran_invoice;
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

	public function get_nomor_faktur()
	{
		$format = "SELECT MAX(SUBSTRING(`no_pembayaran`,10,3)) AS max_nomor_faktur FROM `pembayaran` WHERE SUBSTRING(`no_pembayaran`,5,4) = DATE_FORMAT(NOW(), '%y%m')";	
		return $this->db->query($format);
	}

	public function get_by_trans_tipe($pembayaran_id, $tipe)
	{
		$this->db->select('no_transaksi, pembayaran_tindakan_pasien.tipe as tipe, (CASE tipe WHEN 1 THEN "HD" WHEN 2 THEN "UMUM" WHEN 3 THEN "MATA" WHEN 4 THEN "GIGI" END) as poli, penjamin.nama as penjamin, pembayaran_tindakan_pasien.harga as harga, pembayaran_tindakan_pasien.diskon as diskon, pembayaran_tindakan_pasien.harga_setelah_diskon as harga_setelah_diskon');
		$this->db->where('pembayaran_id', $pembayaran_id);
		$this->db->where('tipe', $tipe);

		if($tipe == 1)
		{
			$this->db->join('tindakan_hd', $this->_table.'.tindakan_id = tindakan_hd.id','left');
		}
		if($tipe == 2)
		{
			$this->db->join('tindakan_umum', $this->_table.'.tindakan_id = tindakan_umum.id','left');
		}
		if($tipe == 3)
		{
			$this->db->join('tindakan_mata', $this->_table.'.tindakan_id = tindakan_mata.id','left');
		}
		if($tipe == 4)
		{
			$this->db->join('tindakan_gigi', $this->_table.'.tindakan_id = tindakan_gigi.id','left');
		}

		$this->db->join('penjamin',$this->_table.'.penjamin_id = penjamin.id','left');
		return $this->db->get($this->_table);
	}

}

/* End of file pembayaran_level_m.php */
/* Location: ./application/models/pembayaran_level_m.php */