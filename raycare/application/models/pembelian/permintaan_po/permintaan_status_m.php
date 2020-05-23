<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permintaan_status_m extends MY_Model {

	protected $_table        = 'permintaan_status';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'permintaan_status.id'              => 'id',
		'permintaan_status.created_date'    => 'tanggal',
		'user.inisial'                      => 'inisial',
		'user.nama'                         => 'nama',
		'da.kode'                           => 'kode_divisi_buat',
		'permintaan_status.transaksi_id'  => 'transaksi_id',
		'permintaan_status.tipe_transaksi'  => 'tipe_transaksi',
		'permintaan_status.transaksi_nomor' => 'transaksi_nomor',
		'permintaan_status.status'          => 'status',
		'permintaan_status.tipe_persetujuan'          => 'tipe_persetujuan',
		'b.nama'                            => 'nama_level_proses',
		'permintaan_status.user_level_id'   => 'user_level_id',
		'permintaan_status.divisi'          => 'divisi',
		'db.kode'                           => 'kode_divisi_proses',
		'order_permintaan_barang.subjek'     => 'subjek',
		'order_permintaan_barang.tipe'     => 'tipe',
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
		$cabang_id = $this->session->userdata('cabang_id');
		
		$join1 = array('user' , $this->_table.'.created_by = user.id','left');
		$join2 = array('user_level a', 'user.user_level_id = a.id','left');
		$join3 = array('divisi da', 'a.divisi_id = da.id', 'left');
		$join4 = array('user_level b', $this->_table.'.user_level_id = b.id','left');
		$join5 = array('divisi db', $this->_table.'.divisi = b.id','left');
		$join6 = array('order_permintaan_barang', $this->_table.'.transaksi_id = order_permintaan_barang.id','left');

 		$join_tables = array($join1, $join2, $join3, $join4, $join5, $join6);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by']="permintaan_status.created_date";
		$params['sort_dir']="desc";

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.tipe_transaksi',1);
		$this->db->where($this->_table.'.status <',5);
		$this->db->where($this->_table.'.is_active',1);
		if($user_level_id != config_item('user_developer')  && $user_level_id != 14 && $user_level_id != 9){
			$this->db->where('order_permintaan_barang.user_level_id',$user_level_id);
			$this->db->where('order_permintaan_barang.cabang_id',$cabang_id);
		}
		
		
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.tipe_transaksi',1);
		$this->db->where($this->_table.'.status <',5);
		$this->db->where($this->_table.'.is_active',1);
		if($user_level_id != config_item('user_developer') && $user_level_id != 14 && $user_level_id != 9){
			$this->db->where('order_permintaan_barang.user_level_id',$user_level_id);
			$this->db->where('order_permintaan_barang.cabang_id',$cabang_id);
		}
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();
		
		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.tipe_transaksi',1);
		$this->db->where($this->_table.'.status <',5);
		$this->db->where($this->_table.'.is_active',1);
		if($user_level_id != config_item('user_developer') && $user_level_id != 14 && $user_level_id != 9){
			$this->db->where('order_permintaan_barang.user_level_id',$user_level_id);
			$this->db->where('order_permintaan_barang.cabang_id',$cabang_id);
		}
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);

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
	public function get_datatable_history()
	{	
		$join1 = array('user' , $this->_table.'.created_by = user.id','left');
		$join2 = array('user_level a', 'user.user_level_id = a.id','left');
		$join3 = array('divisi da', 'a.divisi_id = da.id', 'left');
		$join4 = array('user_level b', $this->_table.'.user_level_id = b.id','left');
		$join5 = array('divisi db', $this->_table.'.divisi = b.id','left');

 		$join_tables = array($join1, $join2, $join3, $join4, $join5);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by']="permintaan_status.created_date";
		$params['sort_dir']="desc";

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.tipe_transaksi',1);
		$this->db->where($this->_table.'.status >=',12);
		$this->db->where($this->_table.'.is_active',1);
		
		
		
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.tipe_transaksi',1);
		$this->db->where($this->_table.'.status >=',12);
		$this->db->where($this->_table.'.is_active',1);
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();
		
		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.tipe_transaksi',1);
		$this->db->where($this->_table.'.status >=',12);
		$this->db->where($this->_table.'.is_active',1);
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);

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

	public function get_max_id_permintaan()
	{
		$format = "SELECT MAX(SUBSTRING(`id`,12,4)) AS max_id FROM `permintaan_status` WHERE SUBSTR(`id`,4,7) = DATE_FORMAT(NOW(), '%m-%Y');";	
		return $this->db->query($format);
	}

}

/* End of file permintaan_status.php */
/* Location: ./application/models/keuangan/permintaan_status.php */