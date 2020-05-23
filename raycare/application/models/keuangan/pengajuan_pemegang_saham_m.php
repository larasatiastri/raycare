<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pengajuan_pemegang_saham_m extends MY_Model {

	protected $_table        = 'pengajuan_pemegang_saham';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'pengajuan_pemegang_saham.tanggal'      => 'tanggal',
		'pengajuan_pemegang_saham.nomor_pengajuan'     	  => 'nomor_pengajuan',

		'user.nama'		=> 'nama_dibuat_oleh',
		'pengajuan_pemegang_saham.nominal'   => 'nominal',
		'pengajuan_pemegang_saham.keterangan'      => 'keterangan',
		'pengajuan_pemegang_saham.status'       => 'status',
		'pengajuan_pemegang_saham.id'           => 'id', 

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
		$level_id = $this->session->userdata('level_id');

		$join1 = array('user',$this->_table.'.created_by = user.id', 'left');
		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = $this->_table.'.tanggal';
		$params['sort_dir'] = 'desc';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('user.user_level_id', $level_id);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('user.user_level_id', $level_id);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('user.user_level_id', $level_id);

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

	public function get_max_id()
	{
		$format = "SELECT MAX(RIGHT(`id`,3)) AS max_id FROM `pengajuan_pemegang_saham` WHERE SUBSTR(`id`,5,4) = DATE_FORMAT(NOW(), '%m%y');";	
		return $this->db->query($format);
	}
	public function get_max_nomor()
	{
		$format = "SELECT MAX(RIGHT(`nomor_pengajuan`,3)) AS max_nomor FROM `pengajuan_pemegang_saham` WHERE SUBSTR(`id`,5,4) = DATE_FORMAT(NOW(), '%m%y');";	
		return $this->db->query($format);
	}


}

/* End of file pengajuan_pemegang_saham.php */
/* Location: ./application/models/gudang/pengajuan_pemegang_saham.php */