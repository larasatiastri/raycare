<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Resep_obat_racikan_m extends MY_Model {

	protected $_table        = 'resep_obat_racikan';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		
	);

	

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		'resep_obat_racikan.id'                => 'id',
		'resep_obat_racikan.nama'              => 'nama_resep',
		'resep_obat_racikan.keterangan'        => 'keterangan',
		'resep_obat_racikan.`status`'          => 'status',
		'tindakan_resep_obat_detail.item_id'   => 'item_id',
		'tindakan_resep_obat_detail.tipe_item' => 'tipe_item',
		'tindakan_resep_obat_detail.jumlah'    => 'jumlah',
		'tindakan_resep_obat.pasien_id'        => 'pasien_id',
		'tindakan_resep_obat.dokter_id'        => 'dokter_id',
		'pasien.nama'                          => 'nama_pasien',
		'pasien.url_photo'                     => 'photo_pasien',
		'`user`.nama'                          => 'nama_dokter',
		'`user`.url'                           => 'photo_dokter',
		'`user`.username'                      => 'username',
		'tindakan_resep_obat_detail.satuan_id' => 'satuan_id',
		'item_satuan.nama'                     => 'nama_satuan'
	);

	protected $datatable_columns_komposisi_item = array(
		//column di table  => alias
		'resep_obat_racikan.id'                    => 'id',
		'resep_obat_racikan.nama'                  => 'nama',
		'resep_obat_racikan.keterangan'            => 'keterangan',
		'resep_obat_racikan_detail.id'             => 'resep_obat_racikan_detail_id',
		'resep_obat_racikan_detail.item_id'        => 'item_id',
		'item.kode'                                => 'item_kode',
		'item.nama'                                => 'item_nama',
		'resep_obat_racikan_detail.item_satuan_id' => 'item_satuan_id',
		'item_satuan.nama'                         => 'nama_satuan',
		'resep_obat_racikan_detail.jumlah'         => 'jumlah'
	);

	protected $datatable_columns_komposisi_manual = array(
		//column di table  => alias
		'resep_obat_racikan_detail_manual.id'         => 'id',
		'resep_obat_racikan_detail_manual.keterangan' => 'keterangan'
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($status)
	{		
		$join1 = array('tindakan_resep_obat_detail', $this->_table.'.id = tindakan_resep_obat_detail.item_id');
		$join2 = array('tindakan_resep_obat', 'tindakan_resep_obat_detail.tindakan_resep_obat_id = tindakan_resep_obat.id');
		$join3 = array('pasien', 'tindakan_resep_obat.pasien_id = pasien.id');
		$join4 = array('`user`', 'tindakan_resep_obat.dokter_id = `user`.id');
		$join5 = array('item_satuan', 'tindakan_resep_obat_detail.satuan_id = item_satuan.id');
		$join_tables = array($join1, $join2, $join3, $join4, $join5);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('resep_obat_racikan.`status`', $status);
		$this->db->where('tindakan_resep_obat_detail.tipe_item', 2);

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('resep_obat_racikan.`status`', $status);
		$this->db->where('tindakan_resep_obat_detail.tipe_item', 2);

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('resep_obat_racikan.`status`', $status);
		$this->db->where('tindakan_resep_obat_detail.tipe_item', 2);
		

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

	public function get_datatable_komposisi_item($id)
	{		
		$join1 = array('resep_obat_racikan_detail', $this->_table.'.id = resep_obat_racikan_detail.resep_obat_racikan_id');
		$join2 = array('item', 'item.id = resep_obat_racikan_detail.item_id');
		$join3 = array('item_satuan', 'item_satuan.id = resep_obat_racikan_detail.item_satuan_id');
		$join_tables = array($join1, $join2, $join3);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_komposisi_item);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.id', $id);

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.id', $id);

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.id', $id);

		

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_komposisi_item as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_komposisi_item;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_datatable_komposisi_manual($id)
	{		
		$join = array('resep_obat_racikan_detail_manual', $this->_table.'.id = resep_obat_racikan_detail_manual.resep_obat_racikan_id');
		$join_tables = array($join);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_komposisi_manual);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.id', $id);

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.id', $id);

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.id', $id);

		

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_komposisi_manual as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_komposisi_manual;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_no_batch()
	{
		$format = "SELECT MAX(SUBSTRING(`no_batch`,9,4)) AS max_no_batch FROM `racik_obat` WHERE SUBSTRING(`no_batch`,5,2) = DATE_FORMAT(NOW(), '%y') AND SUBSTRING(`no_batch`, 7, 2) = DATE_FORMAT(NOW(), '%m')";	
		return $this->db->query($format);
	}

	public function total_komposisi($id)
	{
		$format = "SELECT (SELECT COUNT(*) FROM resep_obat_racikan_detail WHERE resep_obat_racikan_detail.resep_obat_racikan_id = $id)+(SELECT COUNT(*) FROM resep_obat_racikan_detail_manual WHERE resep_obat_racikan_detail_manual.resep_obat_racikan_id = $id) AS jumlah_item";	
		return $this->db->query($format);
	}

	public function get_proses_obat_racikan($id){
		$format = "SELECT
					resep_obat_racikan.id AS id,
					resep_obat_racikan.nama AS nama_resep,
					resep_obat_racikan.keterangan AS keterangan,
					resep_obat_racikan.`status` AS `STATUS`,
					tindakan_resep_obat_detail.item_id AS item_id,
					tindakan_resep_obat_detail.tipe_item AS tipe_item,
					tindakan_resep_obat_detail.jumlah AS jumlah,
					tindakan_resep_obat.pasien_id AS pasien_id,
					tindakan_resep_obat.dokter_id AS dokter_id,
					pasien.nama AS nama_pasien,
					pasien.url_photo AS photo_pasien,
					`user`.nama AS nama_dokter,
					`user`.url AS photo_dokter,
					`user`.username AS username,
					tindakan_resep_obat_detail.satuan_id AS satuan_id,
					item_satuan.nama AS nama_satuan,
					tindakan_resep_obat_detail.dosis AS dosis
					FROM
					resep_obat_racikan
					LEFT JOIN tindakan_resep_obat_detail ON resep_obat_racikan.id = tindakan_resep_obat_detail.item_id
					LEFT JOIN tindakan_resep_obat ON tindakan_resep_obat_detail.tindakan_resep_obat_id = tindakan_resep_obat.id
					LEFT JOIN pasien ON tindakan_resep_obat.pasien_id = pasien.id
					LEFT JOIN `user` ON tindakan_resep_obat.dokter_id = `user`.id
					LEFT JOIN item_satuan ON tindakan_resep_obat_detail.satuan_id = item_satuan.id
					WHERE
						resep_obat_racikan.`status` = 1
					AND tindakan_resep_obat_detail.tipe_item = 2
					AND resep_obat_racikan.id = $id
					";
		return $this->db->query($format);
	}

	public function get_komposisi_racikan($resep_obat_racikan_id)
	{
		$date_now = date('Y-m-d');
		$format = "SELECT
					resep_obat_racikan.id AS id,
					resep_obat_racikan.nama AS nama,
					resep_obat_racikan.keterangan AS keterangan,
					resep_obat_racikan_detail.id AS resep_obat_racikan_detail_id,
					resep_obat_racikan_detail.item_id AS item_id,
					item.kode AS item_kode,
					item.nama AS item_nama,
					resep_obat_racikan_detail.item_satuan_id AS item_satuan_id,
					item_satuan.nama AS nama_satuan,
					resep_obat_racikan_detail.jumlah AS jumlah,
					item_harga.harga as harga,
					item_harga.tanggal as tanggal
					FROM
					resep_obat_racikan
					JOIN resep_obat_racikan_detail ON resep_obat_racikan.id = resep_obat_racikan_detail.resep_obat_racikan_id
					JOIN item ON item.id = resep_obat_racikan_detail.item_id
					JOIN item_satuan ON item_satuan.id = resep_obat_racikan_detail.item_satuan_id
					LEFT JOIN item_harga ON item_satuan.id = item_harga.item_satuan_id
					WHERE
					resep_obat_racikan.id = $resep_obat_racikan_id AND
					item_harga.tanggal <= ?
					";
		return $this->db->query($format, $date_now);
	}

	public function get_harga_terbaru($satuan_id)
	{
		$format = "SELECT max(tanggal) as tanggal
					FROM
					item_harga
					WHERE
					item_harga.item_satuan_id = $satuan_id
					";
		return $this->db->query($format);
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

