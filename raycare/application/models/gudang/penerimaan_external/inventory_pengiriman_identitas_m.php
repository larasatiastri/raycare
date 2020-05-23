<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory_pengiriman_identitas_m extends MY_Model {

	protected $_table        = 'inventory_pengiriman_identitas';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;


	protected $datatable_columns_customer = array(

		//column di table          => alias
		'invoice.no_invoice'        => 'no_invoice', 
		'pengiriman.no_surat_jalan' => 'no_surat_jalan', 
		'customer.nama'             => 'customer_nama', 
		'customer.kode'             => 'customer_kode', 
		'penjualan.tanggal_pesan'   => 'tanggal_pesan',
		'penjualan.tanggal_kirim'   => 'tanggal_kirim',
		'pengiriman.keterangan'     => 'keterangan',
		'pengiriman.id'             => 'id',
		'pengiriman.status'         => 'status',
		'pengiriman.gudang_id'      => 'gudang_id',
		'penjualan.id'              => 'penjualan_id',
	);

	public function __construct()
	{
		parent::__construct();
	}



	public function get_datatable_external()
	{

		$join1 = array('penjualan', $this->_table.'.penjualan_id = penjualan.id', 'left');
		$join2 = array('customer', $this->_table.'.customer_id = customer.id', 'left');
		$join3 = array('invoice', $this->_table.'.invoice_id = invoice.id', 'left');
		$join_tables = array($join1, $join2, $join3);

		$wheres = array(
			'pengiriman.is_active' => 1,
			'pengiriman.tipe_cus'  => 2,
		);

		$status = array(2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_customer);
		// $params['sort_by'] = 'penjualan.no_penjualan';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($wheres);
		$this->db->where_in('pengiriman.status', $status);

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($wheres);
		$this->db->where_in('pengiriman.status', $status);

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($wheres);
		$this->db->where_in('pengiriman.status', $status);

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_customer as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_customer;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 

		return $result; 
	}

	public function delete_where($wheres){
        
        $this->db->where($wheres);
        $this->db->delete($this->_table);
    }

}
