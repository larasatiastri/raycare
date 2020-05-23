<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Observasi_m extends MY_Model {

	protected $_table        = 'observasi_dialisis_hd';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		'id',
		'item_sub_kategori',
		'kode',
		'nama', 
		'keterangan', 
		'is_discontinue', 
		'buffer_stock', 		
		'is_jual', 		
		'id_identitas', 		
	);

	private $_fillable_edit = array(
		'id',
		'item_sub_kategori',
		'kode',
		'nama', 
		'keterangan', 
		'is_discontinue', 
		'buffer_stock', 		
		'is_jual', 		
		'id_identitas',
	);

	// Array of database columns which should be read and sent back to DataTables
	// protected $datatable_columns = array(
	// 	//column di table  => alias
	// 	'item.id'         		 => 'id', 
	// 	'item.item_sub_kategori' => 'item_sub_kategori', 
	// 	'item.kode'   			 => 'kode', 
	// 	'item.nama'   			 => 'nama', 
	// 	'item.keterangan'  		 => 'keterangan',
	// 	'item.is_discontinue'    => 'is_discontinue',
	// 	'item.buffer_stock'      => 'buffer_stock',
	// 	'item.is_jual'     		 => 'is_jual',
	// 	'item.id_identitas'      => 'id_identitas',
	// 	'item_satuan.nama'      => 'unit',
	// 	'item_harga.harga'      => 'harga',
	// 	'item_kategori.nama'      => 'kategori_item',
		
	// );

	protected $datatable_columns = array(
		//column di table  => alias
		'observasi_dialisis_hd.waktu_pencatatan'        => 'waktu_pencatatan', 
		'observasi_dialisis_hd.tekanan_darah_1'         => 'tekanan_darah_1', 
		'observasi_dialisis_hd.tekanan_darah_2'         => 'tekanan_darah_2', 
		 
		'observasi_dialisis_hd.ufg'         		 	=> 'ufg', 
		'observasi_dialisis_hd.ufr'         		 	=> 'ufr', 
		'observasi_dialisis_hd.ufv'         		 	=> 'ufv', 
		'observasi_dialisis_hd.qb'         		 		=> 'qb', 
		'observasi_dialisis_hd.tmp'         		 		=> 'tmp', 
		'observasi_dialisis_hd.vp'         		 		=> 'vp', 
		'observasi_dialisis_hd.ap'         		 		=> 'ap', 
		'observasi_dialisis_hd.cond'         		 		=> 'cond', 
		'observasi_dialisis_hd.temperature'         		 		=> 'temperature', 
		 
		'b.nama'    => 'nama', 
		'user_level.nama' => 'user_level',

		'observasi_dialisis_hd.keterangan'    => 'keterangan', 
		'observasi_dialisis_hd.id'    => 'id', 
		'observasi_dialisis_hd.is_active'    => 'is_active', 
		
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($id=null,$tipe=null)
	{	
 
	 	 
		$join1 = array('user b',$this->_table . '.user_id = b.id');

		// tambahan dari abu karena monitoring_dialisis ada tambahan di column perawat yaitu USER_LEVEL
		$join2 = array('user_level','b.user_level_id = user_level.id');
	  
		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		 
		$this->db->where('observasi_dialisis_hd.transaksi_hd_id',$id);
		if($tipe == null)
		{
			$this->db->where('observasi_dialisis_hd.is_active',1);		
		}
		 
	  
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
	 
		$this->db->where('observasi_dialisis_hd.transaksi_hd_id',$id);
		if($tipe == null)
		{
			$this->db->where('observasi_dialisis_hd.is_active',1);		
		}
	  
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		 
		$this->db->where('observasi_dialisis_hd.transaksi_hd_id',$id);
		if($tipe == null)
		{
			$this->db->where('observasi_dialisis_hd.is_active',1);		
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

		return $result; 
	}

	

	function get_id($id)
	{	
		$this->db->where('id', $id);
		$this->db->order_by('id', 'desc');
		$query = $this->db->query('SELECT id, kode FROM item');
		
		return $query->row();
		return $this->db->get($this->_table);
	}

	function get_by_trans_id($trans_id)
	{	
		$this->db->where('transaksi_hd_id', $trans_id);
		$this->db->where('is_active', 1);
		$this->db->order_by('waktu_pencatatan', 'asc');
		
		return $this->db->get($this->_table);
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

	function get_data_by_id($id)
	{
		 $sql= "select observasi_dialisis_hd.id,observasi_dialisis_hd.transaksi_hd_id,observasi_dialisis_hd.user_id,observasi_dialisis_hd.waktu_pencatatan,observasi_dialisis_hd.tekanan_darah_1,observasi_dialisis_hd.tekanan_darah_2,observasi_dialisis_hd.ufg,observasi_dialisis_hd.ufr,observasi_dialisis_hd.ufv,observasi_dialisis_hd.qb,observasi_dialisis_hd.tmp,observasi_dialisis_hd.vp,observasi_dialisis_hd.ap,observasi_dialisis_hd.cond,observasi_dialisis_hd.temperature,observasi_dialisis_hd.keterangan,user.nama,user_level.id as user_level from observasi_dialisis_hd join user on user.id=observasi_dialisis_hd.user_id join user_level on user_level.id=user.user_level_id where observasi_dialisis_hd.id=?";
		 
		 return $this->db->query($sql,$id);
	}

	function get_data_last($trans_id)
	{
		 $sql= "select * from observasi_dialisis_hd where transaksi_hd_id=? order by id desc";
		 
		 return $this->db->query($sql,$trans_id);
	}

	function get_data_first_time($trans_id)
	{
		 $sql= "select * from observasi_dialisis_hd where transaksi_hd_id=? AND is_active = 1 order by waktu_pencatatan asc LIMIT 1";
		 
		 return $this->db->query($sql,$trans_id);
	}
	function get_data_last_time($trans_id)
	{
		 $sql= "select * from observasi_dialisis_hd where transaksi_hd_id=? AND is_active = 1 order by waktu_pencatatan desc LIMIT 1";
		 
		 return $this->db->query($sql,$trans_id);
	}

}

/* End of file Item_m.php */
/* Location: ./application/models/master/cabang_m.php */