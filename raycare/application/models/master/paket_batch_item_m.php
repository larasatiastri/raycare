<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Paket_batch_item_m extends MY_Model {

	protected $_table        = 'paket_batch_item';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		'id',
		'paket_batch_id',
		'item_id',
		'jumlah',
		'item_satuan_id', 
		'is_active', 		
	);

	private $_fillable_edit = array(
		'id',
		'paket_batch_id',
		'item_id',
		'jumlah',
		'item_satuan_id', 
		'is_active', 	
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'paket_batch_item.id'         		=> 'id', 
		'paket_batch_item.paket_batch_id'   => 'paket_batch_id', 
		'paket_batch_item.item_id'   		=> 'item_id', 
		'paket_batch_item.jumlah'   		=> 'jumlah', 
		'paket_batch_item.item_satuan_id'  	=> 'item_satuan_id',
		'paket_batch_item.is_active'     	=> 'is_active',
		
	);

	protected $datatable_columns_view = array(
		//column di table  => alias
		'paket_batch_item.id'         		=> 'id', 
		'paket_batch_item.paket_batch_id'   => 'paket_batch_id', 
		'paket_batch_item.item_id'   		=> 'item_id', 
		'paket_batch_item.jumlah'   		=> 'jumlah', 
		'paket_batch_item.item_satuan_id'  	=> 'item_satuan_id',
		'paket_batch_item.is_active'     	=> 'is_active',
		'item.nama'     				    => 'nama_item',
		
	);

	protected $datatable_columns_edit_batch = array(
		//column di table  => alias
		'paket_batch_item.id'         		=> 'id', 
		'paket_batch_item.paket_batch_id'   => 'paket_batch_id', 
		'paket_batch_item.item_id'   		=> 'item_id', 
		'paket_batch_item.jumlah'   		=> 'jumlah', 
		'paket_batch_item.item_satuan_id'  	=> 'item_satuan_id',
		'paket_batch_item.is_active'     	=> 'is_active',
		'paket_item.jumlah'     			=> 'jumlah_item',
		'item.nama'     				    => 'nama_item',
		
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
		$join_tables = array();

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		// $this->db->where('paket_batch.paket_id',$id);
		$this->db->where('is_active',1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		// $this->db->where('paket_batch.paket_id',$id);
		$this->db->where('is_active',1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		// $this->db->where('paket_batch.paket_id',$id);
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


	public function get_datatable_edit_batch($paket_batch_id)
	{	

		$join1 = array("item", $this->_table . '.item_id = item.id', 'left');
		$join2 = array("paket_item", 'paket_batch_item.paket_batch_id = paket_item.id', 'left');

		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_edit_batch);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('paket_batch_item.paket_batch_id',$paket_batch_id);
		$this->db->where('paket_batch_item.is_active',1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('paket_batch_item.paket_batch_id',$paket_batch_id);
		$this->db->where('paket_batch_item.is_active',1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('paket_batch_item.paket_batch_id',$paket_batch_id);
		$this->db->where('paket_batch_item.is_active',1);

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_edit_batch as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_edit_batch;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}


	public function get_datatable_view_batch($paket_batch_id)
	{	

		$join1 = array("item", $this->_table . '.item_id = item.id', 'left');

		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_view);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('paket_batch_item.paket_batch_id',$paket_batch_id);
		$this->db->where('is_active',1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('paket_batch_item.paket_batch_id',$paket_batch_id);
		$this->db->where('is_active',1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('paket_batch_item.paket_batch_id',$paket_batch_id);
		$this->db->where('is_active',1);

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_view as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_view;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	function get_id($id)
	{	
		$this->db->where('id', $id);
		$this->db->order_by('id', 'desc');
		$query = $this->db->query('SELECT id, kode FROM paket_batch');
		
		return $query->row();
		return $this->db->get($this->_table);
	}

	function get_data_checked($paket_batch_id)
	{
		$format = "SELECT
						paket_batch_item.id AS id,
						paket_batch_item.paket_batch_id AS paket_batch_id,
						paket_batch_item.item_id AS item_id,
						paket_batch_item.jumlah AS jumlah,
						paket_batch_item.item_satuan_id AS item_satuan_id,
						paket_batch_item.is_active AS is_active,
						paket_item.jumlah AS jumlah_item,
						item.nama AS nama_item
					FROM
						paket_batch_item
					LEFT JOIN item ON paket_batch_item.item_id = item.id
					LEFT JOIN paket_item ON paket_batch_item.paket_batch_id = paket_item.id
					WHERE
						paket_batch_item.paket_batch_id = ".$paket_batch_id."
					AND paket_batch_item.is_active = 1";	

		return $this->db->query($format);
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

/* End of file paket_batch_m.php */
/* Location: ./application/models/master/paket_batch_m.php */