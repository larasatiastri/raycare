<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tindakan_hd_m extends MY_Model {

	protected $_table        = 'tindakan_hd';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
			
	);

	private $_fillable_edit = array(
		
	);

	protected $datatable_columns = array(
		//column di table          => alias
		'tindakan_hd.id'                      => 'id', 
		'tindakan_hd.pendaftaran_tindakan_id' => 'pendaftaran_tindakan_id', 
		'tindakan_hd.no_transaksi'            => 'no_transaksi', 
		'tindakan_hd.tanggal'                 => 'tanggal', 
		'pasien.url_photo'                    => 'url_photo',
		'pasien.id'                           => 'id_pasien',
		'pasien.nama'                         => 'nama_pasien',
		'pasien.no_member'                    => 'no_member',
		'penjamin.nama'                       => 'nama_penjamin',
		'pasien_penjamin.id'                  => 'pasien_penjamin_id',
		'pasien_penjamin.no_kartu'            => 'no_kartu',
		'user.nama'                           => 'nama_dokter',
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
 
	 	 
		$join1 = array('pasien_penjamin',$this->_table . '.pasien_id = pasien_penjamin.pasien_id AND '. $this->_table . '.penjamin_id = pasien_penjamin.penjamin_id');
		$join2 = array('pasien',$this->_table . '.pasien_id = pasien.id');
		$join3 = array('penjamin',$this->_table . '.penjamin_id = penjamin.id');
		$join4 = array('user',$this->_table . '.dokter_id = user.id');
		$join_tables = array($join1, $join2, $join3, $join4);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = 'tindakan_hd.id';
		$params['sort_dir'] = 'desc';
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
	  	$this->db->where("tindakan_hd.is_sep = 0 and tindakan_hd.`status` != 4 and tindakan_hd.penjamin_id != 1 AND pasien_penjamin.is_active = 1 AND date(tindakan_hd.tanggal) <= '".date('Y-m-d', strtotime("-3 days"))."'");
		// dapatkan total row count;
		$query = $this->db->select('(1)')->get();
		$total_records = $query->num_rows();
		//$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
	  	$this->db->where("tindakan_hd.is_sep = 0 and tindakan_hd.`status` != 4 and tindakan_hd.penjamin_id != 1 AND pasien_penjamin.is_active = 1 AND date(tindakan_hd.tanggal) <= '".date('Y-m-d', strtotime("-3 days"))."'");
		// dapatkan total record filtered/search;
		$query = $this->db->select('(1)')->get();
		$total_display_records = $query->num_rows();
		//$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
	  	$this->db->where("tindakan_hd.is_sep = 0 and tindakan_hd.`status` != 4 and tindakan_hd.penjamin_id != 1 AND pasien_penjamin.is_active = 1 AND date(tindakan_hd.tanggal) <= '".date('Y-m-d', strtotime("-3 days"))."'");

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

	public function get_diagnosa($tindakan_hd_id)
	{
		$sql = "SELECT
				icd_code.`name` as nama,
				icd_code.code_ast as kode
				FROM
				tindakan_hd_diagnosa
				INNER JOIN icd_code ON tindakan_hd_diagnosa.kode_diagnosis = icd_code.code_ast
				WHERE tindakan_hd_diagnosa.tindakan_hd_id = $tindakan_hd_id
				";
		return $this->db->query($sql);
	}
}
