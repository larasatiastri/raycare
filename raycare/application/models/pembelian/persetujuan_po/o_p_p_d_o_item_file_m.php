<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class O_p_p_d_o_item_file_m extends MY_Model {

	protected $_table        = 'o_p_p_d_o_item_file';
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

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	
}

/* End of file cabang_m.php */
/* Location: ./application/models/pembelian/daftar_permintaan_po_m.php */