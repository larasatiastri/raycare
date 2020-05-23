<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jurnal_sistem_template_m extends MY_Model
{
	protected $_table        = 'jurnal_sistem_template';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable = array(
			'id',
			'nama_template',
			'tipe_transaksi',
			'keterangan',
			'is_active'
	);



	protected $datatable_columns = array(
		//column di table  => alias
		'jurnal_sistem_template.id'        => 'id', 
		'jurnal_sistem_template.nama_template'   => 'nama_template',
		'jurnal_sistem_template.tipe_transaksi'      => 'tipe_transaksi',  
		'jurnal_sistem_template.keterangan'      => 'keterangan', 
		'jurnal_sistem_template.is_active'      => 'is_active', 
		'jurnal_sistem_template.created_by'      => 'created_by',
	);

	function __construct ()
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
		$params = $this->datatable_param($this->datatable_columns);
		$this->datatable_prepare($join_tables, $params);
		$total_records = $this->db->count_all_results();
		$this->datatable_prepare($join_tables, $params, true);
		$total_display_records = $this->db->count_all_results();
		$this->datatable_prepare($join_tables, $params, true, true);
		foreach ($this->datatable_columns as $col => $alias)
		{
			$this->db->select($col . ' AS ' . $alias);
		}
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




}

