<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tindakan_transfusi_item_m extends MY_Model {

	protected $_table        = 'tindakan_transfusi_item';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
			
	);

	private $_fillable_edit = array(
		
	);

	protected $datatable_columns = array(
		//column di table  => alias
		'tindakan_transfusi_item.waktu'                  => 'waktu',
		'item.nama'                               => 'item_nama',
		'tindakan_transfusi_item.jumlah'	              => 'jumlah',
		'tindakan_transfusi_item.item_satuan_id'         => 'item_satuan_id',
		'item_satuan.nama'                        => 'item_satuan_nama',
		'user.nama'                               => 'user_nama',
		'tindakan_transfusi_item.id'                     => 'id',
		'tindakan_transfusi_item.item_id'                => 'item_id',
		'tindakan_transfusi_item.bn_sn_lot'                => 'bn_sn_lot',
		'tindakan_transfusi_item.expire_date'                => 'expire_date',
		'tindakan_transfusi_item.created_by'             => 'created_by',
		'item.is_identitas'						=> 'is_identitas',
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($tindakan_id)
	{	
 		$join1 = array('user', $this->_table.'.user_id = user.id', 'left');
		$join2 = array('item', $this->_table.'.item_id = item.id', 'left');
		$join3 = array('item_satuan', $this->_table.'.item_satuan_id = item_satuan.id', 'left');
		$join_tables = array($join1, $join2, $join3);

		// get params dari input postnya datatable

		
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = 'tindakan_transfusi_item.waktu';
		$params['sort_dir'] = 'desc';
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('tindakan_transfusi_item.tindakan_transfusi_id', $tindakan_id );
		$this->db->where('tindakan_transfusi_item.is_active', 1 );
		$this->db->where('tindakan_transfusi_item.jumlah !=', 0 );
		
		// dapatkan total row count;
		//$query = $this->db->select('(1)')->get();
        //$total_records = $query->num_rows();
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('tindakan_transfusi_item.tindakan_transfusi_id', $tindakan_id );
		$this->db->where('tindakan_transfusi_item.is_active', 1 );
		$this->db->where('tindakan_transfusi_item.jumlah !=', 0 );
		

		// dapatkan total record filtered/search;
		//$query = $this->db->select('(1)')->get();
        //$total_display_records = $query->num_rows();
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('tindakan_transfusi_item.tindakan_transfusi_id', $tindakan_id );
		$this->db->where('tindakan_transfusi_item.is_active', 1 );
		$this->db->where('tindakan_transfusi_item.jumlah !=', 0 );
		
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

	public function get_max_id()
	{
		$format = "SELECT MAX(RIGHT(`id`,4)) AS max_id FROM `tindakan_transfusi_item` WHERE SUBSTRING(`id`,5,4) = DATE_FORMAT(NOW(), '%m%y')";	
		return $this->db->query($format);
	}
}
