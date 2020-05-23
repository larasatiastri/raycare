<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Poliklinik_tindakan_m extends MY_Model {

	protected $_table        = 'poliklinik_tindakan';
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
		'tindakan.kode'         => 'kode', 
		'tindakan.nama'   => 'nama', 
		'tindakan.harga'   => 'harga',
		//'tindakan.is_active'     => 'is_active',
		'poliklinik_tindakan.id'     => 'id'
		
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($id=null)
	{	

		$join1 = array('tindakan', $this->_table . '.tindakan_id = tindakan.id');
		  
		 $join_tables = array($join1);
		//$join_tables = array();

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('poliklinik_tindakan.is_active',1);
		$this->db->where('tindakan.is_active',1);
		$this->db->where('poliklinik_id',$id);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('poliklinik_tindakan.is_active',1);
		$this->db->where('tindakan.is_active',1);
		$this->db->where('poliklinik_id',$id);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('poliklinik_tindakan.is_active',1);
		$this->db->where('tindakan.is_active',1);
		$this->db->where('poliklinik_id',$id);

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
 //die(dump($result->records));
		return $result; 
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

	public function getdata($id){
		$sql= "select * from poliklinik_tindakan a join tindakan b on b.id=a.tindakan_id where a.poliklinik_id=? and b.is_active=1 and a.is_active=1";

		return $this->db->query($sql,$id);
	}
	public function getdata2($id){
		$sql= "select a.id as idt,b.id,b.nama,b.kode,b.harga from poliklinik_tindakan a join tindakan b on b.id=a.tindakan_id where a.poliklinik_id=? and a.is_active=1 and b.is_active=1";

		return $this->db->query($sql,$id);
	}

	public function getdata3($id){
		$sql= "select a.id as idt,b.id,b.nama,b.kode,b.harga from poliklinik_tindakan a join tindakan b on b.id=a.tindakan_id where a.poliklinik_id=? and a.is_active=1 and b.is_active=1";

		return $this->db->query($sql,$id);
	}

	public function getdata4($id){
		$sql= "select a.id as idt,a.poliklinik_id,b.id,b.nama,b.kode,b.harga from poliklinik_tindakan a join tindakan b on b.id=a.tindakan_id join poliklinik c on c.id=a.poliklinik_id where a.tindakan_id=? and a.is_active=1 and c.is_active=1";

		return $this->db->query($sql,$id);
	}

	public function get_data_harga($poliklinik_id)
	{
		$cabang_id = $this->session->userdata('cabang_id');
		$tanggal = date('Y-m-d');

		$SQL = "SELECT tindakan.id, tindakan.nama, poliklinik_harga_tindakan.harga FROM `poliklinik_tindakan` JOIN tindakan 
				ON poliklinik_tindakan.tindakan_id = tindakan.id JOIN poliklinik_harga_tindakan
				ON poliklinik_tindakan.id = poliklinik_harga_tindakan.poliklinik_tindakan_id
				WHERE poliklinik_tindakan.cabang_id = $cabang_id
				AND poliklinik_tindakan.poliklinik_id = $poliklinik_id
				AND date(poliklinik_harga_tindakan.tanggal) <= '$tanggal'
				AND poliklinik_tindakan.is_active = 1
				GROUP BY poliklinik_harga_tindakan.poliklinik_tindakan_id
				ORDER BY poliklinik_harga_tindakan.tanggal DESC";

		return $this->db->query($SQL);
	}

}

/* End of file cabang_m.php */
/* Location: ./application/models/master/cabang_m.php */