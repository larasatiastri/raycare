<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setoran_kasir_detail_m extends MY_Model {

	protected $_table      = 'setoran_kasir_detail';
	protected $_timestamps = true;
	protected $_order_by   = 'id';

	private $_fillable = array(
		'pasien_id',
		'dashboard_url', 
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'setoran_kasir_detail.id'            => 'id',
		'setoran_kasir_detail.pembayaran_id' => 'pembayaran_id',
		'setoran_kasir_detail.invoice_id'    => 'invoice_id',
		'invoice.no_invoice'              => 'no_invoice',
		'invoice.created_date'            => 'tanggal_invoice',
		'invoice.nama_pasien'             => 'nama_pasien',
		'invoice.harga'                   => 'harga',
		'invoice.akomodasi'                   => 'akomodasi',
		'invoice.diskon_nominal'                   => 'diskon_nominal',
		'setoran_kasir_detail.jumlah'   => 'total_bayar'
	);

	function __construct()
	{
		parent::__construct();
	}

		/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($id_bayar)
	{
		
		$join1 = array("invoice", $this->_table . '.invoice_id = invoice.id');

		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$this->db->where_in($this->_table.'.setoran_id', $id_bayar);
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where_in($this->_table.'.setoran_id', $id_bayar);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where_in($this->_table.'.setoran_id', $id_bayar);

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


	public function get_max_id_setoran_detail()
	{
		$format = "SELECT MAX(SUBSTRING(`id`,13,4)) AS max_id FROM `setoran_kasir_detail` WHERE SUBSTR(`id`,5,7) = DATE_FORMAT(NOW(), '%m-%Y');";	
		return $this->db->query($format);
	}

	
	
}
