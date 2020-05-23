<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Klaim_m extends MY_Model {

	protected $_table        = 'pasien';
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
		'd.nama'                 => 'nama', 
		'b.no_kartu'             => 'no_kartu', 
		'c.tipe'                 => 'tipe', 
		'c.created_date'         => 'tggl', 
		'b.penjamin_id'          => 'id', 
		'b.id'                   => 'id2', 
		'b.penjamin_kelompok_id' => 'id_kelompok',
		'd.url' => 'url',
		'e.url'					 => 'url_kelompok' 
		 


		
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
 
	 	 
		$join1 = array('pasien_penjamin b',$this->_table . '.id = b.pasien_id');
		$join2 = array('(select tipe,DATE_FORMAT(pasien_klaim.created_date,"%Y-%m-%d") as created_date,penjamin_id,nama from pasien_klaim join penjamin on pasien_klaim.penjamin_id=penjamin.id where pasien_id="'.$id.'" and DATE_FORMAT(pasien_klaim.created_date,"%Y-%m-%d")=DATE_FORMAT(now(),"%Y-%m-%d") AND penjamin_id != 1 OR
pasien_id = "'.$id.'" AND DATE_FORMAT(pasien_klaim.created_date, "%Y-%m-%d") = DATE_SUB(CONCAT(CURDATE(), "%Y-%m-%d"), INTERVAL 1 DAY) AND penjamin_id != 1 group by tipe,penjamin_id,nama) c','c.penjamin_id = b.penjamin_id','left'); 
		$join3 = array('penjamin d','d.id = b.penjamin_id');
		$join4 = array('penjamin_kelompok e','b.penjamin_kelompok_id = e.id', 'left');
		$join_tables = array($join1,$join2,$join3,$join4);

	

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		 $this->db->where('b.pasien_id',$id);
		 $this->db->where('b.is_active',1);
		 $this->db->group_by('b.pasien_id, b.penjamin_id');
		//  $this->db->group_by(array("DATE_FORMAT(tggl,'%Y-%m-%d'),id")); 
		    
		 
	 
	  
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		 $this->db->where('b.pasien_id',$id);
		  $this->db->where('b.is_active',1);
		 $this->db->group_by('b.pasien_id, b.penjamin_id');
	//  $this->db->group_by(array("DATE_FORMAT(tggl,'%Y-%m-%d'),id")); 
	   
	// 	$this->db->where('pasien_penjamin.pasien_id',$id);
	 //	$this->db->group_by(array("a.nama","pasien_penjamin.no_kartu","pasien_klaim.tipe","pasien_klaim.created_date","pasien_penjamin.id")); 
	 	 
	 	 
	  
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		 $this->db->where('b.pasien_id',$id);
		  $this->db->where('b.is_active',1);
		 $this->db->group_by('b.pasien_id, b.penjamin_id');
  //$this->db->group_by(array("DATE_FORMAT(tggl,'%Y-%m-%d'),id")); 
   
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
 // die(dump($result->records));
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

}

/* End of file Item_m.php */
/* Location: ./application/models/master/cabang_m.php */