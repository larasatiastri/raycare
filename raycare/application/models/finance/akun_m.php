<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Akun_m extends MY_Model
{
	protected $_table        = 'akun';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		'akun_kategori_id',
		'parent',
		'nama',
		'no_akun',
		'is_suspended',
		'catatan',
		'is_last_child',
		'is_giro'
	);

	private $_fillable = array(
		'akun_kategori_id',
		'parent',
		'nama',
		'no_akun',
		'is_suspended',
		'catatan',
		'is_giro'
	);

	private $_fillable_add_asset = array(
		'akun_kategori_id',
		'parent',
		'nama',
		'no_akun',
		'is_selectable',
		'is_suspended',
		'is_last_child',
		'is_giro',
		'rasidual_value',
		'tanggal_akuisisi',
		'usage_date',
		'description'
	);

		private $_fillable_edit_child = array(
		'is_last_child',
			);




	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'akun.id'        => 'id', 
		'akun.no_akun'   => 'no_akun',
		'akun.parent'      => 'parent',  
		'akun.nama'      => 'nama', 
		'akun.akun_tipe'      => 'akun_tipe', 
		'akun_kategori.nama' => 'kategori_nama',
		'akun.is_suspended' => 'is_suspended',  
		'akun.saldo'   => 'saldo',
		'akun.catatan'   => 'catatan',
		'akun.is_last_child'   => 'is_last_child',
		'akun.is_giro'   => 'is_giro',
		'akun.tanggal_akuisisi'   => 'tanggal_akuisisi',
	);
	
	function __construct ()
	{
		parent::__construct();
	}

	
	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($tipe=NULL)
	{
		$join = array("akun_kategori", $this->_table . '.akun_kategori_id = akun_kategori.id', 'left');
		
		$join_tables = array($join);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		if ($tipe!=NULL){
				$this->db->where('akun_kategori_id',$tipe);
		}
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		if ($tipe!=NULL){
				$this->db->where('akun_kategori_id',$tipe);
		}
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		if ($tipe!=NULL){
				$this->db->where('akun_kategori_id',$tipe);
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
		// die(dump($result->records));
		return $result; 
	}

	public function get_datatable_giro()
	{
		$join = array("akun_kategori", $this->_table . '.akun_kategori_id = akun_kategori.id', 'left');
		
		$join_tables = array($join);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
				$this->db->where('is_giro',1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
				$this->db->where('is_giro',1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
				$this->db->where('is_giro',1);

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
		// die(dump($result->records));
		return $result; 
	}

public function get_datatable_parent()
	{
$join = array("akun_kategori", $this->_table . '.akun_kategori_id = akun_kategori.id', 'left');
		
		$join_tables = array($join);
		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
	
		//		$this->db->where('parent',$id);
	
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
	
			//	$this->db->where('parent',$id);
		
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
	
		//		$this->db->where('parent',$id);
		

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
		// die(dump($result->records));
		return $result; 
	}

public function get_datatable_suspended($suspended)
	{
		$join = array("akun_kategori", $this->_table . '.akun_kategori_id = akun_kategori.id', 'left');
		
		$join_tables = array($join);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);

				$this->db->where('is_suspended',$suspended);
		
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		
				$this->db->where('is_suspended',$suspended);
		
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		
				$this->db->where('is_suspended',$suspended);
		

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
		// die(dump($result->records));
		return $result; 
	}

	/**
	 * [fillable_add description]
	 * @return [type] [description]
	 */

	public function get_no_akun()
	{
		$format = "SELECT MAX(SUBSTRING(`no_akun`,5,2)) AS max_no_akun FROM `akun` WHERE LEFT(`no_akun`,4) = '123.'";	
		

		return $this->db->query($format);
	}

	public function get_no_akun_biaya_akm()
	{
		$format = "SELECT MAX(SUBSTRING(`no_akun`,5,2)) AS max_no_akun FROM `akun` WHERE LEFT(`no_akun`,4) = '509.'";	
		

		return $this->db->query($format);
	}
	public function fillable()
	{
		return $this->_fillable;
	}

	public function fillable_edit_child()
	{
		return $this->_fillable_edit_child;
	}


	public function fillable_add()
	{
		return $this->_fillable_add;
	}

	public function fillable_add_asset()
	{
		return $this->_fillable_add_asset;
	}


	
}

/* End of file user.php */
/* Location: ./application/models/finance/account_m.php */