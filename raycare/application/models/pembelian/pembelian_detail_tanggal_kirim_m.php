<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembelian_detail_tanggal_kirim_m extends MY_Model {

	protected $_table        = 'pembelian_detail_tanggal_kirim';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;


	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'pembelian.id' 				=> 'po_id',
		'pembelian.tanggal_pesan' 	=> 'tanggal_pesan',
		'pembelian.no_pembelian' 	=> 'no_pembelian',
		'supplier.id' 				=> 'supplier_id',
		'supplier.kode' 			=> 'supplier_kode',
		'supplier.nama' 			=> 'supplier_nama',
		'pembelian_detail_tanggal_kirim.tanggal_kirim' => 'tanggal_kirim',
		'user.nama' 				=> 'pj_po'
		
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable_datang($jenis_po)
	{	
		$join1 = array('pembelian', $this->_table.'.pembelian_id = pembelian.id', 'left');
		$join2 = array('supplier', 'pembelian.supplier_id = supplier.id', 'left');
		$join3 = array('user', 'pembelian.created_by = user.id', 'left');
		$join_tables = array($join1, $join2, $join3);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		$params['sort_by'] = 'pembelian_detail_tanggal_kirim.tanggal_kirim';
		$params['sort_dir'] = 'asc';
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$status = array('4');

		if($jenis_po == 3){
			$jenis_po = array('3');
		}elseif($jenis_po == 1){
			$jenis_po = array('1','2');
		}
		
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('pembelian.is_active',1);
		$this->db->where('pembelian.status_cancel',0);
		$this->db->where_in('pembelian.jenis_pembelian',$jenis_po);
		$this->db->where_in('pembelian.status', $status);
		$this->db->group_by('pembelian_detail_tanggal_kirim.pembelian_id');
		$this->db->group_by('pembelian_detail_tanggal_kirim.tanggal_kirim');
		// dapatkan total row count;

		$query = $this->db->select('(1)')->get();
		$total_records = $query->num_rows();
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('pembelian.is_active',1);
		$this->db->where('pembelian.status_cancel',0);
		$this->db->where_in('pembelian.jenis_pembelian',$jenis_po);
		$this->db->where_in('pembelian.status', $status);
		$this->db->group_by('pembelian_detail_tanggal_kirim.pembelian_id');
		$this->db->group_by('pembelian_detail_tanggal_kirim.tanggal_kirim');
		// dapatkan total record filtered/search;

		$query = $this->db->select('(1)')->get();
		$total_display_records = $query->num_rows();
		//$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('pembelian.is_active',1);
		$this->db->where('pembelian.status_cancel',0);
		$this->db->where_in('pembelian.jenis_pembelian',$jenis_po);
		$this->db->where_in('pembelian.status', $status);
		$this->db->group_by('pembelian_detail_tanggal_kirim.pembelian_id');
		$this->db->group_by('pembelian_detail_tanggal_kirim.tanggal_kirim');

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
	public function get_datatable()
	{	
		$join_tables = array();

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('is_active',1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('is_active',1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('is_active',1);

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns as $col => $alias)
		{
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

	public function get_max_id_detail_kirim()
	{
		$format = "SELECT MAX(SUBSTRING(`id`,14,4)) AS max_id FROM `pembelian_detail_tanggal_kirim` WHERE SUBSTR(`id`,6,7) = DATE_FORMAT(NOW(), '%m-%Y');";	
		return $this->db->query($format);
	}

	public function get_tanggal_kirim($pembelian_id)
	{
		$this->db->select('tanggal_kirim');
		$this->db->where($this->_table.'.pembelian_id', $pembelian_id);
		$this->db->group_by($this->_table.'.tanggal_kirim');

		return $this->db->get($this->_table)->result_array();
	}

	public function get_tanggal_kirim_detail($pembelian_id, $tanggal)
	{
		$this->db->select('pembelian_detail_tanggal_kirim.*, item.kode as kode_item, item.nama as nama_item, item_satuan.nama as nama_satuan');
		$this->db->join('pembelian_detail', $this->_table.'.pembelian_detail_id = pembelian_detail.id','left');
		$this->db->join('item', 'pembelian_detail.item_id = item.id','left');
		$this->db->join('item_satuan', 'pembelian_detail.item_satuan_id = item_satuan.id','left');
		$this->db->where($this->_table.'.pembelian_id', $pembelian_id);
		$this->db->where('DATE(pembelian_detail_tanggal_kirim.tanggal_kirim)', $tanggal);

		return $this->db->get($this->_table)->result_array();
	}
}

/* End of file pembelian_detail_m.php */
/* Location: ./application/models/pembelian/pembelian_detail_m.php */