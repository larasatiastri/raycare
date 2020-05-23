<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class T_box_paket_detail_m extends MY_Model {

	protected $_table        = 't_box_paket_detail';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(

		't_box_paket.kode_box_paket' => 'kode_box_paket',
		'item.kode' => 'kode',
		'item.nama' => 'nama',
		't_box_paket_detail.jumlah' => 'jumlah',
		't_box_paket_detail.bn_sn_lot' => 'bn_sn_lot',
		't_box_paket_detail.expire_date' => 'expire_date',
		't_box_paket_detail.status' => 'status',
		't_box_paket_detail.tanggal_pakai' => 'tanggal_pakai',
		't_box_paket_detail.id' => 'id',
		't_box_paket_detail.item_id' => 'item_id',
		't_box_paket_detail.item_satuan_id' => 'item_satuan_id',
		'item_satuan.nama' => 'satuan',
		'box_paket.nama'   => 'nama_box',
		
	);

	public function __construct()
	{
		parent::__construct();
	}


	public function get_datatable($t_box_paket_id, $created_date)
	{	

		$join1 = array('t_box_paket', $this->_table.'.t_box_paket_id = t_box_paket.id');
		$join2 = array('box_paket', 't_box_paket.box_paket_id = box_paket.id');
		$join3 = array('item', $this->_table.'.item_id = item.id');
		$join4 = array('item_satuan', $this->_table.'.item_satuan_id = item_satuan.id');
		$join_tables = array($join1, $join2, $join3, $join4);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = $this->_table.'.id';
		$params['sort_dir'] = 'ASC';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('t_box_paket_id', $t_box_paket_id);
		$this->db->where('date(t_box_paket_detail.created_date)', $created_date);

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('t_box_paket_id', $t_box_paket_id);
		$this->db->where('date(t_box_paket_detail.created_date)', $created_date);

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('t_box_paket_id', $t_box_paket_id);
		$this->db->where('date(t_box_paket_detail.created_date)', $created_date);

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

	public function get_datatable_terpakai($tgl_awal, $tgl_akhir)
	{	

		$join1 = array('t_box_paket', $this->_table.'.t_box_paket_id = t_box_paket.id');
		$join2 = array('box_paket', 't_box_paket.box_paket_id = box_paket.id');
		$join3 = array('item', $this->_table.'.item_id = item.id');
		$join4 = array('item_satuan', $this->_table.'.item_satuan_id = item_satuan.id');
		$join_tables = array($join1, $join2, $join3, $join4);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = $this->_table.'.id';
		$params['sort_dir'] = 'ASC';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('date(t_box_paket.tanggal_tindakan) >= ', $tgl_awal);
		$this->db->where('date(t_box_paket.tanggal_tindakan) <= ', $tgl_akhir);
		$this->db->where('t_box_paket.status', 3);

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('date(t_box_paket.tanggal_tindakan) >= ', $tgl_awal);
		$this->db->where('date(t_box_paket.tanggal_tindakan) <= ', $tgl_akhir);
		$this->db->where('t_box_paket.status', 3);

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('date(t_box_paket.tanggal_tindakan) >= ', $tgl_awal);
		$this->db->where('date(t_box_paket.tanggal_tindakan) <= ', $tgl_akhir);
		$this->db->where('t_box_paket.status', 3);

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

	public function get_max_id_box_paket_detail()
	{
		$format = "SELECT MAX(SUBSTRING(`id`,14,4)) AS max_id FROM `t_box_paket_detail` WHERE SUBSTR(`id`,6,7) = DATE_FORMAT(NOW(), '%m-%Y');";	
		return $this->db->query($format);
	}

	public function get_data_detail($tgl_awal, $tgl_akhir, $item_id, $bn){
		$SQL = "SELECT
					t_box_paket.id AS id_t_box_paket,
					t_box_paket.kode_box_paket AS kode_box_paket,
				  	t_box_paket.box_paket_id AS box_paket_id,
					item.kode AS kode,
					item.nama AS nama,
					t_box_paket_detail.jumlah AS jumlah,
					t_box_paket_detail.bn_sn_lot AS bn_sn_lot,
					t_box_paket_detail.expire_date AS expire_date,
					t_box_paket_detail.status AS STATUS,
					t_box_paket_detail.tanggal_pakai AS tanggal_pakai,
					t_box_paket_detail.id AS id,
					t_box_paket_detail.item_id AS item_id,
					t_box_paket_detail.item_satuan_id AS item_satuan_id,
					item_satuan.nama AS satuan,
					box_paket.nama AS nama_box
				FROM
					t_box_paket_detail
				JOIN t_box_paket ON t_box_paket_detail.t_box_paket_id = t_box_paket.id
				JOIN box_paket ON t_box_paket.box_paket_id = box_paket.id
				JOIN item ON t_box_paket_detail.item_id = item.id
				JOIN item_satuan ON t_box_paket_detail.item_satuan_id = item_satuan.id
				WHERE
					date(
						t_box_paket.tanggal_tindakan
					) >= '$tgl_awal'
				AND date(
					t_box_paket.tanggal_tindakan
				) <= '$tgl_akhir'
				AND t_box_paket.status = 3
				AND t_box_paket_detail.item_id = $item_id
				AND t_box_paket_detail.status = 3
				AND t_box_paket_detail.bn_sn_lot = '$bn'
				OR date(
						t_box_paket.tanggal_tindakan
					) >= '$tgl_awal'
				AND date(
					t_box_paket.tanggal_tindakan
				) <= '$tgl_akhir'
				AND t_box_paket.status = 3
				AND t_box_paket_detail.item_id = $item_id
				AND t_box_paket_detail.status IS NULL
				AND t_box_paket_detail.bn_sn_lot = '$bn'
				ORDER BY
					t_box_paket_detail.id ASC";

		return $this->db->query($SQL);
	}

}
