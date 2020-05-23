<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tindakan_resep_obat_history_m extends MY_Model {

	protected $_table        = 'tindakan_resep_obat_history';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		'tindakan_id',
		'tipe_tindakan',
		'pasien_id', 
		'dokter_id', 
		'status',	 		
	);

	private $_fillable_edit = array(
		'tindakan_id',
		'tipe_tindakan',
		'pasien_id', 
		'dokter_id', 
		'status',
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'tindakan_resep_obat_history.id'          => 'id', 
		'cabang.nama'                     => 'nama_cabang', 
		'tindakan_resep_obat_history.nomor_resep' => 'nomor_resep', 
		'pasien.nama'                     => 'nama_pasien', 
		'dr.nama'                         => 'nama_dokter', 
		'usr.nama'                        => 'nama_user', 
		'tindakan_resep_obat_history.tipe_resep'  => 'tipe_resep'
	 
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
		$join1 = array('pasien', $this->_table.'.pasien_id = pasien.id', 'left');
		$join2 = array('cabang', $this->_table.'.cabang_id = cabang.id', 'left');
		$join3 = array('user dr', $this->_table.'.dokter_id = dr.id', 'left');
		$join4 = array('user usr', $this->_table.'.modified_by = usr.id', 'left');
		$join_tables = array($join1,$join2,$join3,$join4);
			
		$wheres = array(
			'tindakan_resep_obat_history.status'        => $status,
			'tindakan_resep_obat_history.is_active'     => 1,
			'tindakan_resep_obat_history.tipe_tindakan' => 1
		);
		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by']  = $this->_table.'.id';
		if($status == 1){
			$params['sort_dir'] = 'asc';
		}else{
			$params['sort_dir'] = 'desc';

		}
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($wheres);
	 
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($wheres);
		 
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($wheres);
	 

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

	public function getsatuanobat($id)
	{
		$sql= "select * from item_satuan where item_id=?";

		return $this->db->query($sql,$id);
	}

	public function get_max_no($roman_month)
	{
		$month_lenght = strlen($roman_month);
		switch ($month_lenght) {
			case 1:
				$SQL = "SELECT MAX(SUBSTRING(nomor_resep,6,3)) as max_no FROM tindakan_resep_obat_history WHERE SUBSTR(nomor_resep,14,1)='".$roman_month."' AND RIGHT(nomor_resep,4) = '".date('Y')."' ";
				break;
			case 2:
				$SQL = "SELECT MAX(SUBSTRING(nomor_resep,6,3)) as max_no FROM tindakan_resep_obat_history WHERE SUBSTR(nomor_resep,14,2)='".$roman_month."' AND RIGHT(nomor_resep,4) = '".date('Y')."' ";
				break;
			case 3:
				$SQL = "SELECT MAX(SUBSTRING(nomor_resep,6,3)) as max_no FROM tindakan_resep_obat_history WHERE SUBSTR(nomor_resep,14,3)='".$roman_month."' AND RIGHT(nomor_resep,4) = '".date('Y')."' ";
				break;
			case 4:
				$SQL = "SELECT MAX(SUBSTRING(nomor_resep,6,3)) as max_no FROM tindakan_resep_obat_history WHERE SUBSTR(nomor_resep,14,4)='".$roman_month."' AND RIGHT(nomor_resep,4) = '".date('Y')."' ";
				break;
		}
		return $this->db->query($SQL);

	}
}

/* End of file Item_m.php */
/* Location: ./application/models/master/cabang_m.php */