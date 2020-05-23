<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Biaya_item_tindakan_m extends MY_Model {

	protected $_table        = 'biaya_item_tindakan';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
	);

	private $_fillable_edit = array(
		  
	);

	// Array of database columns which should be read and sent back to DataTables        b                
	protected $datatable_columns = array(
		//column di table  => alias
		'biaya_item_tindakan.tanggal'       => 'tanggal',
		'biaya_item_tindakan.jenis_tindakan'       => 'jenis_tindakan',
		'biaya_item_tindakan.shift'       => 'shift',
		'biaya_item_tindakan.user_penerima_id' => 'user_penerima_id',
		'apoteker.nama' => 'nama_apoteker',
		'penerima.nama' => 'nama_penerima',
		'biaya_item_tindakan.keterangan' => 'keterangan',
		'biaya_item_tindakan.id'            => 'id',
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
		$join1 = array('user apoteker', $this->_table.'.created_by = apoteker.id', 'left');
		$join2 = array('user penerima', $this->_table.'.user_penerima_id = penerima.id', 'left');
		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = $this->_table.'.tanggal';
		$params['sort_dir'] = 'desc';
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

	public function get_data_akun_tipe($id)
	{
		$format = "SELECT
						biaya_item_tindakan.id,
						biaya_item_tindakan.nama,
						biaya_item_tindakan.tipe_akun
						FROM
						biaya_item_tindakan
						WHERE biaya_item_tindakan.id = $id";

		return $this->db->query($format);
	}

	public function get_max_id_biaya()
	{
		$format = "SELECT MAX(SUBSTRING(`id`,12,4)) AS max_id FROM `biaya_item_tindakan` WHERE SUBSTR(`id`,4,7) = DATE_FORMAT(NOW(), '%m-%Y');";	
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

