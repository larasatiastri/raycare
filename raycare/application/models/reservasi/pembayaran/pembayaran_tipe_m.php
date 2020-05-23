<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembayaran_tipe_m extends MY_Model {

	protected $_table      = 'pembayaran_tipe';
	protected $_timestamps = true;
	protected $_order_by   = 'id';

	private $_fillable = array(
		'pasien_id',
		'dashboard_url', 
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'invoice.no_invoice'            => 'no_invoice',
		'invoice.created_date'          => 'tanggal_invoice',
		'pembayaran.tanggal'            => 'tanggal_bayar',
		'invoice.nama_pasien'           => 'nama_pasien',
		'invoice.harga'                 => 'harga',
		'pembayaran_tipe.rupiah'        => 'total_bayar',
		'pembayaran_tipe.id'            => 'id',
		'invoice.id'                    => 'invoice_id',
		'invoice.jenis_invoice'                    => 'jenis_invoice',
		'invoice.nama_penjamin'                    => 'nama_penjamin',
		'invoice.created_date'                    => 'tanggal',
		'invoice.shift'                    => 'shift_inv',
		'pembayaran_tipe.pembayaran_id' => 'pembayaran_id',
		'pembayaran_tipe.tipe_bayar'    => 'tipe_bayar',
		'pembayaran_tipe.mesin_edc_id'  => 'mesin_edc_id',
		'pembayaran_tipe.jenis_kartu'   => 'jenis_kartu',
		'pembayaran_tipe.rupiah'   => 'rupiah',
		'mesin_edc.nama'                => 'nama_mesin',	
	);

	protected $datatable_columns_pilih_pembayaran = array(
		//column di table  => alias
		'pembayaran_tipe.id'				=> 'id',
		'pembayaran_tipe.nama'			=> 'nama',
		'pembayaran_tipe.tempat_lahir'	=> 'tempat_lahir',
		'pembayaran_tipe.tanggal_lahir'	=> 'tanggal_lahir',
		'pembayaran_tipe.is_active'		=> 'active',
		'pembayaran_tipe_alamat.alamat'	=> 'alamat'
	);

	function __construct()
	{
		parent::__construct();
	}

		/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($tipe_bayar, $tanggal, $shift,$status)
	{
		
		$join1 = array("pembayaran", $this->_table . '.pembayaran_id = pembayaran.id', 'left');
		$join2 = array("mesin_edc", $this->_table . '.mesin_edc_id = mesin_edc.id', 'left');
		$join3 = array("pembayaran_detail", 'pembayaran.id = pembayaran_detail.pembayaran_id', 'left');
		$join4 = array("invoice", 'pembayaran_detail.invoice_id = invoice.id', 'left');

		$join_tables = array($join1, $join2, $join3, $join4);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.tipe_bayar', $tipe_bayar);
		$this->db->where('date(pembayaran.tanggal)', $tanggal);
		$this->db->where('date(invoice.created_date)', $tanggal);
		$this->db->where('pembayaran.shift', $shift);
		$this->db->where('pembayaran.status', $status);
		$this->db->where('invoice.jenis_invoice', 1);
		$this->db->where('invoice.penjamin_id', 1);

		// $this->db->where('pembayaran_alamat.is_primary', 1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.tipe_bayar', $tipe_bayar);
		$this->db->where('date(pembayaran.tanggal)', $tanggal);
		$this->db->where('date(invoice.created_date)', $tanggal);
		$this->db->where('pembayaran.shift', $shift);
		$this->db->where('pembayaran.status', $status);
		$this->db->where('invoice.jenis_invoice', 1);
		$this->db->where('invoice.penjamin_id', 1);
		// $this->db->where('pembayaran_alamat.is_primary', 1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.tipe_bayar', $tipe_bayar);
		$this->db->where('date(pembayaran.tanggal)', $tanggal);
		$this->db->where('date(invoice.created_date)', $tanggal);
		$this->db->where('pembayaran.shift', $shift);
		$this->db->where('pembayaran.status', $status);
		$this->db->where('invoice.jenis_invoice', 1);
		$this->db->where('invoice.penjamin_id', 1);
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

	public function get_datatable_non($tipe_bayar, $tanggal, $shift,$status)
	{
		
		$join1 = array("pembayaran", $this->_table . '.pembayaran_id = pembayaran.id', 'left');
		$join2 = array("mesin_edc", $this->_table . '.mesin_edc_id = mesin_edc.id', 'left');
		$join3 = array("pembayaran_detail", 'pembayaran.id = pembayaran_detail.pembayaran_id', 'left');
		$join4 = array("invoice", 'pembayaran_detail.invoice_id = invoice.id', 'left');

		$join_tables = array($join1, $join2, $join3, $join4);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.tipe_bayar', $tipe_bayar);
		$this->db->where('date(pembayaran.tanggal)', $tanggal);
		$this->db->where('date(invoice.created_date) !=', $tanggal);
		$this->db->where('pembayaran.shift', $shift);
		$this->db->where('pembayaran.status', $status);
		$this->db->where('invoice.jenis_invoice', 1);
		$this->db->where('invoice.penjamin_id', 1);
		// $this->db->where('pembayaran_alamat.is_primary', 1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.tipe_bayar', $tipe_bayar);
		$this->db->where('date(pembayaran.tanggal)', $tanggal);
		$this->db->where('date(invoice.created_date) !=', $tanggal);
		$this->db->where('pembayaran.shift', $shift);
		$this->db->where('pembayaran.status', $status);
		$this->db->where('invoice.jenis_invoice', 1);
		$this->db->where('invoice.penjamin_id', 1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.tipe_bayar', $tipe_bayar);
		$this->db->where('date(pembayaran.tanggal)', $tanggal);
		$this->db->where('date(invoice.created_date) !=', $tanggal);
		$this->db->where('pembayaran.shift', $shift);
		$this->db->where('pembayaran.status', $status);
		$this->db->where('invoice.jenis_invoice', 1);
		$this->db->where('invoice.penjamin_id', 1);
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

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable_edc($bank_id,$tipe_bayar, $tanggal, $shift,$status)
	{
		
		$join1 = array("pembayaran", $this->_table . '.pembayaran_id = pembayaran.id', 'left');
		$join2 = array("mesin_edc", $this->_table . '.mesin_edc_id = mesin_edc.id', 'left');
		$join3 = array("pembayaran_detail", 'pembayaran.id = pembayaran_detail.pembayaran_id', 'left');
		$join4 = array("invoice", 'pembayaran_detail.invoice_id = invoice.id', 'left');

		$join_tables = array($join1, $join2, $join3, $join4);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.tipe_bayar', $tipe_bayar);
		$this->db->where('date(pembayaran.tanggal)', $tanggal);
		$this->db->where('date(invoice.created_date)', $tanggal);
		$this->db->where('pembayaran.shift', $shift);
		$this->db->where('mesin_edc.bank_id', $bank_id);
		$this->db->where('pembayaran.status', $status);
		// $this->db->where('pembayaran_alamat.is_primary', 1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.tipe_bayar', $tipe_bayar);
		$this->db->where('date(pembayaran.tanggal)', $tanggal);
		$this->db->where('date(invoice.created_date)', $tanggal);
		$this->db->where('pembayaran.shift', $shift);
		$this->db->where('mesin_edc.bank_id', $bank_id);
		$this->db->where('pembayaran.status', $status);
		// $this->db->where('pembayaran_alamat.is_primary', 1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.tipe_bayar', $tipe_bayar);
		$this->db->where('date(pembayaran.tanggal)', $tanggal);
		$this->db->where('date(invoice.created_date)', $tanggal);
		$this->db->where('pembayaran.shift', $shift);
		$this->db->where('mesin_edc.bank_id', $bank_id);
		$this->db->where('pembayaran.status', $status);
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

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable_edc_non($bank_id,$tipe_bayar, $tanggal, $shift,$status)
	{
		
		$join1 = array("pembayaran", $this->_table . '.pembayaran_id = pembayaran.id', 'left');
		$join2 = array("mesin_edc", $this->_table . '.mesin_edc_id = mesin_edc.id', 'left');
		$join3 = array("pembayaran_detail", 'pembayaran.id = pembayaran_detail.pembayaran_id', 'left');
		$join4 = array("invoice", 'pembayaran_detail.invoice_id = invoice.id', 'left');

		$join_tables = array($join1, $join2, $join3, $join4);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.tipe_bayar', $tipe_bayar);
		$this->db->where('date(pembayaran.tanggal)', $tanggal);
		$this->db->where('date(invoice.created_date) !=', $tanggal);
		$this->db->where('pembayaran.shift', $shift);
		$this->db->where('mesin_edc.bank_id', $bank_id);
		$this->db->where('pembayaran.status', $status);
		// $this->db->where('pembayaran_alamat.is_primary', 1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.tipe_bayar', $tipe_bayar);
		$this->db->where('date(pembayaran.tanggal)', $tanggal);
		$this->db->where('date(invoice.created_date) !=', $tanggal);
		$this->db->where('pembayaran.shift', $shift);
		$this->db->where('mesin_edc.bank_id', $bank_id);
		$this->db->where('pembayaran.status', $status);
		// $this->db->where('pembayaran_alamat.is_primary', 1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.tipe_bayar', $tipe_bayar);
		$this->db->where('date(pembayaran.tanggal)', $tanggal);
		$this->db->where('date(invoice.created_date) !=', $tanggal);
		$this->db->where('pembayaran.shift', $shift);
		$this->db->where('mesin_edc.bank_id', $bank_id);
		$this->db->where('pembayaran.status', $status);
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

	public function get_datatable_invoice_pembayaran($tipe_bayar,$penjamin_id, $shift, $tanggal)
	{
		
		$join1 = array("pembayaran", $this->_table . '.pembayaran_id = pembayaran.id', 'left');
		$join2 = array("mesin_edc", $this->_table . '.mesin_edc_id = mesin_edc.id', 'left');
		$join3 = array("pembayaran_detail", 'pembayaran.id = pembayaran_detail.pembayaran_id', 'left');
		$join4 = array("invoice", 'pembayaran_detail.invoice_id = invoice.id', 'left');

		$join_tables = array($join1, $join2, $join3, $join4);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		if($tipe_bayar != 0) $this->db->where($this->_table.'.tipe_bayar', $tipe_bayar);
		$this->db->where('date(pembayaran.tanggal)', $tanggal);
		if($tipe_bayar != 0) $this->db->where('date(invoice.created_date)', $tanggal);
		if($tipe_bayar == 0) $this->db->where('date(invoice.created_date) !=', $tanggal);
		if($shift != 0)$this->db->where('pembayaran.shift', $shift);
		$this->db->where('invoice.jenis_invoice', 1);
		
		// $this->db->where('pembayaran_alamat.is_primary', 1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		if($tipe_bayar != 0) $this->db->where($this->_table.'.tipe_bayar', $tipe_bayar);
		$this->db->where('date(pembayaran.tanggal)', $tanggal);
		if($tipe_bayar != 0) $this->db->where('date(invoice.created_date)', $tanggal);
		if($tipe_bayar == 0) $this->db->where('date(invoice.created_date) !=', $tanggal);
		if($shift != 0)$this->db->where('pembayaran.shift', $shift);
		$this->db->where('invoice.jenis_invoice', 1);
		
		// $this->db->where('pembayaran_alamat.is_primary', 1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		if($tipe_bayar != 0) $this->db->where($this->_table.'.tipe_bayar', $tipe_bayar);
		$this->db->where('date(pembayaran.tanggal)', $tanggal);
		if($tipe_bayar != 0) $this->db->where('date(invoice.created_date)', $tanggal);
		if($tipe_bayar == 0) $this->db->where('date(invoice.created_date) !=', $tanggal);
		if($shift != 0)$this->db->where('pembayaran.shift', $shift);
		$this->db->where('invoice.jenis_invoice', 1);
		
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

	/**
	 * [fillable_add description]
	 * @return [type] [description]
	 */
	public function fillable()
	{
		return $this->_fillable;
	}


	public function get_data_mesin($pembayaran_id)
	{
		$this->db->select('mesin_edc.nama, pembayaran_tipe.rupiah');
		$this->db->where('pembayaran_id', $pembayaran_id);
		$this->db->join('mesin_edc', $this->_table.'.mesin_edc_id = mesin_edc.id','left');

		return $this->db->get($this->_table);

	}

	public function get_total_bayar($tipe,$penjamin_id ,$shift, $tanggal='')
	{
		$SQL = "SELECT
					SUM(pembayaran_tipe.rupiah) AS total_bayar
				FROM
					pembayaran_tipe
				LEFT JOIN pembayaran ON pembayaran_tipe.pembayaran_id = pembayaran.id
				LEFT JOIN pembayaran_detail ON pembayaran.id = pembayaran_detail.pembayaran_id
				LEFT JOIN invoice ON pembayaran_detail.invoice_id = invoice.id
				WHERE
					pembayaran_tipe.tipe_bayar = '$tipe'

				AND date(pembayaran.tanggal) = '$tanggal'
				AND date(invoice.created_date) = '$tanggal'
				AND invoice.jenis_invoice = 1";

		if($shift != 0){
			$SQL .= " AND pembayaran.shift = $shift";
		}if($penjamin_id != 0){
			$SQL .= " AND invoice.penjamin_id = $penjamin_id";
		}

		return $this->db->query($SQL);
				
				
	}


}

/* End of file pembayaran_level_m.php */
/* Location: ./application/models/pembayaran_level_m.php */