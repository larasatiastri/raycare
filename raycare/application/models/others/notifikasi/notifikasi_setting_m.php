<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notifikasi_setting_m extends MY_Model
{
	protected $_table        = 'notifikasi_setting';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable = array(
		'id',
		'notifikasi_daftar_aksi_id',	
		'notifikasi_user_id',	
		'notifikasi_user_level_id',	
		'notifikasi_text',	
		'notifikasi_url',	
		'is_add_parameter',	
		'is_email',	
		'email_address',	
	);




	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'notifikasi_setting.id'                 => 'id', 
		'user.nama'                             => 'nama', 
		'user_level.nama'                       => 'nama_level', 
		'notifikasi_setting.notifikasi_text'    => 'text', 
		'notifikasi_setting.notifikasi_url'     => 'url', 
		'notifikasi_setting.email_address'      => 'email_address',
		'notifikasi_setting.is_active'          => 'is_active',
		'notifikasi_setting.notifikasi_user_id' => 'user_id', 
	);
	
	function __construct ()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($action_list_id)
	{
		$join1 = array("user", $this->_table . '.notifikasi_user_id = user.id', 'left');
		$join2 = array("user_level", $this->_table . '.notifikasi_user_level_id = user_level.id', 'left');

		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('notifikasi_daftar_aksi_id', $action_list_id);

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('notifikasi_daftar_aksi_id', $action_list_id);

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('notifikasi_daftar_aksi_id', $action_list_id);

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
	
}
