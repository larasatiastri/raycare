<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pasien_dokumen_detail_tipe_m extends MY_Model {

	protected $_table        = 'realnew_core.pasien_dokumen_detail_tipe';
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
		 
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		 
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		 

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


	public function get_url_file($pasien_dokumen_id, $tipe)
	{
		# SELECT pasien_dokumen_detail_tipe.text, pasien_dokumen_detail_tipe.value FROM pasien_dokumen_detail_tipe JOIN pasien_dokumen_detail ON pasien_dokumen_detail_tipe.pasien_dokumen_detail_id = pasien_dokumen_detail.id AND pasien_dokumen_detail.pasien_dokumen_id = 1 AND pasien_dokumen_detail.tipe = 9
		$this->db->select('pasien_dokumen_detail_tipe.text as text, pasien_dokumen_detail_tipe.value as value');
		$this->db->join('pasien_dokumen_detail','pasien_dokumen_detail_tipe.pasien_dokumen_detail_id = pasien_dokumen_detail.id');
		$this->db->where('pasien_dokumen_detail.pasien_dokumen_id',$pasien_dokumen_id);
		$this->db->where('pasien_dokumen_detail.tipe', $tipe);

		return $this->db->get($this->_table);
	}

}

/* End of file pasien_dokumen_detail_tipe_m.php */
/* Location: ./application/models/master/pasien_dokumen_detail_tipe_m.php */