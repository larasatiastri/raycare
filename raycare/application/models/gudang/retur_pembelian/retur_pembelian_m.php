<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class retur_pembelian_m extends MY_Model {

	protected $_table        = 'retur_pembelian';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		
	);

	

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'retur_pembelian.id'               => 'id',
		'retur_pembelian.gudang_id'        => 'gudang_id',
		'retur_pembelian.no_retur'         => 'no_retur',
		'retur_pembelian.no_surat_jalan'   => 'no_surat_jalan',
		'retur_pembelian.tanggal'          => 'tanggal',
		'retur_pembelian.keterangan'       => 'keterangan',
		'supplier.tipe'                    => 'supplier_tipe',
		'supplier.kode'                    => 'supplier_kode',
		'supplier.nama'                    => 'supplier_nama',
		'supplier.rating'                  => 'supplier_rating',
		'supplier.orang_yang_bersangkutan' => 'orang_yang_bersangkutan'
		
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($type_supplier=null)
	{		
		$join1 = array('supplier', 'retur_pembelian.supplier_id = supplier.id', 'left');
		$join_tables = array($join1);

		$wheres = '';

		if($type_supplier == null)
		{
			$wheres = array();
		}
		else
		{
			$wheres = array(
				'supplier.tipe' => $type_supplier,
			);
		}
		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('retur_pembelian.status', 1);
		$this->db->where('retur_pembelian.is_active', 1);
		$this->db->where($wheres);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('retur_pembelian.status', 1);
		$this->db->where('retur_pembelian.is_active', 1);
		$this->db->where($wheres);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('retur_pembelian.status', 1);
		$this->db->where('retur_pembelian.is_active', 1);
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
		// die(dump($result->records));
		return $result; 
	}

	public function get_datatable_history($type_supplier=null)
	{		
		$join1 = array('supplier', 'retur_pembelian.supplier_id = supplier.id', 'left');
		$join_tables = array($join1);

		$wheres = '';

		if($type_supplier == null)
		{
			$wheres = array();
		}
		else
		{
			$wheres = array(
				'supplier.tipe' => $type_supplier,
			);
		}
		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('retur_pembelian.status', 2);
		$this->db->where('retur_pembelian.is_active', 1);
		$this->db->where($wheres);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('retur_pembelian.status', 2);
		$this->db->where('retur_pembelian.is_active', 1);
		$this->db->where($wheres);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('retur_pembelian.status', 2);
		$this->db->where('retur_pembelian.is_active', 1);
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
		// die(dump($result->records));
		return $result; 
	}

	public function get_no_retur()
	{
		$format = "SELECT MAX(SUBSTRING(`no_retur`,10,4)) AS max_no_retur FROM `retur_pembelian` WHERE SUBSTRING(`no_retur`,5,2) = DATE_FORMAT(NOW(), '%y') AND SUBSTRING(`no_retur`, 7, 2) = DATE_FORMAT(NOW(), '%m')";	
		return $this->db->query($format);
	}

	public function get_no_surat_jalan()
	{
		$format = "SELECT MAX(SUBSTRING(`no_surat_jalan`,10,4)) AS max_no_surat_jalan FROM `retur_pembelian` WHERE SUBSTRING(`no_surat_jalan`,5,2) = DATE_FORMAT(NOW(), '%y') AND SUBSTRING(`no_surat_jalan`, 7, 2) = DATE_FORMAT(NOW(), '%m')";	
		return $this->db->query($format);
	}

	/**
	 * [fillable_add descriptiopn]
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

