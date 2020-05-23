<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tindakan_resep_obat_detail_identitas_m extends MY_Model {

	protected $_table        = 'tindakan_resep_obat_detail_identitas';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		'tindakan_id',
		'tipe_tindakan',
		'tindakan_resep_obat_id', 
		'item_id', 
		'tipe_item', 
		'jumlah', 		
		'satuan_id', 		
		'dosis', 		
	);

	private $_fillable_edit = array(
		'tindakan_id',
		'tipe_tindakan',
		'tindakan_resep_obat_id', 
		'item_id', 
		'tipe_item', 
		'jumlah', 		
		'satuan_id', 		
		'dosis', 	
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'pasien.nama'                                      => 'nama_pasien',
		'item.nama'                                        => 'nama',
		'item_satuan.nama'                                 => 'satuan',
		'tindakan_resep_obat_detail_identitas.bn_sn_lot'   => 'bn_sn_lot',
		'tindakan_resep_obat_detail_identitas.jumlah'      => 'jumlah',
		'tindakan_resep_obat_detail_identitas.expire_date' => 'expire_date',
		'user.nama'                                        => 'nama_user',
		'item.kode'                                        => 'kode',
		'item.id'                                          => 'item_id',
		'tindakan_resep_obat_detail_identitas.id'          => 'id'	 
	);

	protected $datatable_columns_item = array(
		//column di table  => alias
		'item.id'                                          =>  'item_id',
		'item.kode'                                        =>  'kode',
		'item.nama'                                        =>  'nama',
		'item_satuan.nama'                                 =>  'satuan',
		'tindakan_resep_obat_detail_identitas.jumlah'      =>  'jumlah',
		'tindakan_resep_obat_detail_identitas.bn_sn_lot'   =>  'bn_sn_lot',
		'tindakan_resep_obat_detail_identitas.expire_date' =>  'expire_date',
		'tindakan_resep_obat_detail_identitas.id'          =>  'id', 
		'user.nama'          =>  'nama_user'	 
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($tindakan_hd_id, $tipe_tindakan)
	{	
		$date = date('Y-m-d');
		$join1 = array('item', $this->_table.'.item_id = item.id');
		$join2 = array('item_satuan', $this->_table.'.item_satuan_id = item_satuan.id');
		$join3 = array('tindakan_resep_obat', $this->_table.'.tindakan_resep_obat_id = tindakan_resep_obat.id');
		$join4 = array('tindakan_resep_obat_detail', $this->_table.'.tindakan_resep_obat_detail_id = tindakan_resep_obat_detail.id');
		$join5 = array('user', 'tindakan_resep_obat.created_by = user.id');
		$join_tables = array($join1, $join2, $join3,$join4,$join5);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_item);
		$params['sort_by'] = 'item.nama';
		$params['sort_dir'] = 'asc';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('tindakan_resep_obat.tindakan_id', $tindakan_hd_id);
		$this->db->where('tindakan_resep_obat.tipe_tindakan', $tipe_tindakan);
		$this->db->where('date(tindakan_resep_obat.created_date)', $date);
		$this->db->where('tindakan_resep_obat_detail.bawa_pulang', 0);
		$this->db->where($this->_table.'.status', 1);
	 
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('tindakan_resep_obat.tindakan_id', $tindakan_hd_id);
		$this->db->where('tindakan_resep_obat.tipe_tindakan', $tipe_tindakan);
		$this->db->where('date(tindakan_resep_obat.created_date)', $date);
		$this->db->where('tindakan_resep_obat_detail.bawa_pulang', 0);
		$this->db->where($this->_table.'.status', 1);


		// $this->db->where($wheres);
		 
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('tindakan_resep_obat.tindakan_id', $tindakan_hd_id);
		$this->db->where('tindakan_resep_obat.tipe_tindakan', $tipe_tindakan);
		$this->db->where('date(tindakan_resep_obat.created_date)', $date);
		$this->db->where('tindakan_resep_obat_detail.bawa_pulang', 0);
		$this->db->where($this->_table.'.status', 1);
		

		// $this->db->where($wheres);
	 

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

	
	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable_batal()
	{	
		$join1 = array('item', $this->_table.'.item_id = item.id');
		$join2 = array('item_satuan', $this->_table.'.item_satuan_id = item_satuan.id');
		$join3 = array('tindakan_resep_obat', $this->_table.'.tindakan_resep_obat_id = tindakan_resep_obat.id');
		$join4 = array('pasien', 'tindakan_resep_obat.pasien_id = pasien.id');
		$join5 = array('user', $this->_table.'.canceled_by = user.id','left');
		$join_tables = array($join1, $join2, $join3, $join4, $join5);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = 'item.nama';
		$params['sort_dir'] = 'asc';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		
		$this->db->where($this->_table.'.status', 3);
	 
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		
		$this->db->where($this->_table.'.status', 3);


		// $this->db->where($wheres);
		 
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		
		$this->db->where($this->_table.'.status', 3);
		

		// $this->db->where($wheres);
	 

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

	public function getsatuanobat($id)
	{
		$sql= "select * from item_satuan where item_id=?";

		return $this->db->query($sql,$id);
	}

	public function get_data_item($id)
	{
		$this->db->select("tindakan_resep_obat_detail_identitas.id AS id, tindakan_resep_obat_detail_identitas.tindakan_resep_obat_id AS tindakan_resep_obat_id, item.id AS item_id, item.nama AS item_nama, item.kode AS item_kode, item.is_identitas AS is_identitas, item_satuan.id AS satuan_id, item_satuan.nama AS satuan, (select harga FROM item_harga WHERE item_harga.item_id = tindakan_resep_obat_detail_identitas.item_id AND item_harga.item_satuan_id = tindakan_resep_obat_detail_identitas.item_satuan_id AND date(item_harga.tanggal) <= DATE_FORMAT(NOW(),'%Y-%m-%d') ORDER BY tanggal DESC LIMIT 1) as harga, tindakan_resep_obat_detail_identitas.jumlah AS jumlah,tindakan_resep_obat_detail_identitas.bn_sn_lot AS bn_sn_lot, tindakan_resep_obat_detail_identitas.expire_date AS expire_date, tindakan_resep_obat_detail_identitas.status AS status");
		$this->db->join('item',$this->_table.'.item_id = item.id', 'left');
		$this->db->join('item_satuan',$this->_table.'.item_satuan_id = item_satuan.id', 'left');
		$this->db->where($this->_table.'.tindakan_resep_obat_id', $id);


		return $this->db->get($this->_table);
	}

	public function get_data_item_detail($detail_id)
	{
		$this->db->select("tindakan_resep_obat_detail_identitas.id AS id, tindakan_resep_obat_detail_identitas.tindakan_resep_obat_id AS tindakan_resep_obat_id, item.id AS item_id, item.nama AS item_nama, item.kode AS item_kode, item.is_identitas AS is_identitas, item_satuan.id AS satuan_id, item_satuan.nama AS satuan, (select harga FROM item_harga WHERE item_harga.item_id = tindakan_resep_obat_detail_identitas.item_id AND item_harga.item_satuan_id = tindakan_resep_obat_detail_identitas.item_satuan_id AND date(item_harga.tanggal) <= DATE_FORMAT(NOW(),'%Y-%m-%d') ORDER BY tanggal DESC LIMIT 1) as harga,tindakan_resep_obat_detail_identitas.jumlah AS jumlah,tindakan_resep_obat_detail_identitas.bn_sn_lot AS bn_sn_lot, tindakan_resep_obat_detail_identitas.expire_date AS expire_date, tindakan_resep_obat_detail_identitas.status AS status");
		$this->db->join('item',$this->_table.'.item_id = item.id', 'left');
		$this->db->join('item_satuan',$this->_table.'.item_satuan_id = item_satuan.id', 'left');
		$this->db->where($this->_table.'.tindakan_resep_obat_detail_id', $detail_id);


		return $this->db->get($this->_table);
	}

	public function get_data_item_detail_manual($detail_id)
	{
		$this->db->select("item.kode as item_kode, item.nama as item_nama, item_satuan.nama as satuan, tindakan_resep_obat_detail_identitas.bn_sn_lot, tindakan_resep_obat_detail_identitas.expire_date, tindakan_resep_obat_detail_identitas.jumlah, (select harga FROM item_harga WHERE item_harga.item_id = tindakan_resep_obat_detail_identitas.item_id AND item_harga.item_satuan_id = tindakan_resep_obat_detail_identitas.item_satuan_id AND date(item_harga.tanggal) <= DATE_FORMAT(NOW(),'%Y-%m-%d') ORDER BY tanggal DESC LIMIT 1) as harga, tindakan_resep_obat_detail_identitas.`status` as status"); 
		$this->db->join('tindakan_resep_obat_detail',$this->_table.'.tindakan_resep_obat_detail_id = tindakan_resep_obat_detail.id', 'left');
		$this->db->join('item',$this->_table.'.item_id = item.id', 'left');
		$this->db->join('item_satuan',$this->_table.'.item_satuan_id = item_satuan.id', 'left');
		$this->db->where('tindakan_resep_obat_detail.tindakan_resep_obat_manual_id', $detail_id);


		return $this->db->get($this->_table);
	}

	public function get_current_data($tindakan_hd_id)
	{
		$date = date('Y-m-d');
		
		$SQL = "SELECT * FROM tindakan_resep_obat_detail_identitas JOIN tindakan_resep_obat_detail ON tindakan_resep_obat_detail_identitas.tindakan_resep_obat_detail_id = tindakan_resep_obat_detail.id JOIN tindakan_resep_obat ON tindakan_resep_obat_detail.tindakan_resep_obat_id = tindakan_resep_obat.id WHERE tindakan_resep_obat.tindakan_id = $tindakan_hd_id AND tindakan_resep_obat_detail_identitas.status = 1 AND date(tindakan_resep_obat.created_date) = '$date'";
		
		return $this->db->query($SQL);
	}
}

/* End of file Item_m.php */
/* Location: ./application/models/master/cabang_m.php */