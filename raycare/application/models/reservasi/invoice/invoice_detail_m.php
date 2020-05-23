<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Invoice_detail_m extends MY_Model {

	protected $_table      = 'invoice_detail';
	protected $_timestamps = true;
	protected $_order_by   = 'tipe_item';

	private $_fillable = array(
		'pasien_id',
		'dashboard_url', 
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'invoice.created_date' => 'tanggal',
		'invoice_detail.nama_tindakan' => 'nama_tindakan',
		'SUM(invoice_detail.qty)' => 'qty',
		'SUM(invoice_detail.qty * invoice_detail.harga)' => 'total_harga',
		'SUM(invoice_detail.diskon_nominal)' => 'total_diskon',
		'invoice_detail.item_id' => 'item_id',
		'invoice_detail.tipe_item' => 'tipe_item',
		
	);

	function __construct()
	{
		parent::__construct();
	}

		/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($invoice_id)
	{
		$join1 = array('invoice', $this->_table.'.invoice_id = invoice.id');
		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = $this->_table.'.tipe_item';
		$params['sort_dir'] = 'ASC';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where_in($this->_table.'.invoice_id', $invoice_id);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->group_by($this->_table.'.nama_tindakan');
		// $this->db->where($this->_table.'.pasien_id', $pasien_id);
		// $this->db->where('pembayaran_alamat.is_primary', 1);
		// dapatkan total row count;
		$query = $this->db->select('(1)')->get();
        $total_records = $query->num_rows();
		//$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where_in($this->_table.'.invoice_id', $invoice_id);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->group_by($this->_table.'.nama_tindakan');
		// $this->db->where($this->_table.'.pasien_id', $pasien_id);
		// $this->db->where('pembayaran_alamat.is_primary', 1);
		// dapatkan total record filtered/search;
		$query = $this->db->select('(1)')->get();
        $total_display_records = $query->num_rows();
		//$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where_in($this->_table.'.invoice_id', $invoice_id);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->group_by($this->_table.'.nama_tindakan');
		// $this->db->where($this->_table.'.pasien_id', $pasien_id);
		// $this->db->where('pembayaran_alamat.is_primary', 1);

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
	

	public function get_data_paket($invoice_id)
	{
		$this->db->select('paket.id, paket.nama, invoice_detail.harga,invoice_detail.qty');
		$this->db->join('paket', $this->_table.'.item_id = paket.id');
		$this->db->where($this->_table.'.invoice_id', $invoice_id);
		$this->db->where($this->_table.'.tipe_item', 1);
		$this->db->where($this->_table.'.is_active', 1);

		return $this->db->get($this->_table);
	}

	public function get_data_items($invoice_id)
	{
		$this->db->select('item.id, item.nama, invoice_detail.harga,invoice_detail.qty');
		$this->db->join('item', $this->_table.'.item_id = item.id');
		$this->db->where($this->_table.'.invoice_id', $invoice_id);
		$this->db->where($this->_table.'.tipe_item', 2);
		$this->db->where($this->_table.'.is_active', 1);

		return $this->db->get($this->_table);
	}

	public function get_data_tindakan($invoice_id)
	{
		$this->db->select('tindakan.id, tindakan.nama, invoice_detail.harga,invoice_detail.qty');
		$this->db->join('tindakan', $this->_table.'.item_id = tindakan.id');
		$this->db->where($this->_table.'.invoice_id', $invoice_id);
		$this->db->where($this->_table.'.tipe_item', 3);
		$this->db->where($this->_table.'.is_active', 1);

		return $this->db->get($this->_table);
	}

	public function get_data_paket_sudah_bayar($invoice_id)
	{
		$this->db->select('paket.id, paket.nama, invoice_detail.harga,invoice_detail.qty');
		$this->db->join('paket', $this->_table.'.item_id = paket.id');
		$this->db->where($this->_table.'.invoice_id', $invoice_id);
		$this->db->where($this->_table.'.tipe_item', 1);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.status', 2);

		return $this->db->get($this->_table);
	}

	public function get_data_items_sudah_bayar($invoice_id)
	{
		$this->db->select('item.id, item.nama, invoice_detail.harga,invoice_detail.qty');
		$this->db->join('item', $this->_table.'.item_id = item.id');
		$this->db->where($this->_table.'.invoice_id', $invoice_id);
		$this->db->where($this->_table.'.tipe_item', 2);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.status', 2);

		return $this->db->get($this->_table);
	}

	public function get_data_tindakan_sudah_bayar($invoice_id)
	{
		$this->db->select('tindakan.id, tindakan.nama, invoice_detail.harga,invoice_detail.qty');
		$this->db->join('tindakan', $this->_table.'.item_id = tindakan.id');
		$this->db->where($this->_table.'.invoice_id', $invoice_id);
		$this->db->where($this->_table.'.tipe_item', 3);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.status', 2);

		return $this->db->get($this->_table);
	}

	public function get_data_paket_bayar($invoice_id)
	{
		$this->db->select('paket.id,paket.nama, invoice_detail.harga, invoice_detail.qty, invoice_detail.id as id_detail');
		$this->db->join('paket', $this->_table.'.item_id = paket.id');
		$this->db->where($this->_table.'.invoice_id', $invoice_id);
		$this->db->where($this->_table.'.tipe_item', 1);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.status', 1);

		return $this->db->get($this->_table);
	}

	public function get_data_items_bayar($invoice_id)
	{
		$this->db->select('item.id,item.nama, invoice_detail.harga, invoice_detail.qty, invoice_detail.id as id_detail');
		$this->db->join('item', $this->_table.'.item_id = item.id');
		$this->db->where($this->_table.'.invoice_id', $invoice_id);
		$this->db->where($this->_table.'.tipe_item', 2);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.status', 1);

		return $this->db->get($this->_table);
	}

	public function get_data_tindakan_bayar($invoice_id)
	{
		$this->db->select('tindakan.id, tindakan.nama, invoice_detail.harga, invoice_detail.qty, invoice_detail.id as id_detail');
		$this->db->join('tindakan', $this->_table.'.item_id = tindakan.id');
		$this->db->where($this->_table.'.invoice_id', $invoice_id);
		$this->db->where($this->_table.'.tipe_item', 3);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.status', 1);

		return $this->db->get($this->_table);
	}

	public function get_id_invoice_detail()
	{
		$format = "SELECT MAX(RIGHT(`id`,4)) AS max_id FROM `invoice_detail` WHERE SUBSTRING(`id`,5,7) = DATE_FORMAT(NOW(), '%m-%Y')";	
		return $this->db->query($format);
	}
}
