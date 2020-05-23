<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tindakan_hd_m extends MY_Model {

	protected $_table        = 'tindakan_hd';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
			
	);

	private $_fillable_edit = array(
		
	);

	protected $datatable_columns = array(
		//column di table  => alias
		'tindakan_hd.no_transaksi'      => 'no_transaksi', 
		'pasien.nama'                   => 'nama', 
		'pasien.tempat_lahir'           => 'tempat_lahir', 
		'pasien.tanggal_lahir'          => 'tanggal_lahir',
		'f.alamat'                      => 'alamat',
		'inf_lokasi.nama_kecamatan'     => 'kecamatan',
		'inf_lokasi.nama_kelurahan'     => 'kelurahan',
		'inf_lokasi.nama_kabupatenkota' => 'kota',
		'tindakan_hd.status'            => 'status',
		'user.nama'						=> 'nama_dokter',
		'tindakan_hd.id'                => 'id',
		'pasien.id'                     => 'pasienid', 
		'pasien.url_photo'              => 'url_photo', 
		'pasien.no_member'              => 'no_member', 
		'tindakan_hd.tanggal'           => 'tanggal', 
		'tindakan_hd.shift'           => 'shift', 
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
 
		$join1 = array('pasien', $this->_table . '.pasien_id = pasien.id');
		$join2 = array('user',  $this->_table .'.dokter_id = user.id');
		$join3 = array('(select alamat,pasien_id,kode_lokasi from pasien_alamat WHERE is_primary =  1) f', 'f.pasien_id = pasien.id','left');
		$join4 = array('inf_lokasi','f.kode_lokasi = inf_lokasi.lokasi_kode','left');
		  
		$join_tables = array($join1,$join2,$join3,$join4);
		//$join_tables = array();

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = 'tindakan_hd.id';
		$params['sort_dir'] = 'desc';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		 
		// $this->db->where('dokter_id',$this->session->userdata('user_id'));
		$this->db->where('tindakan_hd.cabang_id',$this->session->userdata('cabang_id'));
		 $this->db->where_in('tindakan_hd.status',$id);
		 $this->db->where('tindakan_hd.is_active',1);
		 $this->db->where('date(tindakan_hd.created_date)',date('Y-m-d'));
		 
		 
		 
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		// $this->db->where('dokter_id',$this->session->userdata('user_id'));
		 $this->db->where('tindakan_hd.cabang_id',$this->session->userdata('cabang_id'));
		$this->db->where_in('tindakan_hd.status',$id);
		$this->db->where('tindakan_hd.is_active',1);
		$this->db->where('date(tindakan_hd.created_date)',date('Y-m-d'));
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		// $this->db->where('dokter_id',$this->session->userdata('user_id'));
		 $this->db->where('tindakan_hd.cabang_id',$this->session->userdata('cabang_id'));
		$this->db->where_in('tindakan_hd.status',$id);
		$this->db->where('tindakan_hd.is_active',1);
		$this->db->where('date(tindakan_hd.created_date)',date('Y-m-d'));
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

	function get_data_id($id_bed, $shift)
	{	
		// $format = "SELECT * FROM tindakan_hd WHERE bed_id = '".$id_bed."' AND LEFT(tanggal, 10) = '".date('Y-m-d')."' ";	
		$format = "SELECT tindakan_hd.*, pasien.id as id_pasien, pasien.nama as nama_pasien, tindakan_hd_penaksiran.waktu, tindakan_hd_penaksiran.time_of_dialysis FROM tindakan_hd JOIN pasien ON tindakan_hd.pasien_id = pasien.id JOIN tindakan_hd_penaksiran ON tindakan_hd.id = tindakan_hd_penaksiran.tindakan_hd_id WHERE bed_id = '".$id_bed."' AND tindakan_hd.status = 1 AND tindakan_hd.shift = '".$shift."' ORDER BY tindakan_hd.tanggal DESC";	
		return $this->db->query($format)->row(0);
	}

	function get_bed_pasien_isi($id_bed,$shift)
	{	
		// $format = "SELECT * FROM tindakan_hd WHERE bed_id = '".$id_bed."' AND LEFT(tanggal, 10) = '".date('Y-m-d')."' ";	
		$format = "SELECT tindakan_hd.*, pasien.id as id_pasien, pasien.nama as nama_pasien, tindakan_hd_penaksiran.waktu, tindakan_hd_penaksiran.time_of_dialysis FROM tindakan_hd JOIN pasien ON tindakan_hd.pasien_id = pasien.id JOIN tindakan_hd_penaksiran ON tindakan_hd.id = tindakan_hd_penaksiran.tindakan_hd_id WHERE bed_id = '".$id_bed."' AND tindakan_hd.status = 2 AND tindakan_hd.shift = '".$shift."' ORDER BY tindakan_hd.tanggal DESC ";	
		return $this->db->query($format)->row(0);
	}
	function get_unprocessed_id($id_tindakan)
	{	
		// $format = "SELECT * FROM tindakan_hd WHERE bed_id = '".$id_bed."' AND LEFT(tanggal, 10) = '".date('Y-m-d')."' ";	
		$format = "SELECT tindakan_hd.*, pasien.id as id_pasien, pasien.nama as nama_pasien, bed.kode as kode_bed FROM tindakan_hd JOIN pasien ON tindakan_hd.pasien_id = pasien.id JOIN bed ON tindakan_hd.bed_id = bed.id WHERE tindakan_hd.status = 1 AND tindakan_hd.id = '".$id_tindakan."' ORDER BY tindakan_hd.tanggal DESC";	
		return $this->db->query($format)->row(0);
	}
	function get_unprocessed($shift, $id_tindakan)
	{	
		// $format = "SELECT * FROM tindakan_hd WHERE bed_id = '".$id_bed."' AND LEFT(tanggal, 10) = '".date('Y-m-d')."' ";	
		$format = "SELECT tindakan_hd.*, pasien.id as id_pasien, pasien.nama as nama_pasien, bed.kode as kode_bed FROM tindakan_hd JOIN pasien ON tindakan_hd.pasien_id = pasien.id JOIN bed ON tindakan_hd.bed_id = bed.id WHERE tindakan_hd.status = 1 AND tindakan_hd.shift = '".$shift."' AND tindakan_hd.id != '".$id_tindakan."' ORDER BY tindakan_hd.tanggal DESC";	
		return $this->db->query($format)->result_array();
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

	public function get_nomor_tindakan($cabang)
	{
		$format = "SELECT MAX(SUBSTRING(`no_transaksi`,17,4)) AS max_nomor_po FROM `tindakan_hd` WHERE SUBSTRING(`no_transaksi`,12,4) = DATE_FORMAT(NOW(), '%y%m') and SUBSTRING(`no_transaksi`,3,8)= '".$cabang."'";	
		return $this->db->query($format);
	}

	public function detail_histori_kiri($id,$pasien_id)
	{
		// $format = "select * from tindakan_hd a join pasien b on b.id=a.pasien_id join (select tanggal,pasien_id from tindakan_hd_penaksiran  group by tanggal desc limit 1) c on c.pasien_id=a.pasien_id where a.id=".$id;
		$format = "SELECT b.url_photo, a.no_transaksi, a.tanggal, b.no_member, b.nama, (SELECT tanggal from tindakan_hd WHERE pasien_id = $pasien_id AND status = 3 ORDER BY tanggal DESC LIMIT 1) as tanggal_terakhir, ifnull(a.keterangan, '-') AS keterangan, (SELECT id from tindakan_hd WHERE pasien_id = $pasien_id ORDER BY tanggal DESC LIMIT 1) as tindakan_id FROM tindakan_hd a LEFT JOIN pasien b ON b.id = a.pasien_id WHERE a.pasien_id = $pasien_id AND a.id = $id";

		return $this->db->query($format);
	}
	public function detail_histori_kanan($id)
	{
		$format = "select b.nama,ifnull(a.berat_awal,'-') as berat_awal,ifnull(a.berat_akhir,'-') as berat_akhir,ifnull(f.nama,'-') as nama_poli,ifnull(d.kode,'-') as kode,ifnull(e.nama,'-') as nama_penjamin from tindakan_hd a left join user b on b.id=a.dokter_id left join  rujukan c on c.tindakan_id=a.id left join bed d on d.id=a.bed_id left join penjamin e on e.id=a.penjamin_id left join poliklinik f on f.id=c.poliklinik_asal_id where a.id=?";
		return $this->db->query($format,$id);
	}

	function get_by_transaction_id($transaction_id=null,$pasien_id,$tanggal=null,$flag)
	{
		$where='';
		if($flag==1){
			$where=" and tanggal='".$tanggal."'";
			$sql= "select * from tindakan_hd_penaksiran where tindakan_hd_id=".$transaction_id." and pasien_id=".$pasien_id.$where;
		}else if($flag==2){
			$sql= "select * from tindakan_hd_penaksiran  f join rm_tindakan_pasien g on f.tindakan_hd_id=g.tindakan_id where  f.pasien_id=".$pasien_id." and tindakan_hd_id in (select b.id from rm_tindakan_pasien a join tindakan_hd b on a.tindakan_id=b.id where a.pasien_id=".$pasien_id.") order by f.tanggal asc,g.tipe asc,g.tipe asc limit 1";
		}else{

		}	

		return $this->db->query($sql);
	}

	function get_by_transaction_id2($transaction_id=null)
	{
		$sql= "select * from tindakan_hd_penaksiran where tindakan_hd_id=".$transaction_id;
		return $this->db->query($sql);		 
	}

	function getpageprev($pasien_id,$tanggal=null)
	{ 
		$sql= "select * from tindakan_hd_penaksiran f join rm_tindakan_pasien g on f.tindakan_hd_id=g.tindakan_id where   f.pasien_id=".$pasien_id." and tindakan_hd_id in (select b.id from rm_tindakan_pasien a join tindakan_hd b on a.tindakan_id=b.id where a.pasien_id=".$pasien_id.") and f.tanggal < '".$tanggal."' order by f.tanggal desc,g.tipe asc limit 1";
		return $this->db->query($sql);
	}

	function getpagenext($pasien_id,$tanggal=null)
	{
		 
		$sql= "select * from tindakan_hd_penaksiran f join rm_tindakan_pasien g on f.tindakan_hd_id=g.tindakan_id  where  f.pasien_id=".$pasien_id." and tindakan_hd_id in (select b.id from rm_tindakan_pasien a join tindakan_hd b on a.tindakan_id=b.id where a.pasien_id=".$pasien_id.") and f.tanggal > '".$tanggal."' order by f.tanggal asc,g.tipe asc limit 1";
		return $this->db->query($sql);
	}

	function getpagefirst($pasien_id,$tanggal=null)
	{
		 
		$sql= "select * from tindakan_hd_penaksiran f join rm_tindakan_pasien g on f.tindakan_hd_id=g.tindakan_id  where  f.pasien_id=".$pasien_id." and tindakan_hd_id in (select b.id from rm_tindakan_pasien a join tindakan_hd b on a.tindakan_id=b.id where a.pasien_id=".$pasien_id.")  order by f.tanggal asc,g.tipe asc limit 1";
		return $this->db->query($sql);
	}

	function getpagelast($pasien_id,$tanggal=null)
	{ 
		$sql= "select * from tindakan_hd_penaksiran f join rm_tindakan_pasien g on f.tindakan_hd_id=g.tindakan_id where  f.pasien_id=".$pasien_id." and tindakan_hd_id in (select b.id from rm_tindakan_pasien a join tindakan_hd b on a.tindakan_id=b.id where a.pasien_id=".$pasien_id.")  order by f.tanggal desc,g.tipe asc limit 1";
		return $this->db->query($sql);
	}

	function getproblem($tindakan_id,$pasien_id)
	{
		 
		$sql= "select * from pasien_problem a where a.tindakan_hd_id=".$tindakan_id." and pasien_id=".$pasien_id." order by problem_id asc";
		return $this->db->query($sql);
	}

	function getkomplikasi($tindakan_id,$pasien_id)
	{
		 
		$sql= "select * from pasien_komplikasi a where a.tindakan_hd_id=".$tindakan_id." and pasien_id=".$pasien_id." order by komplikasi_id asc";
		return $this->db->query($sql);
	}

	function get_last_data($pasien_id)
	{
		$this->db->where('pasien_id',$pasien_id);
		$this->db->order_by('created_date', 'desc');
		$this->db->limit(1);

		return $this->db->get($this->_table)->row(0);
	}

	public function get_hemodialisis_history($pasien_id, $tanggal)
	{	
		$tanggal = date('Y-m-d', strtotime($tanggal));
		$pecah_tanggal = explode('-', $tanggal);
		
		$tanggal_awal = $pecah_tanggal[0]."-".$pecah_tanggal[1]."-01";
		$tanggal_akhir = date('Y-m-d', strtotime($tanggal."+1 days"));

		$sql = "SELECT tindakan_hd.id AS id_tindakan, tindakan_hd.tanggal, tindakan_hd.berat_awal, tindakan_hd.berat_akhir, tindakan_hd_penaksiran.dialyzer_new, tindakan_hd_penaksiran.dialyzer_reuse,
								(SELECT observasi_dialisis_hd.tekanan_darah_1 FROM tindakan_hd a JOIN observasi_dialisis_hd ON observasi_dialisis_hd.transaksi_hd_id = a.id WHERE a.pasien_id = ".$pasien_id." AND a.tanggal BETWEEN '".$tanggal_awal."' AND '".$tanggal_akhir."' AND a.id = tindakan_hd.id ORDER BY observasi_dialisis_hd.waktu_pencatatan ASC LIMIT 1) AS td_pre_1,
								(SELECT observasi_dialisis_hd.tekanan_darah_2 FROM tindakan_hd a JOIN observasi_dialisis_hd ON observasi_dialisis_hd.transaksi_hd_id = a.id WHERE a.pasien_id = ".$pasien_id." AND a.tanggal BETWEEN '".$tanggal_awal."' AND '".$tanggal_akhir."' AND a.id = tindakan_hd.id ORDER BY observasi_dialisis_hd.waktu_pencatatan ASC LIMIT 1) AS td_pre_2,
								(SELECT observasi_dialisis_hd.tekanan_darah_1 FROM tindakan_hd a JOIN observasi_dialisis_hd ON observasi_dialisis_hd.transaksi_hd_id = a.id WHERE a.pasien_id = ".$pasien_id." AND a.tanggal BETWEEN '".$tanggal_awal."' AND '".$tanggal_akhir."' AND a.id = tindakan_hd.id ORDER BY observasi_dialisis_hd.waktu_pencatatan DESC LIMIT 1) AS td_post_1,
								(SELECT observasi_dialisis_hd.tekanan_darah_2 FROM tindakan_hd a JOIN observasi_dialisis_hd ON observasi_dialisis_hd.transaksi_hd_id = a.id WHERE a.pasien_id = ".$pasien_id." AND a.tanggal BETWEEN '".$tanggal_awal."' AND '".$tanggal_akhir."' AND a.id = tindakan_hd.id ORDER BY observasi_dialisis_hd.waktu_pencatatan DESC LIMIT 1) AS td_post_2
				FROM tindakan_hd
				LEFT JOIN tindakan_hd_penaksiran ON tindakan_hd_penaksiran.tindakan_hd_id = tindakan_hd.id
				LEFT JOIN observasi_dialisis_hd ON observasi_dialisis_hd.transaksi_hd_id = tindakan_hd.id
				WHERE tindakan_hd.pasien_id = ".$pasien_id." 
				AND tindakan_hd.tanggal BETWEEN '".$tanggal_awal."' AND '".$tanggal_akhir."'
				GROUP BY tindakan_hd.id";

		return $this->db->query($sql);
	}

	public function get_data_hd_pasien($current_tindakan, $pasien_id, $flag)
	{
		$format = "";

		if ($flag == "prev") {
			$format = "SELECT
					tindakan_hd.id,
					tindakan_hd.pasien_id
					FROM `tindakan_hd`
					WHERE
					tindakan_hd.id < $current_tindakan AND
					tindakan_hd.pasien_id = $pasien_id
					ORDER BY
					tindakan_hd.id DESC
					LIMIT 1";
		}elseif ($flag == "next") {
			$format = "SELECT
					tindakan_hd.id,
					tindakan_hd.pasien_id
					FROM `tindakan_hd`
					WHERE
					tindakan_hd.id > $current_tindakan AND
					tindakan_hd.pasien_id = $pasien_id
					LIMIT 1";
		}
		
		return $this->db->query($format);
	}

	public function get_jumlah_tindakan($bulan,$tahun)
	{
		$SQL = "SELECT COUNT(*) as jumlah_tindakan FROM tindakan_hd WHERE `status` = 3 AND is_active = 1 AND month(tanggal) = '$bulan' AND year(tanggal) = '$tahun' AND penjamin_id	!= 1";

		return $this->db->query($SQL)->result_array();
	}

	public function get_data_tindakan($pasien_id,$periode_tindakan)
	{
		$this->db->select("DATE_FORMAT(`tanggal`,'%d %b %Y') as tanggal, DATE_FORMAT(`tanggal`,'%d-%m-%Y') as val");
		$this->db->where('pasien_id', $pasien_id);
		$this->db->where('month(tanggal)', $periode_tindakan);

		return $this->db->get($this->_table)->result_array();
	}

	public function get_tindakan_minggu($pasien_id, $penjamin_id)
	{
		$first_week = date('Y-m-d', strtotime('monday this week'));
        $last_week = date('Y-m-d', strtotime('sunday this week'));

        $SQL = "SELECT COUNT(*) as jumlah_tindakan FROM tindakan_hd WHERE pasien_id = $pasien_id AND is_active = 1 AND penjamin_id IN (2,3,5,6,7,8,9) AND date(tanggal) BETWEEN '$first_week' AND '$last_week'";

		return $this->db->query($SQL)->result();
	}

	public function get_pasien_non_jadwal($shift,$cabang_id)
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

		$SQL = "SELECT IF(LENGTH(pasien.no_member)<=11, pasien.no_member, RIGHT(pasien.no_member,7)) AS no_member, pasien.nama FROM pendaftaran_tindakan JOIN pasien ON pendaftaran_tindakan.pasien_id = pasien.id WHERE date(pendaftaran_tindakan.created_date) = '$date' AND pendaftaran_tindakan.shift = $shift AND pendaftaran_tindakan.is_active = 1 AND pendaftaran_tindakan.`status` != 5 AND pendaftaran_tindakan.pasien_id NOT IN ( SELECT pasien_id FROM jadwal WHERE date(jadwal.tanggal) = '$date' AND jadwal.waktu = $jam )";

		return $this->db->query($SQL);

	}

	public function get_data_antrian($status)
	{
		$date = date('Y-m-d');

		$SQL = "SELECT tindakan_hd.shift, pasien.nama as nama_pasien, bed.kode, user.nama as nama_dokter, tindakan_hd.created_date FROM `tindakan_hd` 
				JOIN pasien ON tindakan_hd.pasien_id = pasien.id
				JOIN bed ON tindakan_hd.bed_id = bed.id
				JOIN user ON tindakan_hd.dokter_id = user.id
				WHERE tindakan_hd.`status` = $status
				AND date(tindakan_hd.tanggal) = '$date'
				ORDER BY shift ASC, tindakan_hd.id ASC;";
		return $this->db->query($SQL);
	}

	public function get_data_tiga_bulan($pasien_id, $date)
	{		
		$date = date('Y-m-d', strtotime($date));
		$date_past = date('Y-m-d', strtotime($date.' -3 months'));

		$SQL = "SELECT
					tindakan_hd.tanggal,
					tindakan_hd.berat_awal,
					tindakan_hd.berat_akhir,
					tindakan_hd_penaksiran.uf_goal,
					tindakan_hd_penaksiran.time_of_dialysis,
					tindakan_hd_penaksiran.quick_of_blood,
					tindakan_hd_penaksiran.quick_of_dialysis
				FROM
					`tindakan_hd`
				JOIN tindakan_hd_penaksiran ON tindakan_hd.id = tindakan_hd_penaksiran.tindakan_hd_id
				WHERE
					date(tindakan_hd.tanggal) <= '$date'
				AND date(tindakan_hd.tanggal) >= '$date_past'
				AND tindakan_hd.pasien_id = $pasien_id
				AND STATUS = 3
				ORDER BY date(tindakan_hd.tanggal) DESC;";

		return $this->db->query($SQL);
	}

	public function get_data_per_tanggal($pasien_id, $date)
	{		
		$date = date('Y-m-d', strtotime($date));
		$SQL = "SELECT
					tindakan_hd.tanggal,
					tindakan_hd.berat_awal,
					tindakan_hd.berat_akhir,
					tindakan_hd_penaksiran.uf_goal,
					tindakan_hd_penaksiran.time_of_dialysis,
					tindakan_hd_penaksiran.quick_of_blood,
					tindakan_hd_penaksiran.quick_of_dialysis
				FROM
					`tindakan_hd`
				JOIN tindakan_hd_penaksiran ON tindakan_hd.id = tindakan_hd_penaksiran.tindakan_hd_id
				WHERE
					date(tindakan_hd.tanggal) = '$date'
				AND tindakan_hd.pasien_id = $pasien_id
				AND STATUS = 3
				ORDER BY date(tindakan_hd.tanggal) DESC;";

		return $this->db->query($SQL);
	}
}
