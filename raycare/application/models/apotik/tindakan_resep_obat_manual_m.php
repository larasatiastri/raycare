<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tindakan_resep_obat_manual_m extends MY_Model {

	protected $_table        = 'tindakan_resep_obat_manual';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		
	);

	

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'tindakan_resep_obat_manual.id'                  => 'id',
		'tindakan_resep_obat_manual.keterangan'          => 'keterangan',
		'tindakan_resep_obat_manual.`status`'            => 'status',
		'tindakan_resep_obat_manual.created_by'          => 'created_by',
		'tindakan_resep_obat_manual.created_date'        => 'created_date',
		'tindakan_resep_obat_manual.resep_racik_obat_id' => 'resep_racik_obat_id'
	);

	protected $datatable_columns_resep_manual = array(
		//column di table  => alias
		'tindakan_resep_obat_manual.id'         => 'id',
		'tindakan_resep_obat_manual.keterangan' => 'keterangan',
		'tindakan_resep_obat_manual.`status`'   => 'status_tindakan',
		'tindakan_resep_obat.pasien_id'         => 'pasien_id',
		'pasien.nama'                           => 'nama_pasien',
		'pasien.url_photo'                      => 'photo_pasien',
		'tindakan_resep_obat.dokter_id'         => 'dokter_id',
		'`user`.nama'                           => 'nama_dokter',
		'`user`.url'                            => 'photo_dokter',
		'`user`.username'                       => 'username'
	);

	protected $datatable_columns2 = array(
		//column di table  => alias
		'tindakan_resep_obat_manual.keterangan'                  => 'keterangan',
	 
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($status)
	{		
		$join_tables = array();

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.status', $status);

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.status', $status);

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.status', $status);
		

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

	public function get_datatable_resep_manual($status)
	{		
		$join = array('tindakan_resep_obat', $this->_table.'.tindakan_resep_obat_id = tindakan_resep_obat.id');
		$join2 = array('pasien', 'tindakan_resep_obat.pasien_id = pasien.id');
		$join3 = array('`user`', 'tindakan_resep_obat.dokter_id = `user`.id');
		$join_tables = array($join, $join2, $join3);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_resep_manual);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.`status`', $status);

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.`status`',$status);

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.`status`', $status);

		

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_resep_manual as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_resep_manual;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function update_status($resep_racik_obat_id){
		 
		$sql= "update tindakan_resep_obat_manual set status=1 where resep_racik_obat_id=$resep_racik_obat_id";

		$this->db->query($sql);
		 
	}

	public function get_data_resep_manual($id){
		$format = "SELECT
					tindakan_resep_obat_manual.id AS id,
					tindakan_resep_obat_manual.keterangan AS keterangan,
					tindakan_resep_obat_manual.`status` AS status_tindakan,
					tindakan_resep_obat.pasien_id AS pasien_id,
					pasien.nama AS nama_pasien,
					pasien.url_photo AS photo_pasien,
					tindakan_resep_obat.dokter_id AS dokter_id,
					`user`.nama AS nama_dokter,
					`user`.url AS photo_dokter,
					`user`.username AS username
				FROM
					tindakan_resep_obat_manual
				JOIN tindakan_resep_obat ON tindakan_resep_obat_manual.tindakan_resep_obat_id = tindakan_resep_obat.id
				JOIN pasien ON tindakan_resep_obat.pasien_id = pasien.id
				JOIN `user` ON tindakan_resep_obat.dokter_id = `user`.id
				WHERE
				tindakan_resep_obat_manual.id = $id";
		return $this->db->query($format);
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

