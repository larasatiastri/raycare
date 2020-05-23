<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Harga_jual_m extends MY_Model {

	protected $_table        = 'item';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(

		//column di table          => alias
		'item.kode'        	=> 'kode', 
		'item.nama' => 'nama', 
		'item_satuan.nama'               => 'satuan', 
		'item_sub_kategori.nama'               => 'sub_kategori', 
		'item_kategori.nama'               => 'kategori',
		'item_satuan.harga' => 'harga',
		'item_satuan.tanggal' => 'tanggal',
		'item.id'      => 'item_id',
		'item_satuan.id'      => 'item_satuan_id',
	);


	public function __construct()
	{
		parent::__construct();
	}


	public function get_datatable($item_kategori_id,$item_sub_kategori_id)
	{	
		
		$join1 = array('item_satuan', 'item_satuan.item_id = item.id');
		$join2 = array('item_sub_kategori', 'item.item_sub_kategori = item_sub_kategori.id', 'left');
		$join3 = array('item_kategori', 'item_sub_kategori.item_kategori_id = item_kategori.id', 'left');

		$join_tables = array($join1, $join2, $join3);

		$wheres = array();
		if($item_kategori_id != 0 && $item_sub_kategori_id == 0){
			$wheres = array(
				'item_sub_kategori.item_kategori_id' => $item_kategori_id
			);
		}if($item_kategori_id != 0 && $item_sub_kategori_id != 0){
			$wheres = array(
				'item.item_sub_kategori' => $item_sub_kategori_id
			);
		}

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($wheres);
		$this->db->where('item.is_active', 1);
		// $this->db->group_by('order_permintaan_pembelian_detail.item_id');
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($wheres);
		$this->db->where('item.is_active', 1);
		// $this->db->group_by('order_permintaan_pembelian_detail.item_id');

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($wheres);
		$this->db->where('item.is_active', 1);

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



	public function get_no_surat_jalan()
	{
		$format = "SELECT MAX(SUBSTRING(`no_surat_jalan`,5,3)) AS max_no_do FROM `pengiriman` WHERE RIGHT(`no_surat_jalan`,4) = DATE_FORMAT(NOW(), '%Y');";	
		return $this->db->query($format);
	}

	public function get_max_id_pengiriman()
	{
		$format = "SELECT MAX(SUBSTRING(`id`,12,4)) AS max_id FROM `pengiriman` WHERE SUBSTR(`id`,4,7) = DATE_FORMAT(NOW(), '%m-%Y');";	
		return $this->db->query($format);
	}

}
