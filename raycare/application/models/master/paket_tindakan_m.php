<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Paket_tindakan_m extends MY_Model {

	protected $_table        = 'paket_tindakan';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		'id',
		'paket_id',
		'tindakan_id',
		'jumlah',
		'harga', 
		'is_active', 		
	);

	private $_fillable_edit = array(
		'id',
		'paket_id',
		'tindakan_id',
		'jumlah',
		'harga', 
		'is_active', 	
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'paket_tindakan.id'         		=> 'id', 
		'paket_tindakan.paket_id'   		=> 'paket_id', 
		'paket_tindakan.tindakan_id'   		=> 'tindakan_id', 
		'paket_tindakan.jumlah'   			=> 'jumlah', 
		'paket_tindakan.harga'  			=> 'harga',
		'paket_tindakan.is_active'     		=> 'is_active',
		'tindakan.kode'     				=> 'kode',
		'tindakan.nama'     				=> 'nama_tindakan',
		
	);

	protected $datatable_columns_batch = array(
		//column di table  => alias
		'paket_tindakan.id'         		=> 'id', 
		'paket_tindakan.paket_id'   		=> 'paket_id', 
		'paket_tindakan.tindakan_id'   		=> 'tindakan_id', 
		'paket_tindakan.jumlah'   			=> 'jumlah', 
		'paket_tindakan.harga'  			=> 'harga',
		'paket_tindakan.is_active'     		=> 'is_active',
		'tindakan.kode'     				=> 'kode',
		'tindakan.nama'     				=> 'nama_tindakan',
		'paket_batch_tindakan.jumlah'     	=> 'jumlah_batch',
		
	);

	

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($id)
	{	
		$join1 = array("tindakan", $this->_table . '.tindakan_id = tindakan.id', 'left');

		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('paket_tindakan.paket_id',$id);
		$this->db->where('paket_tindakan.is_active',1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('paket_tindakan.paket_id',$id);
		$this->db->where('paket_tindakan.is_active',1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('paket_tindakan.paket_id',$id);
		$this->db->where('paket_tindakan.is_active',1);

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


	public function get_datatable_view($so_id)
	{
		
		$join1 = array("tindakan", $this->_table . '.tindakan_id = tindakan.id', 'left');
		$join2 = array("paket", $this->_table . '.paket_id = paket.id', 'left');

		$join_tables = array($join1, $join2);

		$wheres = array(

			'paket_id' => $so_id,

			);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($wheres);

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($wheres);
		
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		// $this->db->group_by('code');
		$this->db->where($wheres);

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



	function get_id($id)
	{	
		$this->db->where('id', $id);
		$this->db->order_by('id', 'desc');
		$query = $this->db->query('SELECT id, kode FROM paket_tindakan');
		
		return $query->row();
		return $this->db->get($this->_table);
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

}

/* End of file paket_m.php */
/* Location: ./application/models/master/cabang_m.php */