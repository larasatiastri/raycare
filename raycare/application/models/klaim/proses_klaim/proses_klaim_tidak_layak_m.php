<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Proses_klaim_tidak_layak_m extends MY_Model {

	protected $_table        = 'proses_klaim_tidak_layak';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
			
	);

	private $_fillable_edit = array(
		
	);

	protected $datatable_columns = array(
		//column di table          => alias
		'proses_klaim.periode_tindakan'       => 'periode_tindakan',
		'proses_klaim.no_surat'               => 'no_surat',
		'proses_klaim.jumlah_tindakan'        => 'jumlah_tindakan',
		'proses_klaim.jumlah_tarif_riil'      => 'jumlah_tarif_riil',
		'proses_klaim.jumlah_tarif_ina'       => 'jumlah_tarif_ina',
		'proses_klaim.jumlah_tindakan_verif'  => 'jumlah_tindakan_verif',
		'proses_klaim.jumlah_tarif_ina_verif' => 'jumlah_tarif_ina_verif',
		'proses_klaim.tanggal'                => 'tanggal',
		'proses_klaim.status'                 => 'status',
		'proses_klaim.id'                     => 'id',
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
		$params['sort_by']	= 'proses_klaim.id';
		$params['sort_dir']	= 'desc';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where_in('status', $status);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where_in('status', $status);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where_in('status', $status);

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

	public function get_data($proses_klaim_id)
	{
		$this->db->join('pasien', $this->_table.'.pasien_id = pasien.id','left');
		$this->db->where('proses_klaim_id', $proses_klaim_id);
		$this->db->where($this->_table.'.is_active', 1);

		return $this->db->get($this->_table)->result();
	}
}
