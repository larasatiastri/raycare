<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tindakan_hd_history_m extends MY_Model {

	protected $_table        = 'tindakan_hd_history';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
			
	);

	private $_fillable_edit = array(
		
	);

	protected $datatable_columns = array(
		//column di table  => alias
		'tindakan_hd.id'                           => 'id', 
		'date(tindakan_hd.tanggal)'                => 'tanggal', 
		'pasien.id'                         => 'id_pasien', 
		'pasien.no_member'                         => 'no_member', 
		'pasien.nama'                              => 'nama', 
		'pasien.dokter_pengirim'                   => 'dokter_pengirim', 
		'tindakan_hd_penaksiran.waktu'             => 'waktu', 
		'tindakan_hd_penaksiran.machine_no'        => 'no_mesin', 
		'tindakan_hd_penaksiran.assessment_cgs'    => 'assessment_cgs', 
		'tindakan_hd_penaksiran.medical_diagnose'  => 'medical_diagnose', 
		'tindakan_hd_penaksiran.time_of_dialysis'  => 'time_of_dialysis', 
		'tindakan_hd_penaksiran.quick_of_blood'    => 'quick_of_blood', 
		'tindakan_hd_penaksiran.quick_of_dialysis' => 'quick_of_dialysis', 
		'tindakan_hd_penaksiran.uf_goal'           => 'uf_goal', 
		'tindakan_hd_penaksiran.heparin_reguler'   => 'heparin_reguler', 
		'tindakan_hd_penaksiran.heparin_minimal'   => 'heparin_minimal', 
		'tindakan_hd_penaksiran.heparin_free'      => 'heparin_free', 
		'tindakan_hd_penaksiran.dose'              => 'dose', 
		'tindakan_hd_penaksiran.first'             => 'first', 
		'tindakan_hd_penaksiran.maintenance'       => 'maintenance', 
		'tindakan_hd_penaksiran.hours'             => 'hours', 
		'tindakan_hd_penaksiran.dialyzer_new'      => 'dialyzer_new', 
		'tindakan_hd_penaksiran.dialyzer_reuse'    => 'dialyzer_reuse', 
		'tindakan_hd_penaksiran.dialyzer'          => 'dialyzer', 
		'tindakan_hd_penaksiran.bn_dialyzer'       => 'bn_dialyzer', 
		'tindakan_hd_penaksiran.ba_avshunt'        => 'ba_avshunt', 
		'tindakan_hd_penaksiran.ba_femoral'        => 'ba_femoral', 
		'tindakan_hd_penaksiran.ba_catheter'       => 'ba_catheter', 
		'tindakan_hd_penaksiran.dialyzer_type'     => 'dialyzer_type', 
		'pasien.url_photo'                         => 'url_photo'
	);

	protected $datatable_columns2 = array(
		//column di table  => alias
		'DATE(tindakan_hd.tanggal)' => 'date', 
		'COUNT(*)'      => 'trans_count', 
		"(SELECT COUNT(*) AS trans_count FROM tindakan_hd b WHERE date(b.tanggal) = date(tindakan_hd.tanggal) AND b.shift = 1 AND b.status != 1 AND b.is_manual IS NULL GROUP BY date(b.`tanggal`))" => 'pagi',
		"(SELECT COUNT(*) AS trans_count FROM tindakan_hd b WHERE date(b.tanggal) = date(tindakan_hd.tanggal) AND b.shift = 2 AND b.status != 1 AND b.is_manual IS NULL GROUP BY date(b.`tanggal`))" => 'siang',
		"(SELECT COUNT(*) AS trans_count FROM tindakan_hd b WHERE date(b.tanggal) = date(tindakan_hd.tanggal) AND b.shift = 3 AND b.status != 1 AND b.is_manual IS NULL GROUP BY date(b.`tanggal`))" => 'sore',
		"(SELECT COUNT(*) AS trans_count FROM tindakan_hd b WHERE date(b.tanggal) = date(tindakan_hd.tanggal) AND b.shift = 4 AND b.status != 1 AND b.is_manual IS NULL GROUP BY date(b.`tanggal`))" => 'malam',
		
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($tgl_awal,$tgl_akhir, $penjamin_id)
	{	
 		
		$join1 = array('pasien',$this->_table . '.pasien_id = pasien.id','left');
		$join2 = array('tindakan_hd_penaksiran',$this->_table . '.id = tindakan_hd_penaksiran.tindakan_hd_id','left');
		$join_tables = array($join1,$join2);		

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = 'tindakan_hd.tanggal';
		$params['sort_dir'] = 'asc';
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
	  	$this->db->where('tindakan_hd.status',3);
	  	$this->db->where('tindakan_hd.is_active',1);
	  	$this->db->where('tindakan_hd.is_manual', NULL);
	  	$this->db->where("DATE(tindakan_hd.tanggal) >= ", $tgl_awal);
		$this->db->where("DATE(tindakan_hd.tanggal) <= ", $tgl_akhir);
		if($penjamin_id != 0 && $penjamin_id == 1){
			$this->db->where($this->_table.'.penjamin_id', $penjamin_id);
		}if($penjamin_id != 0 && $penjamin_id != 1){
			$this->db->where($this->_table.'.penjamin_id !=', 1);
		}
		
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
	  	$this->db->where('tindakan_hd.status',3);
	  	$this->db->where('tindakan_hd.is_active',1);
	  	$this->db->where('tindakan_hd.is_manual', NULL);
	  	$this->db->where("DATE(tindakan_hd.tanggal) >= ", $tgl_awal);
		$this->db->where("DATE(tindakan_hd.tanggal) <= ", $tgl_akhir);
		if($penjamin_id != 0 && $penjamin_id == 1){
			$this->db->where($this->_table.'.penjamin_id', $penjamin_id);
		}if($penjamin_id != 0 && $penjamin_id != 1){
			$this->db->where($this->_table.'.penjamin_id !=', 1);
		}
		
		  
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
	  	$this->db->where('tindakan_hd.status',3);
	  	$this->db->where('tindakan_hd.is_active',1);
	  	$this->db->where('tindakan_hd.is_manual', NULL);
	  	$this->db->where("DATE(tindakan_hd.tanggal) >= ", $tgl_awal);
		$this->db->where("DATE(tindakan_hd.tanggal) <= ", $tgl_akhir);
		if($penjamin_id != 0 && $penjamin_id == 1){
			$this->db->where($this->_table.'.penjamin_id', $penjamin_id);
		}if($penjamin_id != 0 && $penjamin_id != 1){
			$this->db->where($this->_table.'.penjamin_id !=', 1);
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

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable_report($start_month,$start_year,$penjamin_id)
	{	
		$level_id = $this->session->userdata('level_id');
		$user_id = $this->session->userdata('user_id');

		if($level_id == config_item('level_marketing_id')){
			$datatable_columns_report = array(
			//column di table  => alias
				'DATE(tindakan_hd.tanggal)' => 'date', 
				'COUNT(*)'      => 'trans_count', 
				"(SELECT COUNT(*) AS trans_count FROM tindakan_hd b JOIN pasien ON b.pasien_id = pasien.id WHERE date(b.tanggal) = date(tindakan_hd.tanggal) AND b.shift = 1 AND b.status != 1 AND b.is_manual IS NULL AND pasien.marketing_id = ".$user_id." GROUP BY date(b.`tanggal`))" => 'pagi',
				"(SELECT COUNT(*) AS trans_count FROM tindakan_hd b JOIN pasien ON b.pasien_id = pasien.id WHERE date(b.tanggal) = date(tindakan_hd.tanggal) AND b.shift = 2 AND b.status != 1 AND b.is_manual IS NULL AND pasien.marketing_id = ".$user_id." GROUP BY date(b.`tanggal`))" => 'siang',
				"(SELECT COUNT(*) AS trans_count FROM tindakan_hd b JOIN pasien ON b.pasien_id = pasien.id WHERE date(b.tanggal) = date(tindakan_hd.tanggal) AND b.shift = 3 AND b.status != 1 AND b.is_manual IS NULL AND pasien.marketing_id = ".$user_id." GROUP BY date(b.`tanggal`))" => 'sore',
				"(SELECT COUNT(*) AS trans_count FROM tindakan_hd b JOIN pasien ON b.pasien_id = pasien.id WHERE date(b.tanggal) = date(tindakan_hd.tanggal) AND b.shift = 4 AND b.status != 1 AND b.is_manual IS NULL AND pasien.marketing_id = ".$user_id." GROUP BY date(b.`tanggal`))" => 'malam',
				
			);
		}else{
			$datatable_columns_report = array(
			//column di table  => alias
				'DATE(tindakan_hd.tanggal)' => 'date', 
				'COUNT(*)'      => 'trans_count', 
				"(SELECT COUNT(*) AS trans_count FROM tindakan_hd b WHERE date(b.tanggal) = date(tindakan_hd.tanggal) AND b.shift = 1 AND b.status != 1 AND b.is_manual IS NULL GROUP BY date(b.`tanggal`))" => 'pagi',
				"(SELECT COUNT(*) AS trans_count FROM tindakan_hd b WHERE date(b.tanggal) = date(tindakan_hd.tanggal) AND b.shift = 2 AND b.status != 1 AND b.is_manual IS NULL GROUP BY date(b.`tanggal`))" => 'siang',
				"(SELECT COUNT(*) AS trans_count FROM tindakan_hd b WHERE date(b.tanggal) = date(tindakan_hd.tanggal) AND b.shift = 3 AND b.status != 1 AND b.is_manual IS NULL GROUP BY date(b.`tanggal`))" => 'sore',
				"(SELECT COUNT(*) AS trans_count FROM tindakan_hd b WHERE date(b.tanggal) = date(tindakan_hd.tanggal) AND b.shift = 4 AND b.status != 1 AND b.is_manual IS NULL GROUP BY date(b.`tanggal`))" => 'malam',
				
			);
		}
 		
 		if($penjamin_id != 0 && $penjamin_id == 1){
 			if($level_id == config_item('level_marketing_id')){
				$datatable_columns_report = array(
				//column di table  => alias
					'DATE(tindakan_hd.tanggal)' => 'date', 
					'COUNT(*)'      => 'trans_count', 
					"(SELECT COUNT(*) AS trans_count FROM tindakan_hd b JOIN pasien ON b.pasien_id = pasien.id WHERE date(b.tanggal) = date(tindakan_hd.tanggal) AND b.shift = 1 AND b.status != 1 AND b.is_manual IS NULL AND pasien.marketing_id = ".$user_id." AND b.penjamin_id = ".$penjamin_id." GROUP BY date(b.`tanggal`))" => 'pagi',
					"(SELECT COUNT(*) AS trans_count FROM tindakan_hd b JOIN pasien ON b.pasien_id = pasien.id WHERE date(b.tanggal) = date(tindakan_hd.tanggal) AND b.shift = 2 AND b.status != 1 AND b.is_manual IS NULL AND pasien.marketing_id = ".$user_id." AND b.penjamin_id = ".$penjamin_id." GROUP BY date(b.`tanggal`))" => 'siang',
					"(SELECT COUNT(*) AS trans_count FROM tindakan_hd b JOIN pasien ON b.pasien_id = pasien.id WHERE date(b.tanggal) = date(tindakan_hd.tanggal) AND b.shift = 3 AND b.status != 1 AND b.is_manual IS NULL AND pasien.marketing_id = ".$user_id." AND b.penjamin_id = ".$penjamin_id." GROUP BY date(b.`tanggal`))" => 'sore',
					"(SELECT COUNT(*) AS trans_count FROM tindakan_hd b JOIN pasien ON b.pasien_id = pasien.id WHERE date(b.tanggal) = date(tindakan_hd.tanggal) AND b.shift = 4 AND b.status != 1 AND b.is_manual IS NULL AND pasien.marketing_id = ".$user_id." AND b.penjamin_id = ".$penjamin_id." GROUP BY date(b.`tanggal`))" => 'malam',
					
				);
			}else{
				$datatable_columns_report = array(
			//column di table  => alias
					'DATE(tindakan_hd.tanggal)' => 'date', 
					'COUNT(*)'      => 'trans_count', 
					"(SELECT COUNT(*) AS trans_count FROM tindakan_hd b WHERE date(b.tanggal) = date(tindakan_hd.tanggal) AND b.shift = 1 AND b.status != 1 AND b.is_manual IS NULL AND b.penjamin_id = ".$penjamin_id." GROUP BY date(b.`tanggal`))" => 'pagi',
					"(SELECT COUNT(*) AS trans_count FROM tindakan_hd b WHERE date(b.tanggal) = date(tindakan_hd.tanggal) AND b.shift = 2 AND b.status != 1 AND b.is_manual IS NULL AND b.penjamin_id = ".$penjamin_id." GROUP BY date(b.`tanggal`))" => 'siang',
					"(SELECT COUNT(*) AS trans_count FROM tindakan_hd b WHERE date(b.tanggal) = date(tindakan_hd.tanggal) AND b.shift = 3 AND b.status != 1 AND b.is_manual IS NULL AND b.penjamin_id = ".$penjamin_id." GROUP BY date(b.`tanggal`))" => 'sore',
					"(SELECT COUNT(*) AS trans_count FROM tindakan_hd b WHERE date(b.tanggal) = date(tindakan_hd.tanggal) AND b.shift = 4 AND b.status != 1 AND b.is_manual IS NULL AND b.penjamin_id = ".$penjamin_id." GROUP BY date(b.`tanggal`))" => 'malam',
					
				);
			}
		}if($penjamin_id != 0 && $penjamin_id != 1){
			if($level_id == config_item('level_marketing_id')){
				$datatable_columns_report = array(
				//column di table  => alias
					'DATE(tindakan_hd.tanggal)' => 'date', 
					'COUNT(*)'      => 'trans_count', 
					"(SELECT COUNT(*) AS trans_count FROM tindakan_hd b JOIN pasien ON b.pasien_id = pasien.id WHERE date(b.tanggal) = date(tindakan_hd.tanggal) AND b.shift = 1 AND b.status != 1 AND b.is_manual IS NULL AND pasien.marketing_id = ".$user_id." AND b.penjamin_id != 1 GROUP BY date(b.`tanggal`))" => 'pagi',
					"(SELECT COUNT(*) AS trans_count FROM tindakan_hd b JOIN pasien ON b.pasien_id = pasien.id WHERE date(b.tanggal) = date(tindakan_hd.tanggal) AND b.shift = 2 AND b.status != 1 AND b.is_manual IS NULL AND pasien.marketing_id = ".$user_id." AND b.penjamin_id != 1 GROUP BY date(b.`tanggal`))" => 'siang',
					"(SELECT COUNT(*) AS trans_count FROM tindakan_hd b JOIN pasien ON b.pasien_id = pasien.id WHERE date(b.tanggal) = date(tindakan_hd.tanggal) AND b.shift = 3 AND b.status != 1 AND b.is_manual IS NULL AND pasien.marketing_id = ".$user_id." AND b.penjamin_id != 1 GROUP BY date(b.`tanggal`))" => 'sore',
					"(SELECT COUNT(*) AS trans_count FROM tindakan_hd b JOIN pasien ON b.pasien_id = pasien.id WHERE date(b.tanggal) = date(tindakan_hd.tanggal) AND b.shift = 4 AND b.status != 1 AND b.is_manual IS NULL AND pasien.marketing_id = ".$user_id." AND b.penjamin_id != 1 GROUP BY date(b.`tanggal`))" => 'malam',
					
				);
			}else{
				$datatable_columns_report = array(
			//column di table  => alias
					'DATE(tindakan_hd.tanggal)' => 'date', 
					'COUNT(*)'      => 'trans_count', 
					"(SELECT COUNT(*) AS trans_count FROM tindakan_hd b WHERE date(b.tanggal) = date(tindakan_hd.tanggal) AND b.shift = 1 AND b.status != 1 AND b.is_manual IS NULL AND b.penjamin_id != 1 GROUP BY date(b.`tanggal`))" => 'pagi',
					"(SELECT COUNT(*) AS trans_count FROM tindakan_hd b WHERE date(b.tanggal) = date(tindakan_hd.tanggal) AND b.shift = 2 AND b.status != 1 AND b.is_manual IS NULL AND b.penjamin_id != 1 GROUP BY date(b.`tanggal`))" => 'siang',
					"(SELECT COUNT(*) AS trans_count FROM tindakan_hd b WHERE date(b.tanggal) = date(tindakan_hd.tanggal) AND b.shift = 3 AND b.status != 1 AND b.is_manual IS NULL AND b.penjamin_id != 1 GROUP BY date(b.`tanggal`))" => 'sore',
					"(SELECT COUNT(*) AS trans_count FROM tindakan_hd b WHERE date(b.tanggal) = date(tindakan_hd.tanggal) AND b.shift = 4 AND b.status != 1 AND b.is_manual IS NULL AND b.penjamin_id != 1 GROUP BY date(b.`tanggal`))" => 'malam',
					
				);
			}
		}
		$join1 = array('pasien', $this->_table.'.pasien_id = pasien.id','left');
 		$join_tables = array($join1);		

		// get params dari input postnya datatable
		$params = $this->datatable_param($datatable_columns_report);
		$params['sort_by'] = 'tindakan_hd.tanggal';
		$params['sort_dir'] = 'asc';
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('tindakan_hd.status !=',1);
		$this->db->where('tindakan_hd.status !=',4);
	  	$this->db->where('tindakan_hd.is_active',1);
	  	$this->db->where('tindakan_hd.is_manual', NULL);
	  	$this->db->where('MONTH(tindakan_hd.tanggal)',$start_month);
	  	$this->db->where('YEAR(tindakan_hd.tanggal)',$start_year);
	  	if($penjamin_id != 0 && $penjamin_id == 1){
			$this->db->where($this->_table.'.penjamin_id', $penjamin_id);
		}if($penjamin_id != 0 && $penjamin_id != 1){
			$this->db->where($this->_table.'.penjamin_id !=', 1);
		}
		if($level_id == config_item('level_marketing_id')){
			$this->db->where('pasien.marketing_id', $user_id);
		}
	  	$this->db->group_by('date(tindakan_hd.tanggal)');
		
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('tindakan_hd.status != ',1);
		$this->db->where('tindakan_hd.status !=',4);
	  	$this->db->where('tindakan_hd.is_active',1);
	  	$this->db->where('tindakan_hd.is_manual', NULL);
	  	$this->db->where('MONTH(tindakan_hd.tanggal)',$start_month);
	  	$this->db->where('YEAR(tindakan_hd.tanggal)',$start_year);
	  	if($penjamin_id != 0 && $penjamin_id == 1){
			$this->db->where($this->_table.'.penjamin_id', $penjamin_id);
		}if($penjamin_id != 0 && $penjamin_id != 1){
			$this->db->where($this->_table.'.penjamin_id !=', 1);
		}
		if($level_id == config_item('level_marketing_id')){
			$this->db->where('pasien.marketing_id', $user_id);
		}
	  	$this->db->group_by('date(tindakan_hd.tanggal)');

		
		  
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('tindakan_hd.status != ',1);
		$this->db->where('tindakan_hd.status != ',4);
	  	$this->db->where('tindakan_hd.is_active',1);
	  	$this->db->where('tindakan_hd.is_manual', NULL);
	  	$this->db->where('MONTH(tindakan_hd.tanggal)',$start_month);
	  	$this->db->where('YEAR(tindakan_hd.tanggal)',$start_year);
	  	if($penjamin_id != 0 && $penjamin_id == 1){
			$this->db->where($this->_table.'.penjamin_id', $penjamin_id);
		}if($penjamin_id != 0 && $penjamin_id != 1){
			$this->db->where($this->_table.'.penjamin_id !=', 1);
		}
		if($level_id == config_item('level_marketing_id')){
			$this->db->where('pasien.marketing_id', $user_id);
		}
	  	$this->db->group_by('date(tindakan_hd.tanggal)');

		// tentukan kolom yang mau diselect
		foreach ($datatable_columns_report as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $datatable_columns_report;
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

	public function get_data_export($tgl_awal, $tgl_akhir)
	{
		$SQL = "SELECT DATE(tindakan_hd.tanggal) as tanggal, pasien.no_member as no_member, pasien.nama as nama, tindakan_hd_penaksiran.waktu as waktu, tindakan_hd_penaksiran.machine_no as machine_no, tindakan_hd_penaksiran.assessment_cgs as assessment_cgs, tindakan_hd_penaksiran.medical_diagnose as medical_diagnose FROM tindakan_hd LEFT JOIN pasien ON (tindakan_hd.pasien_id = pasien.id) LEFT JOIN tindakan_hd_penaksiran ON (tindakan_hd.id = tindakan_hd_penaksiran.tindakan_hd_id) WHERE tindakan_hd.status = 3 AND DATE(tindakan_hd.tanggal) >= '".$tgl_awal."' AND DATE(tindakan_hd.tanggal) <= '".$tgl_akhir."' ORDER BY tindakan_hd.tanggal ASC";

		return $this->db->query($SQL)->result_array();
	}

	public function csv($tgl_awal, $tgl_akhir,$penjamin_id)
	{
		$wheres = ' tindakan_hd.is_manual IS NULL';
		if($penjamin_id != 0 && $penjamin_id == 1){
			$wheres = ' AND tindakan_hd.penjamin_id = 1';
		}if($penjamin_id != 0 && $penjamin_id != 1){
			$wheres = ' AND tindakan_hd.penjamin_id != 1';
		}

        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $query = $this->db->query("SELECT 'Tanggal','No. RM', 'Nama Pasien','Jam Pelayanan','No. Mesin','Assesment GCS','Dialisis Program' UNION ALL (SELECT DATE(tindakan_hd.tanggal) as tanggal, pasien.no_member as no_member, pasien.nama as nama, tindakan_hd_penaksiran.waktu as waktu, tindakan_hd_penaksiran.machine_no as machine_no, tindakan_hd_penaksiran.assessment_cgs as assessment_cgs, tindakan_hd_penaksiran.medical_diagnose as medical_diagnose FROM tindakan_hd LEFT JOIN pasien ON (tindakan_hd.pasien_id = pasien.id) LEFT JOIN tindakan_hd_penaksiran ON (tindakan_hd.id = tindakan_hd_penaksiran.tindakan_hd_id) WHERE tindakan_hd.status = 3 AND DATE(tindakan_hd.tanggal) >= '".$tgl_awal."' AND DATE(tindakan_hd.tanggal) <= '".$tgl_akhir."' ".$wheres." ORDER BY tindakan_hd.tanggal ASC)");
        $delimiter = ",";
        $newline = "\r\n";
        $data = $this->dbutil->csv_from_result($query, $delimiter, $newline);
        force_download('Laporan HD '.date('M Y', strtotime($tgl_awal)).'.csv', $data);
	}

	public function get_data_pertanggal($tanggal, $penjamin_id)
	{
		$SQL = "SELECT COUNT(*) AS jumlah FROM tindakan_hd b WHERE date(b.tanggal) = '$tanggal' AND b.status = 3 AND b.is_active = 1 AND b.is_manual IS NULL";
		if($penjamin_id != 0 && $penjamin_id == 1){
			$SQL .= ' AND tindakan_hd.penjamin_id = 1';
		}if($penjamin_id != 0 && $penjamin_id != 1){
			$SQL .= ' AND tindakan_hd.penjamin_id != 1';
		}
		 
		return $this->db->query($SQL);
	}


	public function get_data_perbulan($tanggal,$bulan,$tahun, $penjamin_id)
	{
		$SQL = "SELECT COUNT(*) AS jumlah_total FROM tindakan_hd b WHERE date(b.tanggal) <= '$tanggal' AND b.status = 3 AND b.is_active = 1";
		if($penjamin_id != 0 && $penjamin_id == 1){
			$SQL .= ' AND b.penjamin_id = 1';
		}if($penjamin_id != 0 && $penjamin_id != 1){
			$SQL .= ' AND b.penjamin_id != 1';
		}
		$SQL .= " AND month(b.tanggal) = '$bulan' AND year(b.tanggal) = '$tahun' AND b.is_manual IS NULL";
		 
		return $this->db->query($SQL);
	}

	public function get_data_all($tahun, $penjamin_id)
	{
		$SQL = "SELECT COUNT(*) AS jumlah_total, date(b.tanggal) as month FROM tindakan_hd b WHERE b.status = 3 AND b.is_active = 1";

		if($penjamin_id != 0 && $penjamin_id == 1){
			$SQL .= ' AND b.penjamin_id = 1';
		}if($penjamin_id != 0 && $penjamin_id != 1){
			$SQL .= ' AND b.penjamin_id != 1';
		}
		 
		if($tahun != 0){
			$SQL .= " AND year(b.tanggal) = '$tahun' AND b.is_manual IS NULL";
		}

		$SQL .= " GROUP BY month(b.tanggal), year(b.tanggal)";
		
		return $this->db->query($SQL);
	}
}
