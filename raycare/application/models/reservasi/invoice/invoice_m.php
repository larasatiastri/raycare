<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Invoice_m extends MY_Model {

	protected $_table      = 'invoice';
	protected $_timestamps = true;
	protected $_order_by   = 'id';

	private $_fillable = array(
		'pasien_id',
		'dashboard_url', 
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'invoice.created_date'   => 'tanggal',
		'invoice.waktu_tindakan' => 'waktu',
		'invoice.no_invoice'     => 'no_invoice',
		'invoice.jenis_invoice'     	 => 'jenis_invoice',
		'invoice.nama_penjamin'  => 'nama_penjamin',
		'invoice.nama_pasien'    => 'nama_pasien',
		'user.nama'              => 'nama_resepsionis',
		'invoice.harga'          => 'harga',
		'invoice.sisa_bayar'     => 'sisa_bayar',
		'invoice.pasien_id'      => 'pasien_id',
		'invoice.penjamin_id'      => 'penjamin_id',
		'invoice.status'     	 => 'status',
		'invoice.id'             => 'id',
		'invoice.akomodasi'             => 'akomodasi',
	);


	protected $datatable_columns_laporan = array(
		//column di table  => alias
		'invoice.id'             => 'id',
		'invoice.created_date'   => 'tanggal',
		'invoice.waktu_tindakan' => 'waktu',
		'invoice.no_invoice'     => 'no_invoice',
		'user.nama'              => 'nama_resepsionis',
		'invoice.nama_penjamin'  => 'nama_penjamin',
		'pasien.no_member'            => 'no_member',
		'pasien.nama'            => 'nama_pasien',
		'invoice.harga'          => 'harga',
		'invoice.pasien_id'      => 'pasien_id',
		'invoice.sisa_bayar'     => 'sisa_bayar',
		'invoice.penjamin_id'      => 'penjamin_id',
		'invoice.status'     	 => 'status',
		'invoice.jenis_invoice'     	 => 'jenis_invoice',
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
		
		$join1 = array("user", $this->_table . '.created_by = user.id', 'left');

		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = $this->_table.'.no_invoice';
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

		/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable_belum_setor()
	{
		
		$join1 = array("user", $this->_table . '.created_by = user.id', 'left');

		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = $this->_table.'.no_invoice';
		$params['sort_dir'] = 'desc';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.status', 2);
		$this->db->where($this->_table.'.id NOT IN (SELECT invoice_id FROM setoran_kasir_detail WHERE invoice_id IS NOT NULL)');
		$this->db->where('date(invoice.created_date) <=', date('Y-m-d'));
		$this->db->where('date(invoice.created_date) >=', date('Y-m-d', strtotime('-2 days')));
		// $this->db->where($this->_table.'.pasien_id', $pasien_id);
		// $this->db->where('pembayaran_alamat.is_primary', 1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.status', 2);
		$this->db->where($this->_table.'.id NOT IN (SELECT invoice_id FROM setoran_kasir_detail WHERE invoice_id IS NOT NULL)');
		$this->db->where('date(invoice.created_date) <=', date('Y-m-d'));
		$this->db->where('date(invoice.created_date) >=', date('Y-m-d', strtotime('-2 days')));
		// $this->db->where($this->_table.'.pasien_id', $pasien_id);
		// $this->db->where('pembayaran_alamat.is_primary', 1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.status', 2);
		$this->db->where($this->_table.'.id NOT IN (SELECT invoice_id FROM setoran_kasir_detail WHERE invoice_id IS NOT NULL)');
		$this->db->where('date(invoice.created_date) <=', date('Y-m-d'));
		$this->db->where('date(invoice.created_date) >=', date('Y-m-d', strtotime('-2 days')));
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

	public function get_datatable_laporan($tgl_awal,$tgl_akhir,$user_id='',$shift='',$penjamin='')
	{
		
		$join1 = array("pasien", $this->_table . '.pasien_id = pasien.id', 'left');
		$join2 = array("user", $this->_table . '.created_by = user.id', 'left');

		$join_tables = array($join1,$join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_laporan);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active', 1);
		if ($tgl_awal!=NULL && $tgl_akhir!=NULL) {
			$this->db->where("DATE(invoice.created_date) >= ", $tgl_awal);
			$this->db->where("DATE(invoice.created_date) <= ", $tgl_akhir);
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
		if ($tgl_awal!=NULL || $tgl_akhir!=NULL) {
			$this->db->where("DATE(invoice.created_date) >= ", $tgl_awal);
			$this->db->where("DATE(invoice.created_date) <= ", $tgl_akhir);
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
		if ($tgl_awal!=NULL || $tgl_akhir!=NULL) {
			$this->db->where("DATE(invoice.created_date) >= ", $tgl_awal);
			$this->db->where("DATE(invoice.created_date) <= ", $tgl_akhir);
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
		foreach ($this->datatable_columns_laporan as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_laporan;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		return $result; 
	}

	public function get_datatable_hutang($tgl_awal,$tgl_akhir,$shift)
	{
		
		$join1 = array("user", $this->_table . '.created_by = user.id', 'left');

		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = 'invoice.no_invoice';
		$params['sort_dir'] = 'asc';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('invoice.status',3);
		$this->db->where('invoice.penjamin_id',1);
		$this->db->where('invoice.is_active',1);
		$this->db->where('invoice.sisa_bayar >',0);


		if ($tgl_awal!='0') {
	  		$this->db->where("DATE(invoice.created_date) >= ", $tgl_awal);
	 	}
	 	if ($tgl_akhir!='0') {
			$this->db->where("DATE(invoice.created_date) <= ", $tgl_akhir);
		}
		if ($shift!='0') {
			$this->db->where($this->_table.'.shift', $shift);	
		}
		$this->db->or_where('invoice.status',1);
		$this->db->where('invoice.penjamin_id',1);
		$this->db->where('invoice.is_active',1);
		$this->db->where('invoice.sisa_bayar >',0);
	  	if ($tgl_awal!='0') {
	  		$this->db->where("DATE(invoice.created_date) >= ", $tgl_awal);
	 	}
	 	if ($tgl_akhir!='0') {
			$this->db->where("DATE(invoice.created_date) <= ", $tgl_akhir);
		}
		if ($shift!='0') {
			$this->db->where($this->_table.'.shift', $shift);	
		}
		// $this->db->where($this->_table.'.pasien_id', $pasien_id);
		// $this->db->where('pembayaran_alamat.is_primary', 1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('invoice.status',3);
		$this->db->where('invoice.penjamin_id',1);
		$this->db->where('invoice.is_active',1);
		$this->db->where('invoice.sisa_bayar >',0);
	  	if ($tgl_awal!='0') {
	  		$this->db->where("DATE(invoice.created_date) >= ", $tgl_awal);
	 	}
	 	if ($tgl_akhir!='0') {
			$this->db->where("DATE(invoice.created_date) <= ", $tgl_akhir);
		}
		if ($shift!='0') {
			$this->db->where($this->_table.'.shift', $shift);	
		}
		$this->db->or_where('invoice.status',1);
		$this->db->where('invoice.penjamin_id',1);
		$this->db->where('invoice.is_active',1);
		$this->db->where('invoice.sisa_bayar >',0);
	  	if ($tgl_awal!='0') {
	  		$this->db->where("DATE(invoice.created_date) >= ", $tgl_awal);
	 	}
	 	if ($tgl_akhir!='0') {
			$this->db->where("DATE(invoice.created_date) <= ", $tgl_akhir);
		}
		if ($shift!='0') {
			$this->db->where($this->_table.'.shift', $shift);	
		}
		// $this->db->where($this->_table.'.pasien_id', $pasien_id);
		// $this->db->where('pembayaran_alamat.is_primary', 1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('invoice.status',3);
		$this->db->where('invoice.penjamin_id',1);
		$this->db->where('invoice.is_active',1);
		$this->db->where('invoice.sisa_bayar >',0);
	  	if ($tgl_awal!='0') {
	  		$this->db->where("DATE(invoice.created_date) >= ", $tgl_awal);
	 	}
	 	if ($tgl_akhir!='0') {
			$this->db->where("DATE(invoice.created_date) <= ", $tgl_akhir);
		}
		if ($shift!='0') {
			$this->db->where($this->_table.'.shift', $shift);	
		}
		$this->db->or_where('invoice.status',1);
		$this->db->where('invoice.penjamin_id',1);
		$this->db->where('invoice.is_active',1);
		$this->db->where('invoice.sisa_bayar >',0);
	  	if ($tgl_awal!='0') {
	  		$this->db->where("DATE(invoice.created_date) >= ", $tgl_awal);
	 	}
	 	if ($tgl_akhir!='0') {
			$this->db->where("DATE(invoice.created_date) <= ", $tgl_akhir);
		}
		if ($shift!='0') {
			$this->db->where($this->_table.'.shift', $shift);	
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
						invoice.id,
						invoice.no_tindakan,
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
						invoice.nama_poliklinik
					FROM
						invoice
					LEFT JOIN sep_tindakan ON invoice.tindakan_id = sep_tindakan.tindakan_id
					LEFT JOIN pasien ON invoice.pasien_id = pasien.id
					LEFT JOIN icd_code ON sep_tindakan.diagnosa_awal = icd_code.code_ast
					LEFT JOIN pasien_penjamin ON invoice.pasien_id = pasien_penjamin.pasien_id
					AND invoice.penjamin_id = pasien_penjamin.penjamin_id
					WHERE
						invoice.pembayaran_id = $pembayaran_id
					AND sep_tindakan.tipe_tindakan = 1
					AND pasien_penjamin.is_active = 1
					";

		return $this->db->query($format);
	}

	public function get_nomor_invoice()
	{
		$format = "SELECT MAX(SUBSTRING(`no_invoice`,12,6)) AS max_nomor_invoice FROM `invoice` WHERE SUBSTRING(`no_invoice`,1,4) = DATE_FORMAT(NOW(), '%Y')";	
		return $this->db->query($format);
	}

	public function get_data_tagihan($pasien_id)
	{
		$this->db->where('pasien_id', $pasien_id);
		$this->db->where('status', 1);
		$this->db->or_where('pasien_id', $pasien_id);
		$this->db->where('status', 3);
		$this->db->order_by('no_invoice','desc');

		return $this->db->get($this->_table);
	}

	public function get_data_invoice($pasien_id)
	{

		$this->db->select('invoice.id AS id, invoice.created_date AS tanggal, invoice.waktu_tindakan AS waktu, invoice.no_invoice AS no_invoice, invoice.jenis_invoice AS jenis_invoice, invoice.akomodasi AS akomodasi, user.nama AS nama_resepsionis, cabang.nama AS nama_cabang, invoice.nama_penjamin AS nama_penjamin, invoice.nama_pasien AS nama_pasien, invoice.harga AS harga, invoice.sisa_bayar AS sisa_bayar, invoice.pasien_id AS pasien_id');
		$this->db->join('user', $this->_table.'.created_by = user.id','left');
		$this->db->join('cabang', $this->_table.'.cabang_id = cabang.id','left');
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.penjamin_id', 1);
		$this->db->where($this->_table.'.status', 1);
		$this->db->where($this->_table.'.pasien_id', $pasien_id);
		$this->db->or_where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.penjamin_id', 1);
		$this->db->where($this->_table.'.status', 3);
		$this->db->where($this->_table.'.pasien_id', $pasien_id);

		$this->db->order_by('no_invoice','desc');
		return $this->db->get($this->_table);

	}

	public function get_data_invoice_nama($nama_pasien, $tanggal)
	{

		$SQL ="SELECT invoice.id AS id, invoice.created_date AS tanggal, invoice.waktu_tindakan AS waktu, invoice.no_invoice AS no_invoice, invoice.jenis_invoice AS jenis_invoice, invoice.akomodasi AS akomodasi, user.nama AS nama_resepsionis, cabang.nama AS nama_cabang, invoice.nama_penjamin AS nama_penjamin, invoice.nama_pasien AS nama_pasien, invoice.harga AS harga, invoice.sisa_bayar AS sisa_bayar, invoice.pasien_id AS pasien_id
				FROM invoice
				LEFT JOIN user ON invoice.created_by = user.id
				LEFT JOIN cabang ON invoice.cabang_id = cabang.id
				WHERE invoice.is_active =  1
				AND invoice.penjamin_id =  1
				AND invoice.status =  1
				AND  invoice.nama_pasien  LIKE '%$nama_pasien%'
				OR invoice.is_active =  1
				AND invoice.penjamin_id =  1
				AND invoice.status =  3
				AND  invoice.nama_pasien  LIKE '%$nama_pasien%'
				";


		return $this->db->query($SQL);

	}
	public function get_pasien_tdk_terdaftar()
	{
		# SELECT * FROM `invoice` WHERE tipe_pasien = 2 AND pasien_id = 0 AND `status` = 1 OR  tipe_pasien = 2 AND pasien_id = 0 AND `status` = 3 GROUP BY nama_pasien;
	
		$this->db->where('tipe_pasien',2);
		$this->db->where('pasien_id',0);
		$this->db->where('status',1);
		$this->db->or_where('tipe_pasien',2);
		$this->db->where('pasien_id',0);
		$this->db->where('status',3);
		$this->db->group_by('nama_pasien');

		return $this->db->get($this->_table);
	}

	public function get_total($penjamin_id,$shift,$tanggal)
	{
		$SQL = "SELECT SUM((invoice_detail.qty * invoice_detail.harga ) - invoice_detail.diskon_nominal ) AS total FROM `invoice_detail` WHERE invoice_detail.is_active = 1 AND invoice_id IN (SELECT invoice.id FROM `invoice` WHERE date(created_date) = '".$tanggal."'";

		if($penjamin_id != 0){
			$SQL .= "  AND penjamin_id =" .$penjamin_id;
		}if($shift != 0){
			$SQL .=" AND shift = ". $shift;
		}
		
		$SQL .= " )";
		return $this->db->query($SQL);

	}

	public function get_hutang($shift,$tanggal)
	{
		$SQL = "SELECT SUM(sisa_bayar) as total FROM `invoice` WHERE date(created_date) = '".$tanggal."' AND status = 1 AND penjamin_id = 1";

		if($shift != 0){
			$SQL .=" AND shift = ". $shift;
		}

		$SQL .= " OR date(created_date) = '".$tanggal."' AND status = 3 AND penjamin_id = 1 ";

		if($shift != 0){
			$SQL .=" AND shift = ". $shift;
		}
		
		return $this->db->query($SQL);

	}

	public function get_id_invoice()
	{
		$format = "SELECT MAX(RIGHT(`id`,4)) AS max_id FROM `invoice` WHERE SUBSTRING(`id`,4,7) = DATE_FORMAT(NOW(), '%m-%Y')";	
		return $this->db->query($format);
	}

	public function get_datatable_laporan_rekap($tgl_awal,$tgl_akhir,$shift='',$penjamin='')
	{
		
		$join1 = array("pasien", $this->_table . '.pasien_id = pasien.id', 'left');
		$join2 = array("user", $this->_table . '.created_by = user.id', 'left');

		$join_tables = array($join1,$join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_laporan);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.tindakan_id', NULL);
		$this->db->where($this->_table.'.is_active', 1);
		if ($tgl_awal!=NULL && $tgl_akhir!=NULL) {
			$this->db->where("DATE(invoice.created_date) >= ", $tgl_awal);
			$this->db->where("DATE(invoice.created_date) <= ", $tgl_akhir);
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
		$this->db->where($this->_table.'.tindakan_id', NULL);
		$this->db->where($this->_table.'.is_active', 1);
		if ($tgl_awal!=NULL || $tgl_akhir!=NULL) {
			$this->db->where("DATE(invoice.created_date) >= ", $tgl_awal);
			$this->db->where("DATE(invoice.created_date) <= ", $tgl_akhir);
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
		$this->db->where($this->_table.'.tindakan_id', NULL);
		$this->db->where($this->_table.'.is_active', 1);
		if ($tgl_awal!=NULL || $tgl_akhir!=NULL) {
			$this->db->where("DATE(invoice.created_date) >= ", $tgl_awal);
			$this->db->where("DATE(invoice.created_date) <= ", $tgl_akhir);
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
		foreach ($this->datatable_columns_laporan as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_laporan;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		return $result; 
	}
	

	public function get_data_invoice_tgl($tgl_awal,$tgl_akhir,$shift='',$penjamin='')
	{
		$this->db->where($this->_table.'.tindakan_id', NULL);
		$this->db->where($this->_table.'.is_active', 1);
		if ($tgl_awal!=NULL || $tgl_akhir!=NULL) {
			$this->db->where("DATE(invoice.created_date) >= ", $tgl_awal);
			$this->db->where("DATE(invoice.created_date) <= ", $tgl_akhir);
		}
		if ($shift!='0') {
			$this->db->where($this->_table.'.shift', $shift);	
		}
		if ($penjamin!='0') {
			$this->db->where($this->_table.'.penjamin_id', $penjamin);	
		}

		return $this->db->get($this->_table);
	}
}
