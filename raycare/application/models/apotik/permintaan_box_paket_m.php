<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permintaan_box_paket_m extends MY_Model {

	protected $_table        = 'permintaan_box_paket';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'permintaan_box_paket.id'               => 'id',
		'permintaan_box_paket.no_permintaan'    => 'no_permintaan',
		'pasien.nama'                           => 'nama_pasien',
		'permintaan_box_paket.`tanggal`'   		=> 'tanggal',
		'permintaan_box_paket.pasien_id'        => 'pasien_id',
		'permintaan_box_paket.apoteker_pengirim' => 'apoteker_pengirim',
		'permintaan_box_paket.`status`'         => 'status',
		'permintaan_box_paket.`kode_box_paket`'         => 'kode_box_paket',
		'permintaan_box_paket.is_active'        => 'is_active',
		'c.nama'                                    => 'nama_apoteker',
		'dr.nama'                                    => 'nama_dokter',
		'bed.kode'                                    => 'kode_bed',
		'pasien.no_member'                           => 'no_member',
	);


	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($status)
	{	
		$join1 = array('user dr', 'permintaan_box_paket.created_by = dr.id', 'left');
		$join2 = array('pasien', 'permintaan_box_paket.pasien_id = pasien.id', 'left');
		$join3 = array('user c', 'permintaan_box_paket.apoteker_pengirim = c.id', 'left');
		$join5 = array('tindakan_hd', 'permintaan_box_paket.tindakan_id = tindakan_hd.id', 'left');
		$join5 = array('tindakan_hd', 'permintaan_box_paket.tindakan_id = tindakan_hd.id', 'left');
		$join6 = array('bed', 'tindakan_hd.bed_id = bed.id', 'left');
		$join_tables = array($join1, $join2, $join3, $join5, $join6);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		$params['sort_by'] = 'permintaan_box_paket.created_date';
		$params['sort_dir'] = 'desc';
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('permintaan_box_paket.`status`',$status);
		$this->db->where('permintaan_box_paket.`is_active`',1);
		// $this->db->where('supplier.tipe', $tipe);
		// $this->db->where($wheres);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('permintaan_box_paket.`status`',$status);
		$this->db->where('permintaan_box_paket.`is_active`',1);
		// $this->db->where('supplier.tipe', $tipe);
		// $this->db->where($wheres);
		
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('permintaan_box_paket.`status`',$status);
		$this->db->where('permintaan_box_paket.`is_active`',1);
		// $this->db->where('supplier.tipe', $tipe);
		// $this->db->where($wheres);

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

		/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable_history()
	{	
		$join1 = array('user dr', 'permintaan_box_paket.created_by = dr.id', 'left');
		$join2 = array('pasien', 'permintaan_box_paket.pasien_id = pasien.id', 'left');
		$join3 = array('user c', 'permintaan_box_paket.apoteker_pengirim = c.id', 'left');
		$join5 = array('tindakan_hd', 'permintaan_box_paket.tindakan_id = tindakan_hd.id', 'left');
		$join5 = array('tindakan_hd', 'permintaan_box_paket.tindakan_id = tindakan_hd.id', 'left');
		$join6 = array('bed', 'tindakan_hd.bed_id = bed.id', 'left');
		$join_tables = array($join1, $join2, $join3, $join5, $join6);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		$params['sort_by'] = 'permintaan_box_paket.created_date';
		$params['sort_dir'] = 'desc';
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('permintaan_box_paket.`status` !=',1);
		$this->db->where('permintaan_box_paket.`is_active`',1);
		// $this->db->where('supplier.tipe', $tipe);
		// $this->db->where($wheres);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('permintaan_box_paket.`status` !=',1);
		$this->db->where('permintaan_box_paket.`is_active`',1);
		// $this->db->where('supplier.tipe', $tipe);
		// $this->db->where($wheres);
		
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('permintaan_box_paket.`status` !=',1);
		$this->db->where('permintaan_box_paket.`is_active`',1);
		// $this->db->where('supplier.tipe', $tipe);
		// $this->db->where($wheres);

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

		/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable_report()
	{	
		$join1 = array('user dr', 'permintaan_box_paket.created_by = dr.id', 'left');
		$join2 = array('pasien', 'permintaan_box_paket.pasien_id = pasien.id', 'left');
		$join3 = array('user c', 'permintaan_box_paket.apoteker_pengirim = c.id', 'left');
		$join5 = array('tindakan_hd', 'permintaan_box_paket.tindakan_id = tindakan_hd.id', 'left');
		$join5 = array('tindakan_hd', 'permintaan_box_paket.tindakan_id = tindakan_hd.id', 'left');
		$join6 = array('bed', 'tindakan_hd.bed_id = bed.id', 'left');
		$join_tables = array($join1, $join2, $join3, $join5, $join6);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		$params['sort_by'] = 'permintaan_box_paket.created_date';
		$params['sort_dir'] = 'desc';
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('permintaan_box_paket.`status` !=',1);
		$this->db->where('permintaan_box_paket.`is_active`',1);
		// $this->db->where('supplier.tipe', $tipe);
		// $this->db->where($wheres);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('permintaan_box_paket.`status` !=',1);
		$this->db->where('permintaan_box_paket.`is_active`',1);
		// $this->db->where('supplier.tipe', $tipe);
		// $this->db->where($wheres);
		
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('permintaan_box_paket.`status` !=',1);
		$this->db->where('permintaan_box_paket.`is_active`',1);
		// $this->db->where('supplier.tipe', $tipe);
		// $this->db->where($wheres);

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

	public function get_max_id_permintaan()
	{
		$format = "SELECT MAX(SUBSTRING(`id`,12,4)) AS max_id FROM `permintaan_box_paket` WHERE SUBSTR(`id`,4,7) = DATE_FORMAT(NOW(), '%m-%Y');";	
		return $this->db->query($format);
	}
}

/* End of file cabang_m.php */
/* Location: ./application/models/gudang/gudang_m.php */