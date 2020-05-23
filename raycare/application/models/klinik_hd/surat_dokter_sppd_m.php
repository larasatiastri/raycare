<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Surat_dokter_sppd_m extends MY_Model {

	protected $_table      = 'surat_dokter_sppd';
	protected $_timestamps = true;
	protected $_order_by   = 'id';

	private $_fillable = array(
		'nama',
		'Keterangan', 
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'surat_dokter_sppd.id'        => 'id', 
		'pasien.nama'                 => 'nama',
		'user.nama'					  => 'dokter', 
		'surat_dokter_sppd.tanggal'   => 'tanggal',
		'surat_dokter_sppd.diagnosa1'   => 'diagnosa1',
		'surat_dokter_sppd.diagnosa2'   => 'diagnosa2',
		'surat_dokter_sppd.alasan' => 'alasan',
		'surat_dokter_sppd.status' => 'status',
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
		$join1 = array('pasien', $this->_table.'.pasien_id = pasien.id'); 
		$join2 = array('user', $this->_table.'.dokter_id = user.id'); 
		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = "tanggal";
		$params['sort_dir'] = "desc";
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->order_by("tanggal", 'desc');
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->order_by("tanggal", 'desc');
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->order_by("tanggal", 'desc');

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
	public function get_datatable_setuju($status)
	{
		$join1 = array('pasien', $this->_table.'.pasien_id = pasien.id'); 
		$join2 = array('user', $this->_table.'.dokter_id = user.id'); 
		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = "tanggal";
		$params['sort_dir'] = "desc";
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.".status", $status);
		$this->db->order_by("tanggal", 'desc');
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.".status", $status);
		$this->db->order_by("tanggal", 'desc');
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.".status", $status);
		$this->db->order_by("tanggal", 'desc');

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

    public function get_data_dokter($dokter_id)
	{
		$format_dokter = "SELECT id as id, nama as dokter
						FROM user
						WHERE id = $dokter_id";

		return $this->db->query($format_dokter, $dokter_id);
	}

	public function get_data($id)
	{
		$this->db->select(" user.nama as dokter,
							surat_dokter_sppd.tanggal as tanggal,
							surat_dokter_sppd.tanggal_expired as tanggal_expired,
							pasien.nama as pasien,
							surat_dokter_sppd.isi_surat as keterangan,
							surat_dokter_sppd.id");
		$this->db->join("pasien", $this->_table.'.pasien_id = pasien.id');
		$this->db->join("user", $this->_table.'.dokter_id = user.id');
		$this->db->where("surat_dokter_sppd.id", $id);
		return $this->db->get($this->_table);
	}
}

/* End of file spesialis_m.php */
/* Location: ./application/models/spesialis_m.php */