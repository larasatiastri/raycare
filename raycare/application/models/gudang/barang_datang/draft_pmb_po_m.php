<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Draft_pmb_po_m extends MY_Model {

	protected $_table        = 'draft_pmb_po';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'draft_pmb.draft_pmb_id'               => 'id',
		'draft_pmb.gudang_id'        => 'gudang_id',
		'draft_pmb.tanggal'          => 'tanggal',
		'draft_pmb.no_surat_jalan'   => 'no_surat_jalan',
		'draft_pmb.no_faktur'        => 'no_faktur',
		'draft_pmb.`status`'         => 'status',
		'draft_pmb.is_active'        => 'is_active',
		'draft_pmb.keterangan_gudang'   => 'keterangan_gudang',
		'draft_pmb.supplier_id'      => 'supplier_id',
		'supplier.kode'        => 'supplier_kode',
		'supplier.nama'        => 'supplier_nama',
		'supplier.tipe'        => 'tipe'
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($tipe, $gudang)
	{	
		$join1 = array('supplier', 'draft_pmb.supplier_id = supplier.id', 'left');
		$join_tables = array($join1);

		if($gudang == null)
		{
			$wheres = array();
		}
		else
		{
			$wheres = array(
				$this->_table.'.gudang_id' => $gudang,
			);
		}

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		$params['sort_by'] = 'draft_pmb.draft_pmb_id';
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('supplier.tipe', $tipe);
		$this->db->where($wheres);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('supplier.tipe', $tipe);
		$this->db->where($wheres);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('supplier.tipe', $tipe);
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

	public function get_last_id()
	{
		$format = "SELECT MAX(draft_pmb_po_id) as last_id FROM draft_pmb_po";

		return $this->db->query($format);
	}

	public function get_data($draft_pmb_id, $po_id)
	{
		$sql = "SELECT * FROM draft_pmb_po WHERE draft_pmb_id = $draft_pmb_id and po_id = $po_id";

		return $this->db->query($sql)->result_array();
	}

	public function get_id($draft_pmb_id)
	{
		$sql = "SELECT * FROM draft_pmb_po WHERE draft_pmb_id = $draft_pmb_id";

		return $this->db->query($sql)->result_array();
	}

}

/* End of file draft_pmb_m.php */
/* Location: ./application/models/gudang/barang_datang/draft_pmb_m.php */