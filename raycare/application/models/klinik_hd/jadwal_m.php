<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jadwal_m extends MY_Model {

	protected $_table        = 'jadwal';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'item_harga.id'         => 'id', 
		'item_harga.item_id'    => 'item_id', 
		'item_harga.parent_id'  => 'parent_id', 
		'item_harga.nama'       => 'nama', 
		'item_harga.jumlah'     => 'jumlah',
		'item_harga.is_primary' => 'is_primary',
		
	);

	protected $datatable_columns_item = array(
		//column di table  => alias
		'item.id'                => 'id', 
		'item.item_sub_kategori' => 'item_sub_kategori', 
		'item.kode'              => 'kode', 
		'item.nama'              => 'nama', 
		'item_harga.nama'        => 'unit',
		'item_harga.harga'       => 'harga',
		'item_kategori.nama'     => 'kategori_item',
		'item.keterangan'        => 'keterangan',

		
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($item_id)
	{	

		$join_tables = array();


		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_item);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);


		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_item as $col => $alias)
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

	public function get_data($date, $end_date, $cabang_id)
	{
		$this->db->select(" jadwal.id,
							jadwal.cabang_id,
							jadwal.pasien_id,
							jadwal.no_urut_bed,
							jadwal.tanggal,
							jadwal.tipe,
							jadwal.waktu,
							jadwal.status,
							jadwal.keterangan,
							pasien.nama
						");
		$this->db->join('pasien', $this->_table.'.pasien_id = pasien.id');
		$this->db->where("jadwal.tanggal >= '$date' AND jadwal.tanggal < '$end_date'");
		$this->db->where("jadwal.cabang_id = $cabang_id");
		$this->db->where("jadwal.status = 1");
		$this->db->where("jadwal.is_active = 1");
		$this->db->or_where("jadwal.tanggal >= '$date' AND jadwal.tanggal < '$end_date'");
		$this->db->where("jadwal.cabang_id = $cabang_id");
		$this->db->where("jadwal.status = 0");
		$this->db->where("jadwal.is_active = 1");
		return $this->db->get($this->_table);
	}

	public function get_data_jadwal($date, $end_date, $cabang_id)
	{
		$this->db->select(" jadwal.id,
							jadwal.cabang_id,
							jadwal.pasien_id,
							jadwal.no_urut_bed,
							jadwal.tanggal,
							jadwal.tipe,
							jadwal.waktu,
							jadwal.keterangan,
							pasien.nama
						");
		$this->db->join('pasien', $this->_table.'.pasien_id = pasien.id');
		$this->db->where("date(jadwal.tanggal) >= '$date' AND date(jadwal.tanggal) <= '$end_date'");
		$this->db->where("jadwal.cabang_id = $cabang_id");
		$this->db->where("jadwal.status = 1");
		$this->db->where("jadwal.is_active = 1");
		$this->db->order_by("jadwal.tanggal","ASC");
		$this->db->order_by("jadwal.no_urut_bed","ASC");
		return $this->db->get($this->_table);
	}

	public function get_data_jadwal_cetak($date, $end_date, $cabang_id)
	{
		$this->db->select(" jadwal.id,
							jadwal.cabang_id,
							jadwal.pasien_id,
							jadwal.no_urut_bed,
							jadwal.tanggal,
							jadwal.tipe,
							jadwal.waktu,
							jadwal.keterangan,
							pasien.nama
						");
		$this->db->join('pasien', $this->_table.'.pasien_id = pasien.id');
		$this->db->where("date(jadwal.tanggal) >= '$date' AND date(jadwal.tanggal) <= '$end_date'");
		$this->db->where("jadwal.cabang_id = $cabang_id");
		$this->db->where("jadwal.is_active = 1");
		$this->db->order_by("jadwal.tanggal","ASC");
		$this->db->order_by("jadwal.no_urut_bed","ASC");
		return $this->db->get($this->_table);
	}

	public function get_data_jadwal_waktu($date,$tipe,$cabang_id)
	{
		$this->db->select(" jadwal.id,
							jadwal.cabang_id,
							jadwal.pasien_id,
							jadwal.no_urut_bed,
							jadwal.tanggal,
							jadwal.tipe,
							jadwal.waktu,
							jadwal.keterangan,
						");
		$this->db->where("date(jadwal.tanggal) = '$date'");
		$this->db->where("jadwal.cabang_id = $cabang_id");
		$this->db->where("jadwal.status = 1");
		$this->db->where("jadwal.waktu = $tipe");
		$this->db->where("jadwal.is_active = 1");
		$this->db->group_by("jadwal.pasien_id");
		$this->db->order_by("jadwal.tanggal","ASC");
		$this->db->order_by("jadwal.no_urut_bed","ASC");
		return $this->db->get($this->_table);
	}

	public function get_data_tanggal($date, $cabang_id)
	{
		$this->db->select(" jadwal.id,
							jadwal.cabang_id,
							jadwal.pasien_id,
							jadwal.no_urut_bed,
							jadwal.tanggal,
							jadwal.tipe,
							jadwal.waktu,
							jadwal.keterangan,
							pasien.nama
						");
		$this->db->join('pasien', $this->_table.'.pasien_id = pasien.id');
		$this->db->where("LEFT(jadwal.tanggal, 10) = '$date'");
		$this->db->where("jadwal.cabang_id = $cabang_id");
		$this->db->where("jadwal.status = 1");
		$this->db->where("jadwal.is_active = 1");
		$this->db->group_by("jadwal.pasien_id");
		return $this->db->get($this->_table);
	}

	/**
	 * [fillable_add description]
	 * @return [type] [description]
	 */

	public function check_jadwal2($cabang_id,$date){
		$sql= "select pasien.nama,jadwal.pasien_id,no_urut_bed,tipe,tanggal from jadwal join pasien on jadwal.pasien_id=pasien.id where jadwal.cabang_id=".$cabang_id."  and DATE_FORMAT(tanggal, '%Y-%m-%d')='".$date."' and jadwal.status = 1 and jadwal.is_active = 1";

		return $this->db->query($sql);
	}

	public function get_by_tgl_waktu($date,$time)
	{
		$sql= "select * from jadwal where DATE_FORMAT(tanggal, '%Y-%m-%d')='".$date."' and waktu = '".$time."' and jadwal.status = 1 and jadwal.is_active = 1";

		return $this->db->query($sql);
	}

	public function get_max_bed($date,$time)
	{
		$sql= "SELECT MIN(`nomor`) as `nomor` FROM nomor WHERE `nomor` NOT IN (SELECT no_urut_bed FROM jadwal WHERE DATE_FORMAT(tanggal, '%Y-%m-%d')='".$date."' AND waktu = '".$time."' AND status = 1 AND is_active = 1)";

		return $this->db->query($sql);
	}

	public function get_pasien_tidak_hadir($shift,$cabang_id,$date=NULL)
	{	
		$date = date('Y-m-d');

		if($shift == 1){
			$jam = 7;
		}if($shift == 2){
			$jam = 13;
		}if($shift == 3){
			$jam = 18;
		}if($shift == 4){
			$jam = 23;
		}

		$SQL = "SELECT IF(LENGTH(pasien.no_member)<=11, pasien.no_member, RIGHT(pasien.no_member,7)) AS no_member, pasien.nama, jadwal.keterangan FROM jadwal JOIN pasien ON jadwal.pasien_id = pasien.id
				WHERE date(jadwal.tanggal) = '$date' AND waktu = $jam AND jadwal.is_active = 1 AND jadwal.cabang_id = $cabang_id
				AND jadwal.pasien_id NOT IN (SELECT pasien_id FROM pendaftaran_tindakan_history WHERE date(pendaftaran_tindakan_history.created_date) = '$date' AND pendaftaran_tindakan_history.`status` != 0 AND pendaftaran_tindakan_history.status_verif = 2) GROUP BY jadwal.pasien_id ORDER BY jadwal.tanggal ASC, jadwal.no_urut_bed ASC";

		return $this->db->query($SQL);

	}

	public function get_pasien_tidak_hadir_real($shift,$cabang_id,$date=NULL)
	{	
		if($date != NULL){
			$date = date('Y-m-d', strtotime($date));
		}else{
			$date = date('Y-m-d');

		}

		if($shift == 1){
			$jam = 7;
		}if($shift == 2){
			$jam = 13;
		}if($shift == 3){
			$jam = 18;
		}if($shift == 4){
			$jam = 23;
		}

		$SQL = "SELECT IF(LENGTH(pasien.no_member)<=11, pasien.no_member, RIGHT(pasien.no_member,7)) AS no_member, pasien.nama, jadwal.keterangan, jadwal.id as jadwal_id FROM jadwal JOIN pasien ON jadwal.pasien_id = pasien.id
				WHERE  date(jadwal.tanggal) = '$date' AND waktu = $jam AND jadwal.status = 1 AND jadwal.is_active = 1 AND jadwal.cabang_id = $cabang_id
				AND jadwal.pasien_id NOT IN (SELECT pasien_id FROM pendaftaran_tindakan_history WHERE date(pendaftaran_tindakan_history.created_date) = '$date' AND pendaftaran_tindakan_history.`status` != 0 AND pendaftaran_tindakan_history.status_verif = 2) GROUP BY jadwal.pasien_id ORDER BY jadwal.tanggal ASC, jadwal.no_urut_bed ASC";

		return $this->db->query($SQL);

	}

	public function get_pasien_jadwal($shift,$cabang_id,$date=NULL)
	{	
		$date = date('Y-m-d');

		if($shift == 1){
			$jam = 7;
		}if($shift == 2){
			$jam = 13;
		}if($shift == 3){
			$jam = 18;
		}if($shift == 4){
			$jam = 23;
		}

		$SQL = "SELECT IF(LENGTH(pasien.no_member)<=11, pasien.no_member, RIGHT(pasien.no_member,7)) AS no_member, pasien.nama, jadwal.keterangan FROM jadwal JOIN pasien ON jadwal.pasien_id = pasien.id WHERE date(jadwal.tanggal) = '$date' AND waktu = $jam AND jadwal.is_active = 1 AND jadwal.cabang_id = $cabang_id GROUP BY jadwal.pasien_id ORDER BY jadwal.tanggal ASC, jadwal.no_urut_bed ASC";

		return $this->db->query($SQL);

	}
}

/* End of file Jadwal_m.php */
/* Location: ./application/models/klinik_hd/jadwal_m.php */