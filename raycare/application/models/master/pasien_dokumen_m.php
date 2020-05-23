<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pasien_dokumen_m extends MY_Model {

	protected $_table      = 'realnew_core.pasien_dokumen';
	protected $_timestamps = true;
	protected $_order_by   = 'id';

	private $_fillable = array(
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		
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

		$join_tables = array();

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

	public function get_data_is_active()
    {
        $this->db->where("is_active", 1);
        return $this->db->get($this->_table);
    }

    public function get_by_pasien_id($pasien,$is_active)
    {
    	$format = " SELECT
					pasien_dokumen.id,
					pasien_dokumen.dokumen_id,
					pasien_dokumen.is_kadaluarsa,
					pasien_dokumen.tanggal_kadaluarsa,
					pasien_dokumen.is_required,
					dokumen.nama
				FROM
					pasien_dokumen
				LEFT JOIN dokumen ON pasien_dokumen.dokumen_id = dokumen.id
				WHERE
					pasien_dokumen.pasien = $pasien
				AND
					pasien_dokumen.is_active = $is_active
				ORDER BY pasien_dokumen.id ASC";

		return $this->db->query($format);
    }

    public function get_by_data($wheres)
    {
		$this->db->select('pasien_dokumen_detail_tipe.text as text, pasien_dokumen_detail_tipe.value as value, pasien_dokumen_detail_tipe.id as id');
		$this->db->where('pasien_dokumen.dokumen_id', $wheres['dokumen_id']);
		$this->db->where('pasien_dokumen.pasien_id', $wheres['pasien_id']);
		$this->db->where('pasien_dokumen_detail_tipe.dokumen_detail_id', $wheres['dokumen_detail_id']);
		$this->db->join('pasien_dokumen_detail','pasien_dokumen_detail_tipe.pasien_dokumen_detail_id = pasien_dokumen_detail.id','left');
		$this->db->join('pasien_dokumen','pasien_dokumen.id = pasien_dokumen_detail.pasien_dokumen_id');
		$this->db->group_by('pasien_dokumen.pasien_id');
		$this->db->group_by('pasien_dokumen_detail_tipe.id');

		return $this->db->get('pasien_dokumen_detail_tipe')->row(0);
    }

    public function get_by_data_tipe($wheres)
    {
		$this->db->select('pasien_dokumen_detail_tipe.text as text, pasien_dokumen_detail_tipe.value as value, pasien_dokumen_detail_tipe.id as id,pasien_dokumen.dokumen_id as dokumen_id');
		$this->db->where('pasien_dokumen.dokumen_id', $wheres['dokumen_id']);
		$this->db->where('pasien_dokumen.pasien_id', $wheres['pasien_id']);
		$this->db->where('pasien_dokumen_detail.tipe', $wheres['tipe']);
		$this->db->join('pasien_dokumen_detail','pasien_dokumen_detail_tipe.pasien_dokumen_detail_id = pasien_dokumen_detail.id','left');
		$this->db->join('pasien_dokumen','pasien_dokumen.id = pasien_dokumen_detail.pasien_dokumen_id');
		$this->db->group_by('pasien_dokumen.pasien_id');
		$this->db->group_by('pasien_dokumen_detail_tipe.id');

		return $this->db->get('pasien_dokumen_detail_tipe');
    }
}

/* End of file pasien_dokumen_m.php */
/* Location: ./application/models/master/pasien_dokumen_m.php */