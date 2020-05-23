<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tindakan_m extends MY_Model {

	protected $_table        = 'tindakan';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		'id',
		'kode',
		'nama',
		'harga', 
		'keterangan', 
		'is_active', 		
	);

	private $_fillable_edit = array(
		'id',
		'kode',
		'nama',
		'harga', 
		'keterangan', 
		'is_active', 
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'tindakan.kode'         => 'kode', 
		'tindakan.nama'   		=> 'nama', 
		'tindakan.harga'   		=> 'harga',
		'tindakan.keterangan'   => 'keterangan',
		//'tindakan.is_active'    => 'is_active',
		'tindakan.id'     		=> 'id'
		
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
		$join_tables = array();

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = 'tindakan.id';
		$params['sort_dir'] = 'asc';
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
 // die(dump($result->records));
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

	public function checkpoli($poli_id,$tindakan_id){
		$sql= "select count(*) as counts from poliklinik_tindakan where poliklinik_id=".$poli_id." and tindakan_id=".$tindakan_id." and is_active=1";

		return $this->db->query($sql);
	}

}

/* End of file cabang_m.php */
/* Location: ./application/models/master/cabang_m.php */