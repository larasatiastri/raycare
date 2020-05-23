<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembayaran_detail_m extends MY_Model {

	protected $_table      = 'pembayaran_detail';
	protected $_timestamps = true;
	protected $_order_by   = 'id';

	private $_fillable = array(
		'pasien_id',
		'dashboard_url', 
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'pembayaran_detail.id'             => 'id',
		'pembayaran_detail.created_date'   => 'tanggal',
		'pembayaran_detail.waktu_tindakan' => 'waktu',
		'pembayaran_detail.no_invoice'     => 'no_invoice',
		'user.nama'                        => 'nama_resepsionis',
		'pembayaran_detail.nama_penjamin'  => 'nama_penjamin',
		'pasien.nama'                      => 'nama_pasien',
		'pembayaran_detail.harga'          => 'harga',
		'pembayaran_detail.pasien_id'      => 'pasien_id',
	);

	protected $datatable_columns_setoran = array(
		//column di table  => alias
		'pembayaran_detail.id'            => 'id',
		'pembayaran_detail.pembayaran_id' => 'pembayaran_id',
		'pembayaran_detail.invoice_id'    => 'invoice_id',
		'invoice.no_invoice'              => 'no_invoice',
		'invoice.created_date'            => 'tanggal_invoice',
		'invoice.nama_pasien'             => 'nama_pasien',
		'invoice.harga'                   => 'harga',
		'pembayaran_detail.total_bayar'   => 'total_bayar'
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
		
		$join1 = array("pasien", $this->_table . '.pasien_id = pasien.id', 'left');
		$join2 = array("user", $this->_table . '.created_by = user.id', 'left');

		$join_tables = array($join1,$join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = 'pembayaran_detail.no_invoice';
		$params['sort_dir'] = 'desc';
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

	public function get_datatable_laporan($bulan,$tahun,$user_id='',$shift='',$penjamin='')
	{
		
		$join1 = array("pasien", $this->_table . '.pasien_id = pasien.id', 'left');
		$join2 = array("user", $this->_table . '.created_by = user.id', 'left');

		$join_tables = array($join1,$join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active', 1);
		if ($bulan!=NULL && $tahun!=NULL) {
			$this->db->where('MONTH(pembayaran_detail.created_date)', $bulan);
			$this->db->where('YEAR(pembayaran_detail.created_date)', $tahun);	
		}
		if ($user_id!='0') {
			$this->db->where($this->_table.'.created_by', $user_id);	
		}
		if ($shift!='0') {
			$this->db->where($this->_table.'.shift', $shift);	
		}
		if ($penjamin!='0') {
			$this->db->where($this->_table.'.penjamin_id', $penjamin);	
		}
		// $this->db->where($this->_table.'.pasien_id', $pasien_id);
		// $this->db->where('pembayaran_alamat.is_primary', 1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active', 1);
		if ($bulan!=NULL || $tahun!=NULL) {
			$this->db->where('MONTH(pembayaran_detail.created_date)', $bulan);
			$this->db->where('YEAR(pembayaran_detail.created_date)', $tahun);	
		}
		if ($user_id!='0') {
			$this->db->where($this->_table.'.created_by', $user_id);	
		}
		if ($shift!='0') {
			$this->db->where($this->_table.'.shift', $shift);	
		}
		if ($penjamin!='0') {
			$this->db->where($this->_table.'.penjamin_id', $penjamin);	
		}
		// $this->db->where($this->_table.'.pasien_id', $pasien_id);
		// $this->db->where('pembayaran_alamat.is_primary', 1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active', 1);
		if ($bulan!=NULL || $tahun!=NULL) {
			$this->db->where('MONTH(pembayaran_detail.created_date)', $bulan);
			$this->db->where('YEAR(pembayaran_detail.created_date)', $tahun);	
		}
		if ($user_id!='0') {
			$this->db->where($this->_table.'.created_by', $user_id);	
		}
		if ($shift!='0') {
			$this->db->where($this->_table.'.shift', $shift);	
		}
		if ($penjamin!='0') {
			$this->db->where($this->_table.'.penjamin_id', $penjamin);	
		}
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

	public function get_datatable_setoran($id_bayar)
	{
		
		$join1 = array("invoice", $this->_table . '.invoice_id = invoice.id');

		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_setoran);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where_in($this->_table.'.pembayaran_id', $id_bayar);
		$this->db->where('invoice.jenis_invoice',1);
		
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where_in($this->_table.'.pembayaran_id', $id_bayar);
		$this->db->where('invoice.jenis_invoice',1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where_in($this->_table.'.pembayaran_id', $id_bayar);
		$this->db->where('invoice.jenis_invoice',1);

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_setoran as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_setoran;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		return $result; 
	}
	public function get_datatable_setoran_view($id_bayar)
	{
		
		$join1 = array("invoice", $this->_table . '.invoice_id = invoice.id');

		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_setoran);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where_in($this->_table.'.pembayaran_id', $id_bayar);
		//$this->db->where_in('invoice.jenis_invoice',1);
		
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where_in($this->_table.'.pembayaran_id', $id_bayar);
		//$this->db->where_in('invoice.jenis_invoice',1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where_in($this->_table.'.pembayaran_id', $id_bayar);
		//$this->db->where_in('invoice.jenis_invoice',1);

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_setoran as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_setoran;
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

	public function get_data_detail($pembayaran_id)
	{
		$format = "SELECT
						pembayaran_detail.id,
						pembayaran_detail.no_tindakan,
						sep_tindakan.id,
						sep_tindakan.pasien_id,
						sep_tindakan.poliklinik_id,
						sep_tindakan.tipe_tindakan,
						sep_tindakan.no_sep,
						sep_tindakan.tanggal_sep,
						sep_tindakan.diagnosa_awal,
						sep_tindakan.jenis_peserta,
						sep_tindakan.cob,
						sep_tindakan.jenis_rawat,
						sep_tindakan.kelas_rawat,
						sep_tindakan.catatan,
						pasien.nama AS nama_pasien,
						pasien.tanggal_lahir,
						pasien.gender,
						icd_code.code_ast,
						icd_code.`name` AS nama_diagnosa,
						pasien.ref_kode_rs_rujukan,
						pasien_penjamin.no_kartu,
						pembayaran_detail.nama_poliklinik
					FROM
						pembayaran_detail
					LEFT JOIN sep_tindakan ON pembayaran_detail.tindakan_id = sep_tindakan.tindakan_id
					LEFT JOIN pasien ON pembayaran_detail.pasien_id = pasien.id
					LEFT JOIN icd_code ON sep_tindakan.diagnosa_awal = icd_code.code_ast
					LEFT JOIN pasien_penjamin ON pembayaran_detail.pasien_id = pasien_penjamin.pasien_id
					AND pembayaran_detail.penjamin_id = pasien_penjamin.penjamin_id
					WHERE
						pembayaran_detail.pembayaran_id = $pembayaran_id
					AND sep_tindakan.tipe_tindakan = 1
					AND pasien_penjamin.is_active = 1
					";

		return $this->db->query($format);
	}

	public function get_nomor_invoice()
	{
		$format = "SELECT MAX(SUBSTRING(`no_invoice`,12,6)) AS max_nomor_invoice FROM `pembayaran_detail` WHERE SUBSTRING(`no_invoice`,1,4) = DATE_FORMAT(NOW(), '%Y')";	
		return $this->db->query($format);
	}
	
}
