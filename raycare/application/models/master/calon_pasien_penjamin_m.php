<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Calon_pasien_penjamin_m extends MY_Model {

	protected $_table        = 'calon_pasien_penjamin';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		'kode',
		'nama',
		'alamat', 
		'nomor_telepon1', 
		'nomor_telepon2', 
		'nomor_fax', 		
	);

	private $_fillable_edit = array(
		'kode',
		'nama',
		'alamat', 
		'nomor_telepon1', 
		'nomor_telepon2', 
		'nomor_fax', 
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'calon_pasien_penjamin.id'         		=> 'id',
		'calon_pasien_penjamin.no_kartu'          => 'no_kartu',
		'penjamin.nama' 					=> 'nama',
		'penjamin.id'	 					=> 'penjamin_id',
		'penjamin_kelompok.nama'			=> 'nama_kelompok',
		'calon_pasien_penjamin.status'         	=> 'status',
		'calon_pasien_penjamin.is_active'         => 'is_active',
		
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

	public function get_datatable_by_calon_pasien($id_calon_pasien)
	{	
		$join_penjamin = array('penjamin', $this->_table.'.penjamin_id=penjamin.id','left');
		$join_penjamin_kelompok = array('penjamin_kelompok',$this->_table.'.penjamin_kelompok_id=penjamin_kelompok.id','left');
		$join_tables = array($join_penjamin, $join_penjamin_kelompok);
		

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.calon_pasien_id', $id_calon_pasien);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.calon_pasien_id', $id_calon_pasien);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.calon_pasien_id', $id_calon_pasien);

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

	public function get_data_calon_pasien_penjamin($penjamin_id, $syarat_id){
		// $format = " SELECT
		// 			calon_pasien_penjamin.id,
		// 			calon_pasien_penjamin.calon_pasien_id,
		// 			calon_pasien_isi_syarat_penjamin.calon_pasien_penjamin_id,
		// 			calon_pasien_penjamin.no_kartu,
		// 			calon_pasien_syarat_penjamin.syarat_id,
		// 			calon_pasien_syarat_penjamin.`value`,
		// 			syarat.tipe,
		// 			calon_pasien_isi_syarat_penjamin.calon_pasien_syarat_penjamin_id,
		// 			calon_pasien_syarat_penjamin_detail.value as value_detail,
		// 			calon_pasien_syarat_penjamin_detail.id AS id_detail
		// 			FROM
		// 			calon_pasien_penjamin
		// 			LEFT JOIN calon_pasien_isi_syarat_penjamin ON calon_pasien_penjamin.id = calon_pasien_isi_syarat_penjamin.calon_pasien_penjamin_id
		// 			LEFT JOIN calon_pasien_syarat_penjamin ON calon_pasien_syarat_penjamin.id = calon_pasien_isi_syarat_penjamin.calon_pasien_syarat_penjamin_id
		// 			LEFT JOIN syarat ON calon_pasien_syarat_penjamin.syarat_id = syarat.id
		// 			LEFT JOIN calon_pasien_syarat_penjamin_detail ON calon_pasien_syarat_penjamin.id = calon_pasien_syarat_penjamin_detail.calon_pasien_syarat_penjamin_id
		// 			WHERE calon_pasien_penjamin.id = $penjamin_id AND syarat.id = $syarat_id
		// 		  ";
		$format = " SELECT
					calon_pasien_penjamin.id,
					calon_pasien_penjamin.calon_pasien_id,
					calon_pasien_isi_syarat_penjamin.calon_pasien_penjamin_id,
					calon_pasien_penjamin.no_kartu,
					calon_pasien_syarat_penjamin.syarat_id,
					calon_pasien_syarat_penjamin.`value`,
					syarat.tipe,
					calon_pasien_isi_syarat_penjamin.calon_pasien_syarat_penjamin_id
					FROM
						calon_pasien_penjamin
					LEFT JOIN calon_pasien_isi_syarat_penjamin ON calon_pasien_penjamin.id = calon_pasien_isi_syarat_penjamin.calon_pasien_penjamin_id
					LEFT JOIN calon_pasien_syarat_penjamin ON calon_pasien_syarat_penjamin.id = calon_pasien_isi_syarat_penjamin.calon_pasien_syarat_penjamin_id
					LEFT JOIN syarat ON calon_pasien_syarat_penjamin.syarat_id = syarat.id
					WHERE calon_pasien_penjamin.id = $penjamin_id AND syarat.id = $syarat_id
									  ";
		return $this->db->query($format);
	}

	public function get_data_syarat_detail($syarat_id, $tipe){
		//di pakai untuk controler calon_pasien/show_claim
		$format = " SELECT
					syarat.id,
					syarat.tipe,
					syarat.maksimal_karakter,
					syarat.judul,
					syarat_detail.value,
					syarat_detail.text
					FROM
					syarat
					LEFT JOIN syarat_detail ON syarat.id = syarat_detail.syarat_id
					WHERE
					syarat.id = $syarat_id AND
					syarat.tipe = $tipe";

		return $this->db->query($format);
	}

	public function get_data_penjamin($calon_pasien_id)
	{
		$this->db->select('penjamin.id, penjamin.nama');
		$this->db->from('calon_pasien_penjamin');
		$this->db->join("penjamin", $this->_table . '.penjamin_id = penjamin.id', 'left');
		$this->db->where('calon_pasien_penjamin.calon_pasien_id', $calon_pasien_id);

		return $this->db->get()->result_array();
	}

}

/* End of file calon_pasien_penjamin_m.php */
/* Location: ./application/models/master/calon_pasien_penjamin_m.php */