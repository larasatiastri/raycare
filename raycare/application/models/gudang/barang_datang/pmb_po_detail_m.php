<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pmb_po_detail_m extends MY_Model {

	protected $_table        = 'pmb_po_detail';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		
	);

	

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'pmb_po_detail.po_detail_id' => 'id',
		'pmb_po_detail.jumlah' => 'jumlah',
		'pmb_detail.id' => 'pmb_detail_id',
		'pmb_detail.bn_sn_lot' => 'bn_sn_lot',
		'pmb_detail.expire_date' => 'expire_date',
		'pmb.tanggal' => 'tanggal',
		'pmb.created_by' => 'created_by',
		'`user`.nama' => 'nama',
		'user_level.nama' => 'user_level',
		'pmb.no_pmb' => 'no_pmb',
		'item_satuan.nama' => 'satuan_nama'
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
		$join1 = array('pmb_detail', 'pmb_po_detail.pmb_detail_id = pmb_detail.id', 'left');
		$join2 = array('pmb', 'pmb_detail.pmb_id = pmb.id', 'left');
		$join3 = array('`user`', 'pmb.created_by = `user`.id', 'left');
		$join4 = array('user_level', 'user_level.id = `user`.user_level_id', 'left');
		$join5 = array('item_satuan', 'pmb_po_detail.item_satuan_id = item_satuan.id', 'left');
		$join_tables = array($join1, $join2, $join3, $join4, $join5);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('pmb_po_detail.po_detail_id',$id);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('pmb_po_detail.po_detail_id',$id);
		
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('pmb_po_detail.po_detail_id',$id);

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

	public function get_data_diterima($po_detail_id)
	{
		$this->db->select('SUM(pmb_po_detail.jumlah) AS jumlah, pmb_po_detail.item_satuan_id, item_satuan.nama as nama_satuan');
		$this->db->join('item_satuan', $this->_table.'.item_satuan_id = item_satuan.id','left');
		$this->db->where_in($this->_table.'.po_detail_id',$po_detail_id);
		$this->db->group_by('pmb_po_detail.item_satuan_id');

		return $this->db->get($this->_table)->result_array();
	}

	public function get_data_terima_po($po_id)
	{
		$this->db->select('item.id as item_id, item.kode as kode_item, item.nama as nama_item, item_satuan.id as item_satuan_id, item_satuan.nama as nama_satuan, pmb_po_detail.po_id, pmb_po_detail.po_detail_id, pmb.tanggal, pmb_detail.jumlah_diterima, pmb_detail.bn_sn_lot, pmb_detail.expire_date, pembelian_detail.harga_beli_primary');
		$this->db->join('pmb_detail', $this->_table.'.pmb_detail_id = pmb_detail.id','left');
		$this->db->join('pmb', $this->_table.'.pmb_id = pmb.id','left');
		$this->db->join('pembelian_detail', $this->_table.'.po_detail_id = pembelian_detail.id','left');
		$this->db->join('item','pmb_detail.item_id = item.id','left');
		$this->db->join('item_satuan','pmb_detail.item_satuan_id = item_satuan.id','left');
		$this->db->where($this->_table.'.po_id',$po_id);

		return $this->db->get($this->_table)->result_array();
	}
}

