<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Item_tersimpan_m extends MY_Model {

	protected $_table        = 'simpan_item';
	protected $_order_by     = 'simpan_item_id';
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
		
	);

	protected $datatable_columns = array(
		
		//column di table            => alias
		'simpan_item.simpan_item_id' => 'simpan_item_id',
		'pasien.nama'                => 'nama_pasien', 
		'b.nama'                     => 'nama_item', 
		'simpan_item.index'  		 => 'idx',
		'simpan_item.bn_sn_lot'      => 'bn_sn_lot',
		'simpan_item.volume'      => 'volume',
		'simpan_item.expire_date'    => 'expire_date',
		'simpan_item.status_reuse'   => 'status_reuse',
		'user.nama'   => 'nama_user',
		 
	);

	protected $datatable_columns_dokter = array(
		
		//column di table            => alias
		'simpan_item.simpan_item_id' => 'simpan_item_id',
		'b.nama'                     => 'nama', 
		'simpan_item.status'         => 'status',
		'simpan_item.index'  => 'idx'
		 
	);

	protected $datatable_columns_item_tersimpan = array(
		
		//column di table  => alias
		'b.nama'                  => 'nama', 
		'simpan_item.jumlah'    => 'jumlah',
		'item_satuan.nama'           => 'item_satuan', 
		'simpan_item.item_id'        => 'item_id', 
		'simpan_item.item_satuan_id' => 'item_satuan_id', 
		'simpan_item.harga_beli'     => 'harga_beli', 
		'simpan_item.simpan_item_id' => 'id',
		'simpan_item.index'  => 'idx'
		 
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($id=null)
	{	
 
	 	 
		$join1 = array('item b',$this->_table . '.item_id = b.id');
	  
		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_dokter);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
	 
		 $this->db->where('transaksi_simpan_id',$id);
		//$this->db->group_by(array("a.nama","pasien_penjamin.no_kartu","pasien_klaim.tipe","pasien_klaim.created_date","pasien_penjamin.id")); 
		 
	 
	  
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
	 
		  $this->db->where('transaksi_simpan_id',$id);
	// 	$this->db->where('pasien_penjamin.pasien_id',$id);
	 //	$this->db->group_by(array("a.nama","pasien_penjamin.no_kartu","pasien_klaim.tipe","pasien_klaim.created_date","pasien_penjamin.id")); 
	 	 
	 	 
	  
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		 
		 $this->db->where('transaksi_simpan_id',$id);
		//$this->db->where('pasien_penjamin.pasien_id',$id);
		//$this->db->group_by(array("a.nama","pasien_penjamin.no_kartu","pasien_klaim.tipe","pasien_klaim.created_date","pasien_penjamin.id")); 
		 
		 
	  

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_dokter as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_dokter;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
   //die(dump($result->records));
		return $result; 
	}

	public function get_datatable_simpan_item($pasien_id)
	{	
 
		$join1 = array('item b', $this->_table . '.item_id = b.id');
		$join2 = array('item_satuan', $this->_table . '.item_satuan_id = item_satuan.id');
	  
		$join_tables = array($join1, $join2);

		$wheres = array();

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_item_tersimpan);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('pasien_id',$pasien_id);
		$this->db->where('simpan_item.status',1);
		$this->db->where('simpan_item.status_reuse',3);
		
		 
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('pasien_id',$pasien_id);
		$this->db->where('simpan_item.status',1);
		$this->db->where('simpan_item.status_reuse',3);
		
	 	 
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('pasien_id',$pasien_id);
		$this->db->where('simpan_item.status',1);
		$this->db->where('simpan_item.status_reuse',3);
		

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_item_tersimpan as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_item_tersimpan;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_datatable_simpan_item_pasien()
	{	
 
		$join1 = array('item b', $this->_table . '.item_id = b.id');
		$join2 = array('item_satuan', $this->_table . '.item_satuan_id = item_satuan.id');
		$join3 = array('pasien', $this->_table . '.pasien_id = pasien.id');
		$join4 = array('user', $this->_table . '.petugas_reuse = user.id','left');
	  
		$join_tables = array($join1, $join2, $join3, $join4);

		$wheres = array();

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = 'pasien.nama';
		$params['sort_dir'] = 'asc';
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('simpan_item.status',1);
		$this->db->group_by('simpan_item.pasien_id');
		$this->db->group_by('simpan_item.item_id');
		$this->db->group_by('simpan_item.bn_sn_lot');
		
		 
		// dapatkan total row count;
		//$total_records = $this->db->count_all_results();
		$query = $this->db->select('(1)')->get();
        $total_records = $query->num_rows();


		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('simpan_item.status',1);
		$this->db->group_by('simpan_item.pasien_id');
		$this->db->group_by('simpan_item.item_id');
		$this->db->group_by('simpan_item.bn_sn_lot');
		
	 	 
		// dapatkan total record filtered/search;
		//$total_display_records = $this->db->count_all_results();
		$query = $this->db->select('(1)')->get();
	    $total_display_records = $query->num_rows();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('simpan_item.status',1);
		$this->db->group_by('simpan_item.pasien_id');
		$this->db->group_by('simpan_item.item_id');
		$this->db->group_by('simpan_item.bn_sn_lot');
		

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

	// UNTUK KEPERLUAN MENAMPILKAN DATA IDENTITAS ITEM
	// DIGUNAKAN DI OBSERVASI DIALISIS ITEM
	// Created By Abu Rizal
	public function get_simpan_identitas($id)
	{
		$format = "SELECT
					simpan_item_identitas.simpan_item_identitas_id as simpan_item_identitas_id,
					simpan_item_identitas.simpan_item_id,
					simpan_item_identitas.jumlah,
					simpan_item_identitas_detail.identitas_id,
					simpan_item_identitas_detail.judul,
					simpan_item_identitas_detail.`value`,
					identitas.tipe,
					simpan_item.simpan_item_id,
					simpan_item.harga_beli,
					simpan_item.harga_jual,
					simpan_item.jumlah as jumlah_simpan_item
					FROM
					simpan_item_identitas
					LEFT JOIN simpan_item_identitas_detail ON simpan_item_identitas.simpan_item_identitas_id = simpan_item_identitas_detail.simpan_item_identitas_id
					LEFT JOIN identitas ON simpan_item_identitas_detail.identitas_id = identitas.id
					LEFT JOIN simpan_item ON simpan_item_identitas.simpan_item_id = simpan_item.simpan_item_id
					WHERE
					simpan_item_identitas.simpan_item_identitas_id = $id
					";

		return $this->db->query($format);
	}


	// UNTUK KEPERLUAN MENDAPATKAN HARGA DAN STOCK 
	// DIGUNAKAN DI OBSERVASI DIALISIS ITEM
	// Created By Abu Rizal
	public function get_stock_harga($pasien_id, $item_id, $item_satuan_id)
	{
		$format = "SELECT
						item.nama AS nama,
						simpan_item.jumlah AS jumlah,
						item_satuan.nama AS item_satuan,
						simpan_item.item_id AS item_id,
						simpan_item.item_satuan_id AS item_satuan_id,
						simpan_item.harga_beli AS harga_beli,
						simpan_item.harga_jual AS harga_jual,
						simpan_item.simpan_item_id AS id
					FROM
						simpan_item
					JOIN item ON simpan_item.item_id = item.id
					JOIN item_satuan ON simpan_item.item_satuan_id = item_satuan.id
					WHERE
						pasien_id = ".$pasien_id."
					AND simpan_item.item_id = ".$item_id."
					AND simpan_item.item_satuan_id = ".$item_satuan_id." ";

		return $this->db->query($format);
	}

	public function save_simpan_item($data, $simpan_item_id){

		$this->db->where('simpan_item_id', $simpan_item_id);
		$this->db->update($this->_table, $data);
	}

	public function delete_simpan_item_kosong($simpan_item_id)
	{
		$this->db->where('simpan_item_id', $simpan_item_id);
		$this->db->delete($this->_table);

	}


	function get_id($id)
	{	
		$this->db->where('id', $id);
		$this->db->order_by('id', 'desc');
		$query = $this->db->query('SELECT id, kode FROM item');
		
		return $query->row();
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
		 $sql= "select * from observasi_dialisis_hd where id=?";
		 
		 return $this->db->query($sql,$id);
	}
	function get_data_by_simpan_item_id($id)
	{
		 $sql= "select * from simpan_item where simpan_item_id=?";
		 
		 return $this->db->query($sql,$id);
	}

	function get_max_id()
	{
		 $sql= "select max(simpan_item_id) as max_id from simpan_item;";
		 
		 return $this->db->query($sql);
	}
	
	function get_dialyzer($pasien_id)
	{
		$this->db->select('simpan_item.simpan_item_id as simpan_item_id,simpan_item.kode_dialyzer as kode_dialyzer,simpan_item.item_id as id, simpan_item.`index` as idx, item.nama');
		$this->db->join('item', $this->_table.'.item_id = item.id');
		$this->db->where($this->_table.'.pasien_id', $pasien_id);
		$this->db->where($this->_table.'.status', 1);
		$this->db->where($this->_table.'.status_reuse', 3);

		return $this->db->get($this->_table);
	}

	function get_max_kode($pasien_id){

		

		$format = "SELECT MAX(RIGHT(`kode_dialyzer`,2)) AS max_kode FROM `simpan_item` WHERE pasien_id = $pasien_id;";	
		return $this->db->query($format);
	}


}

/* End of file Item_m.php */
/* Location: ./application/models/master/cabang_m.php */