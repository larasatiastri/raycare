<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cabang_alamat_m extends MY_Model {

	protected $_table        = 'cabang_alamat';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		
		'item.kode'                              => 'item_kode',
		'item.nama'                              => 'item_nama',
		'SUM(inventory.jumlah)'                  => 'stock',
		'pembelian_detail.jumlah_belum_diterima' => 'belum_diterima',
		'item_satuan.nama'                       => 'item_satuan',
		'item_satuan.id'                         => 'item_satuan_id',
		'item.id'                                => 'item_id',
	);

	public function __construct()
	{
		parent::__construct();
	}


	public function get_datatable()
	{	

		$join1 = array('item', $this->_table.'.item_id = item.id', 'right');
		$join2 = array('item_satuan', $this->_table.'.item_satuan_id = item_satuan.id', 'left');
		$join3 = array('pembelian_detail', $this->_table.'.item_id = pembelian_detail.item_id AND pembelian_detail.item_satuan_id = inventory.item_satuan_id', 'left');
		$join_tables = array($join1, $join2, $join3);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('item_satuan.is_primary', 1);
		$this->db->group_by('inventory.item_id');
		$this->db->group_by('inventory.item_satuan_id');

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('item_satuan.is_primary', 1);
		$this->db->group_by('inventory.item_id');
		$this->db->group_by('inventory.item_satuan_id');
		
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('item_satuan.is_primary', 1);
		$this->db->group_by('inventory.item_id');
		$this->db->group_by('inventory.item_satuan_id');

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

	public function get_alamat($cabang_id){

		$this->db->select('cabang_alamat.id AS id_alamat, cabang_alamat.subjek_id AS subjek, cabang_alamat.alamat AS alamat, cabang_alamat.rt_rw AS rt_rw, cabang_alamat.kode_pos AS kode_pos, 
							cabang_alamat.kode_lokasi AS kode_lokasi, inf_lokasi.lokasi_nama AS lokasi_nama, inf_lokasi.nama_propinsi AS nama_propinsi, 
							inf_lokasi.nama_kabupatenkota AS nama_kabupatenkota, inf_lokasi.nama_kecamatan AS nama_kecamatan, inf_lokasi.nama_kelurahan AS nama_kelurahan');
		
		$this->db->join('inf_lokasi', $this->_table.'.kode_lokasi = inf_lokasi.lokasi_kode');

		$this->db->where('cabang_id', $cabang_id);
		$this->db->where('is_primary', 1);
		$this->db->where('is_active', 1);

		return $this->db->get($this->_table)->result_array();
	}

}
