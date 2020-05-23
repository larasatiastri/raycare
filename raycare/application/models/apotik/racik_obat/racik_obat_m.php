<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Racik_obat_m extends MY_Model {

	protected $_table        = 'racik_obat';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		
	);

	

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		'racik_obat.id'                    => 'id',
		'racik_obat.resep_obat_racikan_id' => 'resep_obat_racikan_id',
		'racik_obat.no_batch'              => 'no_batch',
		'racik_obat.nama'                  => 'nama_resep',
		'racik_obat.tanggal_kadaluarsa'    => 'tanggal_kadaluarsa',
		'racik_obat.jumlah_produksi'       => 'jumlah_produksi',
		'racik_obat.satuan_produksi'       => 'satuan_produksi',
		'racik_obat.harga_jual'            => 'harga_jual',
		'racik_obat.created_by'            => 'dibuat_oleh',
		'racik_obat.tipe_resep'			   => 'tipe_resep',
		'racik_obat.satuan_produksi'	   => 'satuan_produksi',	
		'racik_obat.is_active'             => 'is_active',	
	);

	protected $datatable_columns_racik_obat = array(
		'racik_obat.id'                                     => 'id',
		'racik_obat.resep_obat_racikan_id'                  => 'resep_obat_racikan_id',
		'racik_obat.no_batch'                               => 'no_batch',
		'racik_obat.nama'                                   => 'nama_resep',
		'racik_obat.tanggal_kadaluarsa'                     => 'tanggal_kadaluarsa',
		'racik_obat.jumlah_produksi'                        => 'jumlah_produksi',
		'racik_obat.harga_jual'                             => 'harga_jual',
		'tindakan_resep_obat_detail.item_id'                => 'item_id',
		'tindakan_resep_obat_detail.tipe_item'              => 'tipe_item',
		'tindakan_resep_obat_detail.tindakan_resep_obat_id' => 'tindakan_resep_obat_id',
		'tindakan_resep_obat.pasien_id'                     => 'pasien_id',
		'racik_obat.created_by'                             => 'dibuat_oleh',
		'pasien.nama'                                       => 'nama_pasien',
		'pasien.url_photo'                                  => 'photo_pasien',
		'tindakan_resep_obat.dokter_id'                     => 'dokter_id',
		'`user`.nama'                                       => 'nama_dokter', 
		'`user`.username'                                   => 'username', 
		'`user`.url'                                        => 'photo_dokter',
	);

	public function __construct()
	{
		parent::__construct();
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
		// $this->db->group_by($this->_table.'.id');

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		// $this->db->group_by($this->_table.'.id');

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		// $this->db->group_by($this->_table.'.id');
		

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

	public function get_datatable_racik_obat()
	{		
		$join1 = array('tindakan_resep_obat_detail', 'racik_obat.resep_obat_racikan_id = tindakan_resep_obat_detail.item_id', 'left');
		$join2 = array('tindakan_resep_obat', 'tindakan_resep_obat_detail.tindakan_resep_obat_id = tindakan_resep_obat.id', 'left');
		$join3 = array('`user`', 'tindakan_resep_obat.dokter_id = `user`.id', 'left');
		$join4 = array('pasien', 'tindakan_resep_obat.pasien_id = pasien.id', 'left');
		$join_tables = array($join1, $join2, $join3, $join4);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_racik_obat);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		// $this->db->group_by($this->_table.'.id');

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		// $this->db->group_by($this->_table.'.id');

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		// $this->db->group_by($this->_table.'.id');
		

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_racik_obat as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_racik_obat;
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

	public function get_data_racik_obat($id){
		$format = "SELECT
					racik_obat.id AS id,
					racik_obat.resep_obat_racikan_id AS resep_obat_racikan_id,
					racik_obat.no_batch AS no_batch,
					racik_obat.nama AS nama_resep,
					racik_obat.tanggal_kadaluarsa AS tanggal_kadaluarsa,
					racik_obat.jumlah_produksi AS jumlah_produksi,
					racik_obat.harga_jual AS harga_jual,
					racik_obat.biaya_tambahan AS biaya_tambahan,
					tindakan_resep_obat_detail.item_id AS item_id,
					tindakan_resep_obat_detail.tipe_item AS tipe_item,
					tindakan_resep_obat_detail.tindakan_resep_obat_id AS tindakan_resep_obat_id,
					tindakan_resep_obat.pasien_id AS pasien_id,
					racik_obat.created_by AS dibuat_oleh,
					pasien.nama AS nama_pasien,
					pasien.url_photo AS photo_pasien,
					tindakan_resep_obat.dokter_id AS dokter_id,
					`user`.nama AS nama_dokter,
					`user`.username AS username,
					`user`.url AS photo_dokter,
					tindakan_resep_obat_detail.jumlah,
					tindakan_resep_obat_detail.satuan_id,
					tindakan_resep_obat_detail.dosis,
					item_satuan.nama as nama_satuan,
					resep_obat_racikan.keterangan
					FROM
					racik_obat
					LEFT JOIN tindakan_resep_obat_detail ON racik_obat.resep_obat_racikan_id = tindakan_resep_obat_detail.item_id
					LEFT JOIN tindakan_resep_obat ON tindakan_resep_obat_detail.tindakan_resep_obat_id = tindakan_resep_obat.id
					LEFT JOIN `user` ON tindakan_resep_obat.dokter_id = `user`.id
					LEFT JOIN pasien ON tindakan_resep_obat.pasien_id = pasien.id
					LEFT JOIN item_satuan ON tindakan_resep_obat_detail.satuan_id = item_satuan.id
					LEFT JOIN resep_obat_racikan ON racik_obat.resep_obat_racikan_id = resep_obat_racikan.id
					WHERE
					racik_obat.id = $id
					";
		return $this->db->query($format);
	}

	public function get_racik_obat($id){
		$format = "SELECT
					racik_obat.id AS id,
					racik_obat.resep_obat_racikan_id AS resep_obat_racikan_id,
					racik_obat.no_batch AS no_batch,
					racik_obat.nama AS nama_resep,
					racik_obat.tanggal_kadaluarsa AS tanggal_kadaluarsa,
					racik_obat.jumlah_produksi AS jumlah_produksi,
					racik_obat.harga_jual AS harga_jual,
					tindakan_resep_obat_detail.item_id AS item_id,
					tindakan_resep_obat_detail.tipe_item AS tipe_item,
					tindakan_resep_obat_detail.tindakan_resep_obat_id AS tindakan_resep_obat_id,
					tindakan_resep_obat.pasien_id AS pasien_id,
					racik_obat.created_by AS dibuat_oleh,
					pasien.nama AS nama_pasien,
					pasien.url_photo AS photo_pasien,
					tindakan_resep_obat.dokter_id AS dokter_id,
					`user`.nama AS nama_dokter,
					`user`.username AS username,
					`user`.url AS photo_dokter
				FROM
					racik_obat
				LEFT JOIN tindakan_resep_obat_detail ON racik_obat.resep_obat_racikan_id = tindakan_resep_obat_detail.item_id
				LEFT JOIN tindakan_resep_obat ON tindakan_resep_obat_detail.tindakan_resep_obat_id = tindakan_resep_obat.id
				LEFT JOIN `user` ON tindakan_resep_obat.dokter_id = `user`.id
				LEFT JOIN pasien ON tindakan_resep_obat.pasien_id = pasien.id
				WHERE
				racik_obat.id = $id AND racik_obat.tipe_resep = '1' ";

		return $this->db->query($format);
		

	}

	public function get_data_racik_obat_manual($id){
		$format = "SELECT
					tindakan_resep_obat_manual.id AS tindakan_resep_obat_manual_id,
					tindakan_resep_obat_manual.keterangan AS keterangan,
					tindakan_resep_obat_manual.`status` AS status_tindakan,
					tindakan_resep_obat.pasien_id AS pasien_id,
					pasien.nama AS nama_pasien,
					pasien.url_photo AS photo_pasien,
					tindakan_resep_obat.dokter_id AS dokter_id,
					`user`.nama AS nama_dokter,
					`user`.url AS photo_dokter,
					`user`.username AS username,
					racik_obat.id as id,
					racik_obat.resep_obat_racikan_id,
					racik_obat.nama as nama_resep,
					racik_obat.tanggal_kadaluarsa,
					racik_obat.jumlah_produksi as jumlah,
					racik_obat.satuan_produksi as nama_satuan,
					racik_obat.harga_produksi,
					racik_obat.harga_jual,
					racik_obat.biaya_tambahan
					FROM
					tindakan_resep_obat_manual
					JOIN tindakan_resep_obat ON tindakan_resep_obat_manual.tindakan_resep_obat_id = tindakan_resep_obat.id
					JOIN pasien ON tindakan_resep_obat.pasien_id = pasien.id
					JOIN `user` ON tindakan_resep_obat.dokter_id = `user`.id
					LEFT JOIN racik_obat ON tindakan_resep_obat_manual.id = racik_obat.resep_obat_racikan_id
					WHERE
					racik_obat.id = $id";

		return $this->db->query($format);
	}

	public function get_racik_obat_manual($id){
		$format = "SELECT
					tindakan_resep_obat_manual.id AS id,
					tindakan_resep_obat_manual.keterangan AS keterangan,
					tindakan_resep_obat_manual.`status` AS status_tindakan,
					pasien.nama AS nama_pasien,
					pasien.url_photo AS photo_pasien,
					tindakan_resep_obat.dokter_id AS dokter_id,
					`user`.nama AS nama_dokter,
					`user`.url AS photo_dokter,
					`user`.username AS username,
					racik_obat.id as racik_obat_id,
					racik_obat.resep_obat_racikan_id,
					racik_obat.tipe_resep
					FROM
					tindakan_resep_obat_manual
					LEFT JOIN tindakan_resep_obat ON tindakan_resep_obat_manual.tindakan_resep_obat_id = tindakan_resep_obat.id
					LEFT JOIN pasien ON tindakan_resep_obat.pasien_id = pasien.id
					LEFT JOIN `user` ON tindakan_resep_obat.dokter_id = `user`.id
					LEFT JOIN racik_obat ON tindakan_resep_obat_manual.id = racik_obat.resep_obat_racikan_id
					WHERE
					racik_obat.tipe_resep = 2 AND
					racik_obat.id = $id
					";
		return $this->db->query($format);
	}

	public function get_komposisi_racikan($id){
		$format = "SELECT
					racik_obat.id,
					racik_obat_detail.item_id,
					racik_obat_detail.item_satuan_id,
					racik_obat_detail.jumlah,
					item.nama AS item_nama,
					item_satuan.nama AS nama_satuan,
					item.kode AS item_kode,
					racik_obat.biaya_tambahan AS biaya_tambahan,
					item_satuan.jumlah AS jumlah_farmasi,
					racik_obat_detail.harga_beli,
					racik_obat_detail.harga_jual as harga
					FROM
					racik_obat
					LEFT JOIN racik_obat_detail ON racik_obat.id = racik_obat_detail.racik_obat_id
					LEFT JOIN item ON racik_obat_detail.item_id = item.id
					LEFT JOIN item_satuan ON racik_obat_detail.item_satuan_id = item_satuan.id
					WHERE
						racik_obat.id = $id
					GROUP BY item.id";
		return $this->db->query($format);
	}

	public function get_jumlah_farmasi($racik_obat_id, $item_id, $item_satuan_id)
	{
		$format = "SELECT SUM(racik_obat_detail.jumlah) as jumlah_farmasi
					FROM
					racik_obat_detail
					WHERE
					racik_obat_detail.racik_obat_id = $racik_obat_id AND
					racik_obat_detail.item_id = $item_id AND
					racik_obat_detail.item_satuan_id = $item_satuan_id
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

