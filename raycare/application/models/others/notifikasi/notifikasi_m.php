<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notifikasi_m extends MY_Model
{
	protected $_table        = 'notifikasi';
	protected $_order_by     = 'notifikasi_id';
	protected $_timestamps   = true;

	private $_fillable = array(
		'notifikasi_id',
		'user_level_id',		
		'user_id',		
		'notifikasi_text',		
		'notifikasi_text_param',		
		'notifikasi_url',		
		'notifikasi_parameter',		
		'created_date',		
	);




	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'notifikasi.notifikasi_id'         => 'notifikasi_id',
		'notifikasi.user_level_id'         => 'user_level_id',		
		'notifikasi.user_id'               => 'user_id',		
		'notifikasi.notifikasi_text'       => 'notifikasi_text',		
		'notifikasi.notifikasi_text_param' => 'notifikasi_text_param',	
		'notifikasi.notifikasi_url'        => 'notifikasi_url',			
		'notifikasi.notifikasi_parameter'  => 'notifikasi_parameter',		
		'notifikasi.created_date'           => 'created_date',	
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
		$join1 = array("users", $this->_table . '.notifikasi_user_id = users.id', 'left');
		$join2 = array("user_levels", $this->_table . '.notifikasi_user_level_id = user_levels.id', 'left');

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

	public function max(){
		$query = $this->db->query('SELECT MAX(notifikasi_id) as max_id FROM notifikasi');

		return $query->row();
	}

	public function simpan($data){
		$query = $this->db->insert($this->_table, $data);
		return true; 
	}

	public function delete_this($id)
	{
		$this->db->where('notifikasi_id', $id);
		$this->db->delete($this->_table);
		return true;
	}

	public function get_notif($user_id,$level_id)
	{
		
        $columns = array(
            't1.notifikasi_id',
            't1.user_level_id',
            't1.user_id',
            't1.notifikasi_text',
            't1.notifikasi_url',
            'SUBSTRING(t1.created_date,12,5) as created_date'
            );

        $emails = $this->db
            ->select($columns)
            ->from($this->_table . ' AS t1')            
            ->where('t1.user_level_id', $level_id)
            ->where('t1.user_id', $user_id)
            ->or_where('t1.user_level_id', $level_id)
            ->where('t1.user_id', NULL)
            ->order_by('t1.created_date', 'desc')
            ->get()->result();

        return $emails;

	}
	
}
