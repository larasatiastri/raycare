<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Order_permintaan_barang_m extends MY_Model {

	protected $_table        = 'order_permintaan_barang';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'order_permintaan_barang.id'         => 'id', 
		'order_permintaan_barang.tanggal'    => 'tanggal', 
		'user.nama'                          => 'user', 
		'user_level.nama'                    => 'user_level',
		'order_permintaan_barang.subjek'     => 'subjek',
		'order_permintaan_barang.keterangan' => 'keterangan',
		'order_permintaan_barang.is_active'  => 'is_active',
		'order_permintaan_barang.is_finish'  => 'is_finish',
		'(SELECT COUNT(*) FROM order_permintaan_barang_detail WHERE order_permintaan_barang_detail.order_permintaan_barang_id = 2)'	=> 'jumlah'
	);

	protected $datatable_columns_proses = array(
		//column di table  => alias
		'order_permintaan_barang.id'         => 'id', 
		'order_permintaan_barang.tanggal'    => 'tanggal', 
		'user.nama'                          => 'user', 
		'user_level.nama'                    => 'user_level',
		'order_permintaan_barang.subjek'     => 'subjek',
		'order_permintaan_barang.keterangan' => 'keterangan',
		'order_permintaan_barang.is_active'  => 'is_active',
		'order_permintaan_barang.is_finish'  => 'is_finish',
		'(SELECT COUNT(*) FROM order_permintaan_barang_detail WHERE order_permintaan_barang_detail.order_permintaan_barang_id = order_permintaan_barang.id)'	=> 'jumlah_terdaftar',
		'(SELECT COUNT(*) FROM order_permintaan_barang_detail_other WHERE order_permintaan_barang_detail_other.order_permintaan_barang_id = order_permintaan_barang.id)'	=> 'jumlah_tidak_terdaftar',
		'MAX(persetujuan_permintaan_barang.status)' => 'status_terakhir'
		);

	protected $datatable_columns_item = array(
		//column di table  => alias
		'order_permintaan_barang.id'         => 'id', 
		'order_permintaan_barang_detail_other.id'   => 'id_detail', 
		'order_permintaan_barang_detail_other.nama'   => 'nama', 
		'order_permintaan_barang_detail_other.jumlah'   => 'jumlah', 
		
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
		$user_level_id = $this->session->userdata('level_id');
		$datatable_columns = array(
		//column di table  => alias
			'order_permintaan_barang.id'                                => 'id', 
			'order_permintaan_barang.tanggal'                           => 'tanggal', 
			'user.nama'                                                 => 'user', 
			'user_level.nama'                                           => 'user_level',
			'order_permintaan_barang.subjek'                            => 'subjek',
			'order_permintaan_barang.keterangan'                        => 'keterangan',
			'order_permintaan_barang.tipe'                              => 'tipe',
			'order_permintaan_barang.is_active'                         => 'is_active',
			'order_permintaan_barang.is_finish'                         => 'is_finish',
			'order_permintaan_barang.status'                            => 'status',
			'order_permintaan_barang_detail.order_permintaan_barang_id' => 'order_permintaan_barang_id',
			'order_permintaan_barang_detail.item_id'                    => 'item_id',
			'order_permintaan_barang_detail.item_satuan_id'             => 'item_satuan_id',
			'order_permintaan_barang.tipe_persetujuan'             => 'tipe_persetujuan',
			'(SELECT COUNT(DISTINCT item_id) FROM order_permintaan_barang_detail WHERE order_permintaan_barang_detail.order_permintaan_barang_id = order_permintaan_barang.id)'				=> 'jumlah_terdaftar',
			'(SELECT COUNT(DISTINCT nama) FROM order_permintaan_barang_detail_other WHERE order_permintaan_barang_detail_other.order_permintaan_barang_id = order_permintaan_barang.id)'	=> 'jumlah_tidak_terdaftar',
			'order_permintaan_barang_detail_other.order_permintaan_barang_id' => 'order_permintaan_barang_id_other',
			'order_permintaan_barang_detail_other.nama'                       => 'nama_item_other',
			'order_permintaan_barang_detail_other.satuan'                     => 'nama_satuan_other',
			'order_permintaan_barang_detail_other.jumlah'                     => 'jumlah_other'
		);

		$join1 = array('user', $this->_table.'.user_id = user.id', 'LEFT');
		// $join2 = array('persetujuan_permintaan_barang', $this->_table.'.id = persetujuan_permintaan_barang.order_permintaan_barang_id');
		$join3 = array('user_level', $this->_table.'.user_level_id = user_level.id', 'LEFT');
		$join4 = array('order_permintaan_barang_detail', $this->_table.'.id = order_permintaan_barang_detail.order_permintaan_barang_id', 'LEFT');
		$join5 = array('order_permintaan_barang_detail_other', $this->_table.'.id = order_permintaan_barang_detail_other.order_permintaan_barang_id', 'LEFT');
		$join_tables = array($join1, $join3, $join4, $join5);

		// get params dari input postnya datatable
		$params = $this->datatable_param($datatable_columns);
		$params['sort_by']=$this->_table.'.id';
		$params['sort_dir']='desc';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where($this->_table.'.is_finish',0);
		if($user_level_id != config_item('user_developer')){
			$this->db->where($this->_table.'.user_level_id',$user_level_id);
		}
		$this->db->group_by($this->_table.'.id');
		// dapatkan total row count;
		$query = $this->db->select('(1)')->get();
	    $total_records = $query->num_rows();
		// $total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active',1);
		// $this->db->where($wheres);
		$this->db->where($this->_table.'.is_finish',0);
		if($user_level_id != config_item('user_developer')){
			$this->db->where($this->_table.'.user_level_id',$user_level_id);
		}
		$this->db->group_by($this->_table.'.id');

		// $this->db->where('persetujuan_permintaan_barang.status', $status);
		// $this->db->group_by($this->_table.'.id');$this->db->where('persetujuan_permintaan_barang.order = (SELECT MAX(persetujuan_permintaan_barang.order) FROM persetujuan_permintaan_barang WHERE persetujuan_permintaan_barang.order_permintaan_barang_id = order_permintaan_barang.id)');
		// dapatkan total record filtered/search;
		$query = $this->db->select('(1)')->get();
	    $total_display_records = $query->num_rows();
		// $total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active',1);
		// $this->db->where($wheres);
		$this->db->where($this->_table.'.is_finish',0);
		if($user_level_id != config_item('user_developer')){
			$this->db->where($this->_table.'.user_level_id',$user_level_id);
		}
		$this->db->group_by($this->_table.'.id');

		// $this->db->where('persetujuan_permintaan_barang.status', $status);
		// $this->db->group_by($this->_table.'.id');$this->db->where('persetujuan_permintaan_barang.order = (SELECT MAX(persetujuan_permintaan_barang.order) FROM persetujuan_permintaan_barang WHERE persetujuan_permintaan_barang.order_permintaan_barang_id = order_permintaan_barang.id)');	

		// tentukan kolom yang mau diselect
		foreach ($datatable_columns as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $datatable_columns;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_datatable_proses($status)
	{	
		$datatable_columns_proses = array(
		//column di table  => alias
		'order_permintaan_barang.id'         => 'id', 
		'order_permintaan_barang.tanggal'   	=> 'tanggal', 
		'user.nama'   							=> 'user', 
		'user_level.nama'   					=> 'user_level',
		'order_permintaan_barang.subjek'     => 'subjek',
		'order_permintaan_barang.keterangan' => 'keterangan',
		'order_permintaan_barang.is_active'  => 'is_active',
		'order_permintaan_barang.is_finish'  => 'is_finish',
		'(SELECT COUNT(*) FROM order_permintaan_barang_detail WHERE order_permintaan_barang_detail.order_permintaan_barang_id = order_permintaan_barang.id)'	=> 'jumlah_terdaftar',
		'(SELECT COUNT(*) FROM order_permintaan_barang_detail_other WHERE order_permintaan_barang_detail_other.order_permintaan_barang_id = order_permintaan_barang.id)'	=> 'jumlah_tidak_terdaftar',
		'MAX(persetujuan_permintaan_barang.status)' => 'status_terakhir',
		'order_permintaan_barang.tipe' 		=> 'tipe'
		);

		$join1 = array('user', $this->_table.'.user_id = user.id', 'LEFT');
		$join2 = array('persetujuan_permintaan_barang', $this->_table.'.id = persetujuan_permintaan_barang.order_permintaan_barang_id', 'LEFT');
		$join3 = array('user_level', $this->_table.'.user_level_id = user_level.id');
		$join4 = array('order_permintaan_barang_detail', $this->_table.'.id = order_permintaan_barang_detail.order_permintaan_barang_id', 'LEFT');
		$join5 = array('order_permintaan_barang_detail_other', $this->_table.'.id = order_permintaan_barang_detail_other.order_permintaan_barang_id', 'LEFT');
		$join_tables = array($join1, $join2, $join3, $join4, $join5);
		
		if($status == null)
		{
			$wheres = array();
		}
		else
		{
			if($status == "1")
			{
				$wheres = array(
					'(SELECT MAX(persetujuan_permintaan_barang.status) FROM persetujuan_permintaan_barang WHERE persetujuan_permintaan_barang.`order` = (SELECT MAX(persetujuan_permintaan_barang.`order`) FROM persetujuan_permintaan_barang WHERE persetujuan_permintaan_barang.order_permintaan_barang_id = order_permintaan_barang.id)) < ' => 4,
				);
			}
			else
			{
				$wheres = array(
					'(SELECT MAX(persetujuan_permintaan_barang.status) FROM persetujuan_permintaan_barang WHERE persetujuan_permintaan_barang.`order` = (SELECT MAX(persetujuan_permintaan_barang.`order`) FROM persetujuan_permintaan_barang WHERE persetujuan_permintaan_barang.order_permintaan_barang_id = order_permintaan_barang.id)) =' => 4,
				);
			}
		}
			// get params dari input postnya datatable
		$params = $this->datatable_param($datatable_columns_proses);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where($this->_table.'.is_finish',1);
		$this->db->where('persetujuan_permintaan_barang.order = (SELECT MAX(persetujuan_permintaan_barang.`order`) FROM persetujuan_permintaan_barang WHERE persetujuan_permintaan_barang.order_permintaan_barang_id = order_permintaan_barang.id)');
		$this->db->where($wheres);
		// $this->db->or_where('persetujuan_permintaan_barang.status', $status);
		$this->db->group_by($this->_table.'.id');
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where($wheres);
		$this->db->where($this->_table.'.is_finish',1);
		$this->db->group_by($this->_table.'.id');

		// $this->db->where('persetujuan_permintaan_barang.status', $status);
		$this->db->group_by($this->_table.'.id');$this->db->where('persetujuan_permintaan_barang.order = (SELECT MAX(persetujuan_permintaan_barang.`order`) FROM persetujuan_permintaan_barang WHERE persetujuan_permintaan_barang.order_permintaan_barang_id = order_permintaan_barang.id)');
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where($wheres);
		$this->db->where($this->_table.'.is_finish',1);
		$this->db->group_by($this->_table.'.id');

		// $this->db->where('persetujuan_permintaan_barang.status', $status);
		$this->db->group_by($this->_table.'.id');$this->db->where('persetujuan_permintaan_barang.order = (SELECT MAX(persetujuan_permintaan_barang.`order`) FROM persetujuan_permintaan_barang WHERE persetujuan_permintaan_barang.order_permintaan_barang_id = order_permintaan_barang.id)');	

		// tentukan kolom yang mau diselect
		foreach ($datatable_columns_proses as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $datatable_columns_proses;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_datatable_item($id)
	{	
		$join1 = array('order_permintaan_barang_detail_other', $this->_table.'.id = order_permintaan_barang_detail_other.order_permintaan_barang_id');
		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_item);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('order_permintaan_barang_detail_other.order_permintaan_barang_id', $id);
		$this->db->where('is_active',1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('order_permintaan_barang_detail_other.order_permintaan_barang_id', $id);
		$this->db->where('is_active',1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('order_permintaan_barang_detail_other.order_permintaan_barang_id', $id);
		$this->db->where('is_active',1);

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_item as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_item;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_max_kode()
    {
    	$SQL = "SELECT MAX(SUBSTRING(`id`,13,4)) AS max_kode FROM `order_permintaan_barang` WHERE SUBSTRING(`id`,5,2) = DATE_FORMAT(NOW(), '%m') and SUBSTRING(`id`,8,4) = DATE_FORMAT(NOW(), '%Y')";
    	return $this->db->query($SQL);
    }

    public function get_no_permintaan()
	{
		$format = "SELECT MAX(SUBSTRING(`nomor_permintaan`,5,3)) AS max_no_permintaan FROM `order_permintaan_barang` WHERE RIGHT(`nomor_permintaan`,4) = DATE_FORMAT(NOW(), '%Y');";	
		return $this->db->query($format);
	}
}

/* End of file cabang_m.php */
/* Location: ./application/models/barang/daftar_permintaan_po_m.php */