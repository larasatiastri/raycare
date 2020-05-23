<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permintaan_dialyzer_baru_m extends MY_Model {

	protected $_table        = 'permintaan_dialyzer_baru';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'permintaan_dialyzer_baru.id'               => 'id',
		'cabang.nama'                               => 'nama_cabang',
		'permintaan_dialyzer_baru.no_permintaan'    => 'no_permintaan',
		'pasien.nama'                               => 'nama_pasien',
		'a.nama'                                    => 'nama_minta',
		'permintaan_dialyzer_baru.`created_date`'   => 'created_date',
		'permintaan_dialyzer_baru.cabang_id'        => 'cabang_id',
		'permintaan_dialyzer_baru.pasien_id'        => 'pasien_id',
		'permintaan_dialyzer_baru.tindakan_id'      => 'tindakan_id',
		'permintaan_dialyzer_baru.perawat_penerima' => 'perawat_penerima',
		'permintaan_dialyzer_baru.`status`'         => 'status',
		'permintaan_dialyzer_baru.is_active'        => 'is_active',
		'b.nama'                                    => 'nama_terima',
		'c.nama'                                    => 'nama_apoteker',
		'item.nama'                                 => 'nama_dialyzer',
		'permintaan_dialyzer_baru.bn_sn_lot'        => 'bn_sn_lot',
		'permintaan_dialyzer_baru.expired_date'        => 'expired_date',
		'permintaan_dialyzer_baru.modified_date'        => 'modified_date',
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
		$join1 = array('cabang', 'permintaan_dialyzer_baru.cabang_id = cabang.id', 'left');
		$join2 = array('pasien', 'permintaan_dialyzer_baru.pasien_id = pasien.id', 'left');
		$join3 = array('user a', 'permintaan_dialyzer_baru.created_by = a.id', 'left');
		$join4 = array('user b', 'permintaan_dialyzer_baru.perawat_penerima = b.id', 'left');
		$join5 = array('user c', 'permintaan_dialyzer_baru.modified_by = c.id', 'left');
		$join6 = array('item', 'permintaan_dialyzer_baru.dialyzer_id = item.id', 'left');
		$join_tables = array($join1, $join2, $join3, $join4, $join5, $join6);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		$params['sort_by'] = 'permintaan_dialyzer_baru.created_date';
		$params['sort_dir'] = 'desc';
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('permintaan_dialyzer_baru.`status`',$status);
		$this->db->where('permintaan_dialyzer_baru.`is_active`',1);
		// $this->db->where('supplier.tipe', $tipe);
		// $this->db->where($wheres);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('permintaan_dialyzer_baru.`status`',$status);
		$this->db->where('permintaan_dialyzer_baru.`is_active`',1);
		// $this->db->where('supplier.tipe', $tipe);
		// $this->db->where($wheres);
		
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('permintaan_dialyzer_baru.`status`',$status);
		$this->db->where('permintaan_dialyzer_baru.`is_active`',1);
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
		$format = "SELECT MAX(SUBSTRING(`id`,12,4)) AS max_id FROM `permintaan_dialyzer_baru` WHERE SUBSTR(`id`,4,7) = DATE_FORMAT(NOW(), '%m-%Y');";	
		return $this->db->query($format);
	}
}

/* End of file cabang_m.php */
/* Location: ./application/models/gudang/gudang_m.php */