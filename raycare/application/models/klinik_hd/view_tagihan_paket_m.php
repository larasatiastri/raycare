<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class View_tagihan_paket_m extends MY_Model {

	protected $_table        = 'paket_item';
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
		'b.nama'        => 'nama', 
		'paket_item.jumlah'        => 'jumlah', 
		'ifnull(c.jumlah2,0)'         => 'digunakan', 
		'ifnull(c.jumlah2,0)'         => 'digunakan', 
		'b.kode'         => 'kode', 
		 
		
);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($transaksiid=null,$paket_id=null)
	{	
 
	 	 
		$join1 = array('item b',$this->_table . '.item_id = b.id');
		$join2 = array('(select item_id,sum(jumlah) as jumlah2 from rm_obat_pasien where tindakan_id="'.$transaksiid.'" and paket_id="'.$paket_id.'" and tipe_tindakan=1 and is_paket=1 group by item_id) c',$this->_table . '.item_id = c.item_id','left');
	  
		$join_tables = array($join1,$join2);

		// $wheres = array(

		// 		'item_harga.cabang_id' => $cabang_id,
		// 		'item_satuan.is_primary' => 1,

		// 	);

		

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		 
		$this->db->where('paket_item.paket_id',$paket_id);
		 
		//$this->db->where('pasien_penjamin.pasien_id',$id);
		//$this->db->group_by(array("a.nama","pasien_penjamin.no_kartu","pasien_klaim.tipe","pasien_klaim.created_date","pasien_penjamin.id")); 
		 
	 
	  
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
	 
		$this->db->where('paket_item.paket_id',$paket_id);
	// 	$this->db->where('pasien_penjamin.pasien_id',$id);
	 //	$this->db->group_by(array("a.nama","pasien_penjamin.no_kartu","pasien_klaim.tipe","pasien_klaim.created_date","pasien_penjamin.id")); 
	 	 
	 	 
	  
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		 
		$this->db->where('paket_item.paket_id',$paket_id);
		//$this->db->where('pasien_penjamin.pasien_id',$id);
		//$this->db->group_by(array("a.nama","pasien_penjamin.no_kartu","pasien_klaim.tipe","pasien_klaim.created_date","pasien_penjamin.id")); 
		 
		 
	  

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
  //die(dump($result->records));
		return $result; 
	}

	public function get_datatable2($transaksiid=null,$paket_id=null,$transaksihdpaketid=null)
	{	
 
	 	 
		$join1 = array('item b',$this->_table . '.item_id = b.id');
		$join2 = array('(select item_id,sum(jumlah) as jumlah2 from tindakan_hd_item where tindakan_hd_id="'.$transaksiid.'" and transaksi_pemberian_id="'.$transaksihdpaketid.'" and tipe_pemberian="1" and is_active=1 group by item_id) c',$this->_table . '.item_id = c.item_id','left');
	  
		$join_tables = array($join1,$join2);

		// $wheres = array(

		// 		'item_harga.cabang_id' => $cabang_id,
		// 		'item_satuan.is_primary' => 1,

		// 	);

		

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		 
		$this->db->where('paket_item.paket_id',$paket_id);
		 
		//$this->db->where('pasien_penjamin.pasien_id',$id);
		//$this->db->group_by(array("a.nama","pasien_penjamin.no_kartu","pasien_klaim.tipe","pasien_klaim.created_date","pasien_penjamin.id")); 
		 
	 
	  
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
	 
		$this->db->where('paket_item.paket_id',$paket_id);
	// 	$this->db->where('pasien_penjamin.pasien_id',$id);
	 //	$this->db->group_by(array("a.nama","pasien_penjamin.no_kartu","pasien_klaim.tipe","pasien_klaim.created_date","pasien_penjamin.id")); 
	 	 
	 	 
	  
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		 
		$this->db->where('paket_item.paket_id',$paket_id);
		//$this->db->where('pasien_penjamin.pasien_id',$id);
		//$this->db->group_by(array("a.nama","pasien_penjamin.no_kartu","pasien_klaim.tipe","pasien_klaim.created_date","pasien_penjamin.id")); 
		 
		 
	  

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
  //die(dump($result->records));
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
 
	function get_paket_item($transaksiid=null,$paket_id=null,$tindakanhdpaketid=null)
	{
		 $sql= "select e.nama as nama_satuan,a.item_satuan_id,b.id,b.nama,b.is_identitas,a.jumlah,ifnull(c.jumlah2,0) as digunakan,b.kode from paket_item a join item b on a.item_id=b.id join (select item_id,sum(jumlah) as jumlah2 from tindakan_hd_item where tindakan_hd_id=".$transaksiid." and transaksi_pemberian_id=".$tindakanhdpaketid."  and tipe_pemberian=1 and is_active=1 group by item_id) c on c.item_id=a.item_id join item_satuan e on e.id=a.item_satuan_id where a.paket_id=".$paket_id;
		 
		 return $this->db->query($sql);
	}

	function get_paket_item2($transaksiid=null,$id=null,$tindakanhdpaketid=null)
	{
		 $sql= "select f.nama as nama_satuan,d.item_satuan_id,b.id,b.nama,d.jumlah,b.is_identitas,ifnull(c.jumlah2,0) as digunakan,b.kode from paket_batch a join paket_batch_item d on d.paket_batch_id=a.id join item b on d.item_id=b.id join (select item_id,sum(jumlah) as jumlah2 from tindakan_hd_item where tindakan_hd_id=".$transaksiid." and transaksi_pemberian_id=".$tindakanhdpaketid." and tipe_pemberian=1 and is_active=1 group by item_id) c on c.item_id=d.item_id join item_satuan f on f.id=d.item_satuan_id where a.id=".$id;
		 
		 return $this->db->query($sql);
	}

	function get_paket_tindakan($transaksiid=null,$paket_id=null,$tindakanhdpaketid=null)
	{
		 $sql= "select b.id,b.nama,a.jumlah,ifnull(c.jumlah2,0) as digunakan,b.kode from paket_tindakan a join tindakan b on a.tindakan_id=b.id join (select tindakan_id,sum(jumlah) as jumlah2 from tindakan_hd_tindakan where tindakan_hd_id=".$transaksiid." and tindakan_hd_paket_id=".$tindakanhdpaketid."  and is_active=1 group by tindakan_id) c on c.tindakan_id=a.tindakan_id where a.paket_id=".$paket_id;
		 
		 return $this->db->query($sql);
	}

	function get_paket_tindakan2($transaksiid=null,$id=null,$tindakanhdpaketid=null)
	{
		 $sql= "select b.id,b.nama,d.jumlah,ifnull(c.jumlah2,0) as digunakan,b.kode from paket_batch a join paket_batch_tindakan d on d.paket_batch_id=a.id join tindakan b on d.tindakan_id=b.id join (select tindakan_id,sum(jumlah) as jumlah2 from tindakan_hd_tindakan where tindakan_hd_id=".$transaksiid." and tindakan_hd_paket_id=".$tindakanhdpaketid."  and is_active=1 group by tindakan_id) c on c.tindakan_id=d.tindakan_id where a.id=".$id;
		 
		 return $this->db->query($sql);
	}

}

/* End of file Item_m.php */
/* Location: ./application/models/master/cabang_m.php */