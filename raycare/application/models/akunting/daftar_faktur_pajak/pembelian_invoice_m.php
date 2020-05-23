<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembelian_invoice_m extends MY_Model {

	protected $_table        = 'pembelian_invoice';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'pembelian_invoice.id'               => 'id',
		'supplier.nama'                      => 'nama',
		'pembelian.no_pembelian'             => 'no_pembelian',
		'supplier.kode'                      => 'kode',
		'pembelian_invoice.no_invoice'       => 'no_invoice',
		'pembelian_invoice.tgl_invoice'      => 'tgl_invoice',
		'pembelian_invoice.total_invoice'    => 'total_invoice',
		'pembelian_invoice.keterangan'       => 'keterangan',
		'pembelian_invoice.pembelian_id'     => 'id_pembelian',
		'pembelian_invoice.url'              => 'url',
		'pembelian_invoice.url_faktur_pajak' => 'url_faktur_pajak',
	);

	public function __construct()
	{
		parent::__construct();
	}	

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($status=NULL)
	{	
		$join1 = array('pembelian', $this->_table.'.pembelian_id = pembelian.id');
		$join2 = array('supplier', 'pembelian.supplier_id = supplier.id');
		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = $this->_table.'.tgl_invoice';
		$params['sort_dir'] = 'asc';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		
		if($status != NULL) {
			$this->db->where($this->_table.'.status', $status);
		}
		else{
			$this->db->where($this->_table.'.status is NULL');
		};
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		
		if($status != NULL) {
			$this->db->where($this->_table.'.status', $status);
		}
		else{
			$this->db->where($this->_table.'.status is NULL');
		};
		
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		
		if($status != NULL) {
			$this->db->where($this->_table.'.status', $status);
		}
		else{
			$this->db->where($this->_table.'.status is NULL');
		};
		

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

	public function get_max_id_invoice_pembelian()
	{
		$format = "SELECT MAX(SUBSTRING(`id`,13,4)) AS max_id FROM `pembelian_invoice` WHERE SUBSTR(`id`,5,7) = DATE_FORMAT(NOW(), '%m-%Y');";	
		return $this->db->query($format);
	}

	
}

/* End of file pembelian_invoice_m.php */
/* Location: ./application/models/pembelian/pembelian_invoice_m.php */