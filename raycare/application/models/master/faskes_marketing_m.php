<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Faskes_marketing_m extends MY_Model {

	protected $_table      = 'faskes_marketing';
	protected $_timestamps = true;
	protected $_order_by   = 'id';

	private $_fillable = array(
		'tipe',
		'nama', 
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'user.nama'		=> 'nama_marketing',
		'master_faskes.kode_faskes'		=> 'kode_faskes',
		'master_faskes.nama_faskes'		=> 'nama_faskes',
		'master_faskes.nama_reg'		=> 'nama_reg',
		'master_faskes.id'		=> 'id',
	);

	function __construct()
	{
		parent::__construct();
	}

		/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable()
	{
		$join1 = array('user', $this->_table.'.marketing_id = user.id', 'left');
		$join2 = array('master_faskes', $this->_table.'.kode_faskes = master_faskes.kode_faskes', 'left');
		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = $this->_table.'.id';
		$params['sort_dir'] = $this->_table.'.desc';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'is_active', 1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active', 1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active', 1);

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

	public function get_data_is_active()
    {
        $this->db->where("is_active", 1);
        return $this->db->get($this->_table);
    }

    public function get_faskes_marketing($marketing_id)
    {
    	$this->db->select('master_faskes.id, master_faskes.kode_faskes, master_faskes.nama_faskes, master_faskes.jenis, master_faskes.nama_reg, master_faskes.alamat, master_faskes.telp');
    	$this->db->join('master_faskes', $this->_table.'.kode_faskes = master_faskes.kode_faskes','left');
    	$this->db->where($this->_table.'.marketing_id', $marketing_id);
    	$this->db->where($this->_table.'.is_active', 1);

    	return $this->db->get($this->_table);
    }

    
}

/* End of file user_level_m.php */
/* Location: ./application/models/user_level_m.php */