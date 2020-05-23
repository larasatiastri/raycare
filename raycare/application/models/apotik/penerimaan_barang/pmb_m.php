<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pmb_m extends MY_Model {

	protected $_table        = 'pmb';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'pmb.id'                  => 'id',
		'pmb.gudang_id'           => 'gudang_id',
		'pmb.tanggal'             => 'tanggal',
		'pmb.no_pmb'              => 'no_pmb',
		'pmb.no_surat_jalan'      => 'no_surat_jalan',
		'pmb.no_faktur'           => 'no_faktur',
		'pmb.`status`'            => 'status',
		'pmb.is_active'           => 'is_active',
		'pmb.keterangan_gudang'   => 'keterangan_gudang',
		'pmb.keterangan_keuangan' => 'keterangan_keuangan',
		'pmb.supplier_id'         => 'supplier_id',
		'supplier.kode'           => 'supplier_kode',
		'supplier.nama'           => 'supplier_nama',
		'supplier.tipe'           => 'tipe'
	);

	protected $datatable_columns_detail = array(
		//column di table  => alias
		'pmb.id'                     => 'pmb_id',
		'pmb.supplier_id'            => 'supplier_id',
		'pmb.tanggal'                => 'tanggal',
		'pmb.no_pmb'                 => 'no_pmb',
		'pmb_detail.item_id'         => 'item_id',
		'pmb_detail.item_satuan_id'  => 'item_satuan_id',
		'pmb_detail.jumlah_diterima' => 'jumlah_diterima',
		'item_satuan.nama'			 => 'nama_satuan'
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
		$join1 = array('supplier', 'pmb.supplier_id = supplier.id', 'left');
		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		$params['sort_by'] = 'pmb.tanggal';
		$params['sort_dir'] = 'desc';
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('pmb.`status`',1);
		// $this->db->where('supplier.tipe', $tipe);
		// $this->db->where($wheres);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('pmb.`status`',1);
		// $this->db->where('supplier.tipe', $tipe);
		// $this->db->where($wheres);
		
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('pmb.`status`',1);
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

	public function get_datatable_detail($supplier_id, $item_id, $item_satuan_id)
	{	
		$join1 = array('pmb_detail', 'pmb.id = pmb_detail.pmb_id', 'left');
		$join2 = array('item_satuan', 'pmb_detail.item_satuan_id = item_satuan.id', 'left');
		$join_tables = array($join1, $join2);

		
		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_detail);

		$params['sort_by'] = 'pmb_detail.id';
		$params['sort_dir'] = 'asc';
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('pmb.supplier_id',$supplier_id);
		$this->db->where('pmb_detail.item_id', $item_id);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('pmb.supplier_id',$supplier_id);
		$this->db->where('pmb_detail.item_id', $item_id);
		
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('pmb.supplier_id',$supplier_id);
		$this->db->where('pmb_detail.item_id', $item_id);

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_detail as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_detail;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_datatable_detail_supplier_lain($supplier_id, $item_id, $item_satuan_id)
	{	
		$join1 = array('pmb_detail', 'pmb.id = pmb_detail.pmb_id', 'left');
		$join_tables = array($join1);

		
		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_detail);

		$params['sort_by'] = 'pmb.no_pmb';
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('pmb.supplier_id !=',$supplier_id);
		$this->db->where('pmb_detail.item_id', $item_id);
		$this->db->where('pmb_detail.item_satuan_id', $item_satuan_id);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('pmb.supplier_id !=',$supplier_id);
		$this->db->where('pmb_detail.item_id', $item_id);
		$this->db->where('pmb_detail.item_satuan_id', $item_satuan_id);
		
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('pmb.supplier_id !=',$supplier_id);
		$this->db->where('pmb_detail.item_id', $item_id);
		$this->db->where('pmb_detail.item_satuan_id', $item_satuan_id);

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_detail as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_detail;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_jumlah_terima($supplier_id, $item_id, $item_satuan_id, $id)
	{
		$pembelian_id = rtrim($id, ',');
		// $format = "SELECT
		// 			pmb.supplier_id,
		// 			SUM(pmb_detail.jumlah_diterima) as jumlah_diterima
		// 			FROM
		// 			pmb
		// 			LEFT JOIN pmb_detail ON pmb.id = pmb_detail.pmb_id
		// 			WHERE
		// 			pmb.supplier_id = $supplier_id AND
		// 			pmb_detail.item_id = $item_id AND
		// 			pmb_detail.item_satuan_id = $item_satuan_id";

		$format = "SELECT
					pmb_po_detail.jumlah as jumlah_diterima
					FROM
					pmb_po_detail
					LEFT JOIN pembelian_detail ON pmb_po_detail.po_detail_id = pembelian_detail.id
					LEFT JOIN pmb_detail ON pmb_po_detail.pmb_detail_id = pmb_detail.id
					LEFT JOIN pmb ON pmb_detail.pmb_id = pmb.id
					WHERE
					pembelian_detail.pembelian_id IN ($pembelian_id) AND
					pmb.supplier_id = $supplier_id AND
					pembelian_detail.item_id = $item_id AND
					pmb_po_detail.item_satuan_id = $item_satuan_id
					";

		return $this->db->query($format);
	}

	public function get_jumlah_terima_supplier_lain($supplier_id, $item_id, $item_satuan_id)
	{
		$format = "SELECT
					pmb.supplier_id,
					SUM(pmb_detail.jumlah_diterima) as jumlah_diterima
					FROM
					pmb
					LEFT JOIN pmb_detail ON pmb.id = pmb_detail.pmb_id
					WHERE
					pmb.supplier_id != $supplier_id AND
					pmb_detail.item_id = $item_id AND
					pmb_detail.item_satuan_id = $item_satuan_id";

		return $this->db->query($format);
	}

	public function get_no_pmb()
	{
		$format = "SELECT MAX(SUBSTRING(`no_pmb`,6,3)) AS max_no_pmb FROM `pmb` WHERE RIGHT(`no_pmb`,4) = DATE_FORMAT(NOW(), '%Y');";	
		return $this->db->query($format);
	}

	public function get_max_id_pmb()
	{
		$format = "SELECT MAX(SUBSTRING(`id`,13,4)) AS max_id FROM `pmb` WHERE SUBSTR(`id`,5,7) = DATE_FORMAT(NOW(), '%m-%Y');";	
		return $this->db->query($format);
	}
}

/* End of file cabang_m.php */
/* Location: ./application/models/gudang/gudang_m.php */