<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pasien_dok_history_detail_tipe_m extends MY_Model {

	protected $_table        = 'pasien_dok_history_detail_tipe';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(	
	);

	private $_fillable_edit = array(
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
	);

	protected $datatable_columns_pilih_data_claim = array(
		//column di table  => alias
		'pasien_penjamin_dok_history_detail_tipe.id'                            => 'id',
		'pasien_penjamin_dok_history_detail_tipe.pasien_id'                     => 'pasien_id',
		'pasien_penjamin_dok_history_detail_tipe.syarat_id'                     => 'syarat_id',
		'syarat.judul'                                         => 'judul',
		'pasien_penjamin_dok_history_detail_tipe.value'                         => 'value',
		'syarat.tipe'                                          => 'tipe',
		'pasien_penjamin.is_active'                            => 'is_active',
		'pasien_isi_syarat_penjamin.pasien_penjamin_dok_history_detail_tipe_id' => 'pasien_penjamin_dok_history_detail_tipe_id'

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

		// $join1 = array('poliklinik_tindakan', $this->_table . '.id = poliklinik_tindakan.tindakan_id');
		// $join2 = array('poliklinik', 'poliklinik.id = poliklinik_tindakan.poliklinik_id');
		  
		 $join_tables = array();
		//$join_tables = array();

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

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
		$this->db->where($this->_table.'is_active', 1);
		 
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'is_active', 1);
		 

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

	public function get_datatable_by_pasien($id_pasien)
	{	

		$join_tables = array();

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.pasien_id', $id_pasien);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.pasien_id', $id_pasien);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.pasien_id', $id_pasien);

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

	public function get_datatable_pilih_data_klaim($pasien_id)
	{

		$join1 = array('syarat', $this->_table.'.syarat_id = syarat.id','left');
		$join2 = array('pasien_isi_syarat_penjamin', 'pasien_isi_syarat_penjamin.pasien_penjamin_dok_history_detail_tipe_id = pasien_penjamin_dok_history_detail_tipe.id','left');
		$join3 = array('pasien_penjamin', 'pasien_penjamin.id = pasien_isi_syarat_penjamin.pasien_penjamin_id','left');
		$join_tables = array($join1,$join2,$join3);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_pilih_data_claim);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.pasien_id', $pasien_id);
		$this->db->where($this->_table.'.value !=', "");
		$this->db->where('pasien_penjamin.is_active', 1);
		$this->db->group_by('pasien_isi_syarat_penjamin.pasien_penjamin_dok_history_detail_tipe_id');
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.pasien_id', $pasien_id);
		$this->db->where($this->_table.'.value !=', "");
		$this->db->where('pasien_penjamin.is_active', 1);
		$this->db->group_by('pasien_isi_syarat_penjamin.pasien_penjamin_dok_history_detail_tipe_id');

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.pasien_id', $pasien_id);
		$this->db->where($this->_table.'.value !=', "");
		$this->db->where('pasien_penjamin.is_active', 1);
		$this->db->group_by('pasien_isi_syarat_penjamin.pasien_penjamin_dok_history_detail_tipe_id');


		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_pilih_data_claim as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_pilih_data_claim;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 

		return $result; 
	}

	/**
	 * [fillable_add description]
	 * @return [type] [description]
	 */
	public function fillable_add()
	{
		return $this->_fillable_add;
	}
	/**
	 * [fillable_edit description]
	 * @return [type] [description]
	 */
	public function fillable_edit()
	{
		return $this->_fillable_edit;
	}

}

/* End of file pasien_dok_history_detail_tipe_m.php */
/* Location: ./application/models/master/pasien_dok_history_detail_tipe_m.php */