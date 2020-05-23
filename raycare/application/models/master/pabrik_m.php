<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pabrik_m extends MY_Model {

	protected $_table      = 'pabrik';
	protected $_timestamps = true;
	protected $_order_by   = 'id';

	private $_fillable = array(
		'id',
		'kode', 
		'nama', 
		'is_active', 
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(

		//column di table  => alias
		
		'pabrik.id' 									=> 'id',
		'pabrik.kode'									=> 'kode',
		'pabrik.nama'									=> 'nama_pabrik',
		'pabrik.is_active'								=> 'is_active',
		'pabrik_alamat.alamat'							=> 'alamat_pabrik',
		'pabrik_alamat.rt_rw'							=> 'rt_rw',
		'pabrik_alamat.kode_pos'						=> 'kode_pos',
		// 'pabrik_alamat.negera_id'						=> 'negera_id',
		'pabrik_contact_person.nama'		=> 'nama_contact_person',
		'pabrik_contact_person.nomor'		=> 'nomor_contact_person',
		'pabrik_telepon.nomor'				=> 'nomor_tlp_pabrik',
		'region.nama'						=> 'nama_negara',

	);

	protected $datatable_columns_contact_person = array(

		//column di table  => alias
		
		'pabrik.id' 									=> 'id',
		'pabrik.kode'									=> 'kode',
		'pabrik.nama'									=> 'nama_pabrik',
		'pabrik.is_active'								=> 'is_active',
		'pabrik_contact_person.nama'		=> 'nama_contact_person',
		'pabrik_contact_person.nomor'		=> 'nomor_contact_person',

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

		$join1 = array('pabrik_alamat', $this->_table.'.id = pabrik_alamat.pabrik_id','left');
		$join2 = array('pabrik_contact_person', $this->_table.'.id = pabrik_contact_person.pabrik_id','left');
		$join3 = array('pabrik_telepon', $this->_table.'.id = pabrik_telepon.pabrik_id','left');
		$join4 = array('region', 'pabrik_alamat.negara_id = region.id','left');

		$join_tables = array($join1, $join2, $join3, $join4);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'is_active', 1);
		$this->db->group_by('pabrik.nama');
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->group_by('pabrik.nama');
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->group_by('pabrik.nama');

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

	public function get_datatable_cp($id)
	{

		$join = array('pabrik_contact_person', $this->_table.'.id = pabrik_contact_person.pabrik_id','left');

		$join_tables = array($join);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_contact_person);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('pabrik_contact_person.pabrik_id', $id);
		// $this->db->where($this->_table.'is_active', 1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('pabrik_contact_person.pabrik_id', $id);
		// $this->db->where($this->_table.'.is_active', 1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('pabrik_contact_person.pabrik_id', $id);
		// $this->db->where($this->_table.'.is_active', 1);

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_contact_person as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_contact_person;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 

		return $result; 
	}

		/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable_active()
	{

		$join_tables = array();

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('is_active',1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('is_active',1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('is_active',1);

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

	public function get_data_subjek($type)
	{
		$format = "SELECT id as id, nama as nama
				   FROM subjek
				   WHERE tipe = $type";

		return $this->db->query($format);
	}

	public function get_data_is_active()
    {
        $this->db->where("is_active", 1);
        return $this->db->get($this->_table);
    }
}

/* End of file user_level_m.php */
/* Location: ./application/models/user_level_m.php */