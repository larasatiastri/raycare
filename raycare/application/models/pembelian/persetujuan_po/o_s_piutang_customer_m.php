<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class O_s_piutang_customer_m extends MY_Model {

	protected $_table        = 'o_s_piutang_customer';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		
	);

	protected $datatable_columns_proses = array(
		//column di table  => alias
	
		);



	public function __construct()
	{
		parent::__construct();
	}

	public function get_total_piutang($customer_id)
	{

		$format = "Select SUM(total_piutang) as total_piutang
					FROM
					o_s_piutang_customer
					WHERE
					o_s_piutang_customer.customer_id = $customer_id";

		return $this->db->query($format);

	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	
}

/* End of file cabang_m.php */
/* Location: ./application/models/pembelian/daftar_permintaan_po_m.php */