<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventaris_m extends MY_Model {

	protected $_table      = 'inventaris';
	protected $_timestamps = true;
	protected $_order_by   = 'id';

	private $_fillable = array(
		'id',
		'no_inventaris',
		'merk',
		'tipe',
		'serial_number',
		'tanggal_pembelian',
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'inventaris.id'           		=> 'id', 
		'inventaris.no_inventaris'  	=> 'no_inventaris',
		'inventaris.merk'        		=> 'merk',
		'inventaris.tipe'          		=> 'tipe',
		'inventaris.serial_number'  	=> 'serial_number',
		'inventaris.tanggal_pembelian'  => 'tanggal_pembelian',
		'inventaris.garansi'        => 'garansi',
		'inventaris.jadwal_maintenance'        => 'jadwal_maintenance',
		'user_penerima.nama'							=> 'nama_pengguna',
		'user_penanggung_jawab.nama'							=> 'nama_penanggung_jawab',
		'inventaris.is_active'    => 'is_active', 
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
		$level_id = $this->user_level_m->get($this->session->userdata('level_id'));
		$divisi_id = $level_id->divisi_id;
		
		$join1 = array('user user_penerima', $this->_table.'.pengguna = user_penerima.id', 'left');
		$join2 = array('user user_penanggung_jawab', $this->_table.'.yang_menyerahkan = user_penanggung_jawab.id', 'left');

		$join_tables = array($join1,$join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = $this->_table.'.created_date';
		$params['sort_dir'] = 'asc';
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active', 1);
		if($this->session->userdata('level_id') != config_item('user_developer') && $this->session->userdata('level_id') != 14)
		{
			$this->db->where($this->_table.'.divisi_id', $divisi_id);
		}
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active', 1);
		if($this->session->userdata('level_id') != config_item('user_developer') && $this->session->userdata('level_id') != 14)
		{
			$this->db->where($this->_table.'.divisi_id', $divisi_id);
		}
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active', 1);
		if($this->session->userdata('level_id') != config_item('user_developer') && $this->session->userdata('level_id') != 14)
		{
			$this->db->where($this->_table.'.divisi_id', $divisi_id);
		}

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

	public function get_no_inventaris()
	{
		$format = "SELECT MAX(SUBSTRING(`no_inventaris`,6,3)) AS max_no_inventaris FROM `inventaris` WHERE RIGHT(`no_inventaris`,4) = DATE_FORMAT(NOW(), '%Y');";	
		return $this->db->query($format);
	}

	public function get_max_id()
	{
		$format = "SELECT MAX(SUBSTRING(`id`,11,4)) AS max_id FROM `inventaris` WHERE SUBSTR(`id`,5,5) = DATE_FORMAT(NOW(), '%m-%y');";	
		return $this->db->query($format);
	}

	public function get_data_full()
	{
		$this->db->select('mesin_edc.id, mesin_edc.nama, bank.nob');
		$this->db->join('bank', $this->_table.'.bank_id = bank.id','left');
		$this->db->where($this->_table.'.is_active', 1);

		return $this->db->get($this->_table);
	}

}

/* End of file inventaris_m.php */
/* Location: ./application/models/inventaris_m.php */