<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tindakan_hd_history_m extends MY_Model {

	protected $_table        = 'tindakan_hd_history';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
			
	);

	private $_fillable_edit = array(
		
	);

	protected $datatable_columns = array(
		//column di table  => alias
		'tindakan_hd_history.tanggal' => 'tanggal',	
		'tindakan_hd_history.pasien_id' => 'pasien_id',	
		'tindakan_hd_history.penjamin_id' => 'penjamin_id',	
		'pasien.no_member' => 'no_member',	
		'pasien.nama' => 'nama',	
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable_laporan_rekap($tgl_awal,$tgl_akhir,$shift='',$penjamin='')
	{	
 
		$join1 = array('pasien',$this->_table . '.pasien_id = pasien.id');
		$join_tables = array($join1);

	
		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.is_manual', 0);
		if ($tgl_awal!=NULL && $tgl_akhir!=NULL) {
			$this->db->where("DATE(tindakan_hd_history.tanggal) >= ", $tgl_awal);
			$this->db->where("DATE(tindakan_hd_history.tanggal) <= ", $tgl_akhir);
		}
		if ($shift!='0') {
			$this->db->where($this->_table.'.shift', $shift);	
		}
		if ($penjamin!='0') {
			$this->db->where($this->_table.'.penjamin_id', $penjamin);	
		}
		$this->db->or_where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.is_manual', NULL);
		if ($tgl_awal!=NULL && $tgl_akhir!=NULL) {
			$this->db->where("DATE(tindakan_hd_history.tanggal) >= ", $tgl_awal);
			$this->db->where("DATE(tindakan_hd_history.tanggal) <= ", $tgl_akhir);
		}
		if ($shift!='0') {
			$this->db->where($this->_table.'.shift', $shift);	
		}
		if ($penjamin!='0') {
			$this->db->where($this->_table.'.penjamin_id', $penjamin);	
		}
	  
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.is_manual', 0);
		if ($tgl_awal!=NULL && $tgl_akhir!=NULL) {
			$this->db->where("DATE(tindakan_hd_history.tanggal) >= ", $tgl_awal);
			$this->db->where("DATE(tindakan_hd_history.tanggal) <= ", $tgl_akhir);
		}
		if ($shift!='0') {
			$this->db->where($this->_table.'.shift', $shift);	
		}
		if ($penjamin!='0') {
			$this->db->where($this->_table.'.penjamin_id', $penjamin);	
		}
		$this->db->or_where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.is_manual', NULL);
		if ($tgl_awal!=NULL && $tgl_akhir!=NULL) {
			$this->db->where("DATE(tindakan_hd_history.tanggal) >= ", $tgl_awal);
			$this->db->where("DATE(tindakan_hd_history.tanggal) <= ", $tgl_akhir);
		}
		if ($shift!='0') {
			$this->db->where($this->_table.'.shift', $shift);	
		}
		if ($penjamin!='0') {
			$this->db->where($this->_table.'.penjamin_id', $penjamin);	
		}
	  
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.is_manual', 0);
		if ($tgl_awal!=NULL && $tgl_akhir!=NULL) {
			$this->db->where("DATE(tindakan_hd_history.tanggal) >= ", $tgl_awal);
			$this->db->where("DATE(tindakan_hd_history.tanggal) <= ", $tgl_akhir);
		}
		if ($shift!='0') {
			$this->db->where($this->_table.'.shift', $shift);	
		}
		if ($penjamin!='0') {
			$this->db->where($this->_table.'.penjamin_id', $penjamin);	
		}
		$this->db->or_where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.is_manual', NULL);
		if ($tgl_awal!=NULL && $tgl_akhir!=NULL) {
			$this->db->where("DATE(tindakan_hd_history.tanggal) >= ", $tgl_awal);
			$this->db->where("DATE(tindakan_hd_history.tanggal) <= ", $tgl_akhir);
		}
		if ($shift!='0') {
			$this->db->where($this->_table.'.shift', $shift);	
		}
		if ($penjamin!='0') {
			$this->db->where($this->_table.'.penjamin_id', $penjamin);	
		}

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
	  

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
		// $format = "SELECT * FROM tindakan_hd_history WHERE bed_id = '".$id_bed."' AND LEFT(tanggal, 10) = '".date('Y-m-d')."' ";	
		$format = "SELECT tindakan_hd_history.*, pasien.id as id_pasien, pasien.nama as nama_pasien, tindakan_hd_penaksiran_history.waktu, tindakan_hd_penaksiran_history.time_of_dialysis FROM tindakan_hd_history JOIN pasien ON tindakan_hd_history.pasien_id = pasien.id JOIN tindakan_hd_penaksiran_history ON tindakan_hd_history.id = tindakan_hd_penaksiran_history.tindakan_hd_id WHERE bed_id = '".$id_bed."' AND tindakan_hd_history.status = 1 AND tindakan_hd_history.shift = '".$shift."' ORDER BY tindakan_hd_history.tanggal DESC";	
		return $this->db->query($format)->row(0);
	}

	function get_bed_pasien_isi($id_bed,$shift)
	{	
		// $format = "SELECT * FROM tindakan_hd_history WHERE bed_id = '".$id_bed."' AND LEFT(tanggal, 10) = '".date('Y-m-d')."' ";	
		$format = "SELECT tindakan_hd_history.*, pasien.id as id_pasien, pasien.nama as nama_pasien, tindakan_hd_penaksiran_history.waktu, tindakan_hd_penaksiran_history.time_of_dialysis FROM tindakan_hd_history JOIN pasien ON tindakan_hd_history.pasien_id = pasien.id JOIN tindakan_hd_penaksiran_history ON tindakan_hd_history.id = tindakan_hd_penaksiran_history.tindakan_hd_id WHERE bed_id = '".$id_bed."' AND tindakan_hd_history.status = 2 AND tindakan_hd_history.shift = '".$shift."' ORDER BY tindakan_hd_history.tanggal DESC ";	
		return $this->db->query($format)->row(0);
	}
	function get_unprocessed_id($id_tindakan)
	{	
		// $format = "SELECT * FROM tindakan_hd_history WHERE bed_id = '".$id_bed."' AND LEFT(tanggal, 10) = '".date('Y-m-d')."' ";	
		$format = "SELECT tindakan_hd_history.*, pasien.id as id_pasien, pasien.nama as nama_pasien, bed.kode as kode_bed FROM tindakan_hd_history JOIN pasien ON tindakan_hd_history.pasien_id = pasien.id JOIN bed ON tindakan_hd_history.bed_id = bed.id WHERE tindakan_hd_history.status = 1 AND tindakan_hd_history.id = '".$id_tindakan."' ORDER BY tindakan_hd_history.tanggal DESC";	
		return $this->db->query($format)->row(0);
	}
	function get_unprocessed($shift, $id_tindakan)
	{	
		// $format = "SELECT * FROM tindakan_hd_history WHERE bed_id = '".$id_bed."' AND LEFT(tanggal, 10) = '".date('Y-m-d')."' ";	
		$format = "SELECT tindakan_hd_history.*, pasien.id as id_pasien, pasien.nama as nama_pasien, bed.kode as kode_bed FROM tindakan_hd_history JOIN pasien ON tindakan_hd_history.pasien_id = pasien.id JOIN bed ON tindakan_hd_history.bed_id = bed.id WHERE tindakan_hd_history.status = 1 AND tindakan_hd_history.shift = '".$shift."' AND tindakan_hd_history.id != '".$id_tindakan."' ORDER BY tindakan_hd_history.tanggal DESC";	
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
		$format = "SELECT MAX(SUBSTRING(`no_transaksi`,17,4)) AS max_nomor_po FROM `tindakan_hd_history` WHERE SUBSTRING(`no_transaksi`,12,4) = DATE_FORMAT(NOW(), '%y%m') and SUBSTRING(`no_transaksi`,3,8)= '".$cabang."'";	
		return $this->db->query($format);
	}

	public function detail_histori_kiri($id,$pasien_id)
	{
		// $format = "select * from tindakan_hd_history a join pasien b on b.id=a.pasien_id join (select tanggal,pasien_id from tindakan_hd_penaksiran_history  group by tanggal desc limit 1) c on c.pasien_id=a.pasien_id where a.id=".$id;
		$format = "SELECT b.url_photo, a.no_transaksi, a.tanggal, b.no_member, b.nama, (SELECT tanggal from tindakan_hd_history WHERE pasien_id = $pasien_id AND status = 3 ORDER BY tanggal DESC LIMIT 1) as tanggal_terakhir, ifnull(a.keterangan, '-') AS keterangan, (SELECT id from tindakan_hd_history WHERE pasien_id = $pasien_id ORDER BY tanggal DESC LIMIT 1) as tindakan_id FROM tindakan_hd_history a LEFT JOIN pasien b ON b.id = a.pasien_id WHERE a.pasien_id = $pasien_id AND a.id = $id";

		return $this->db->query($format);
	}
	public function detail_histori_kanan($id)
	{
		$format = "select b.nama,ifnull(a.berat_awal,'-') as berat_awal,ifnull(a.berat_akhir,'-') as berat_akhir,ifnull(f.nama,'-') as nama_poli,ifnull(d.kode,'-') as kode,ifnull(e.nama,'-') as nama_penjamin from tindakan_hd_history a left join user b on b.id=a.dokter_id left join  rujukan c on c.tindakan_id=a.id left join bed d on d.id=a.bed_id left join penjamin e on e.id=a.penjamin_id left join poliklinik f on f.id=c.poliklinik_asal_id where a.id=?";
		return $this->db->query($format,$id);
	}

	function get_by_transaction_id($transaction_id=null,$pasien_id,$tanggal=null,$flag)
	{
		$where='';
		if($flag==1){
			$where=" and tanggal='".$tanggal."'";
			$sql= "select * from tindakan_hd_penaksiran_history where tindakan_hd_id=".$transaction_id." and pasien_id=".$pasien_id.$where;
		}else if($flag==2){
			$sql= "select * from tindakan_hd_penaksiran_history  f join rm_tindakan_pasien g on f.tindakan_hd_id=g.tindakan_id where  f.pasien_id=".$pasien_id." and tindakan_hd_id in (select b.id from rm_tindakan_pasien a join tindakan_hd_history b on a.tindakan_id=b.id where a.pasien_id=".$pasien_id.") order by f.tanggal asc,g.tipe asc,g.tipe asc limit 1";
		}else{

		}	

		return $this->db->query($sql);
	}

	function get_by_transaction_id2($transaction_id=null)
	{
		$sql= "select * from tindakan_hd_penaksiran_history where tindakan_hd_id=".$transaction_id;
		return $this->db->query($sql);		 
	}

	function getpageprev($pasien_id,$tanggal=null)
	{ 
		$sql= "select * from tindakan_hd_penaksiran_history f join rm_tindakan_pasien g on f.tindakan_hd_id=g.tindakan_id where   f.pasien_id=".$pasien_id." and tindakan_hd_id in (select b.id from rm_tindakan_pasien a join tindakan_hd_history b on a.tindakan_id=b.id where a.pasien_id=".$pasien_id.") and f.tanggal < '".$tanggal."' order by f.tanggal desc,g.tipe asc limit 1";
		return $this->db->query($sql);
	}

	function getpagenext($pasien_id,$tanggal=null)
	{
		 
		$sql= "select * from tindakan_hd_penaksiran_history f join rm_tindakan_pasien g on f.tindakan_hd_id=g.tindakan_id  where  f.pasien_id=".$pasien_id." and tindakan_hd_id in (select b.id from rm_tindakan_pasien a join tindakan_hd_history b on a.tindakan_id=b.id where a.pasien_id=".$pasien_id.") and f.tanggal > '".$tanggal."' order by f.tanggal asc,g.tipe asc limit 1";
		return $this->db->query($sql);
	}

	function getpagefirst($pasien_id,$tanggal=null)
	{
		 
		$sql= "select * from tindakan_hd_penaksiran_history f join rm_tindakan_pasien g on f.tindakan_hd_id=g.tindakan_id  where  f.pasien_id=".$pasien_id." and tindakan_hd_id in (select b.id from rm_tindakan_pasien a join tindakan_hd_history b on a.tindakan_id=b.id where a.pasien_id=".$pasien_id.")  order by f.tanggal asc,g.tipe asc limit 1";
		return $this->db->query($sql);
	}

	function getpagelast($pasien_id,$tanggal=null)
	{ 
		$sql= "select * from tindakan_hd_penaksiran_history f join rm_tindakan_pasien g on f.tindakan_hd_id=g.tindakan_id where  f.pasien_id=".$pasien_id." and tindakan_hd_id in (select b.id from rm_tindakan_pasien a join tindakan_hd_history b on a.tindakan_id=b.id where a.pasien_id=".$pasien_id.")  order by f.tanggal desc,g.tipe asc limit 1";
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

		$sql = "SELECT tindakan_hd_history.id AS id_tindakan, tindakan_hd_history.tanggal, tindakan_hd_history.berat_awal, tindakan_hd_history.berat_akhir, tindakan_hd_penaksiran_history.dialyzer_new, tindakan_hd_penaksiran_history.dialyzer_reuse,
								(SELECT observasi_dialisis_hd_history.tekanan_darah_1 FROM tindakan_hd_history a JOIN observasi_dialisis_hd_history ON observasi_dialisis_hd_history.transaksi_hd_id = a.id WHERE a.pasien_id = ".$pasien_id." AND a.tanggal BETWEEN '".$tanggal_awal."' AND '".$tanggal_akhir."' AND a.id = tindakan_hd_history.id ORDER BY observasi_dialisis_hd_history.waktu_pencatatan ASC LIMIT 1) AS td_pre_1,
								(SELECT observasi_dialisis_hd_history.tekanan_darah_2 FROM tindakan_hd_history a JOIN observasi_dialisis_hd_history ON observasi_dialisis_hd_history.transaksi_hd_id = a.id WHERE a.pasien_id = ".$pasien_id." AND a.tanggal BETWEEN '".$tanggal_awal."' AND '".$tanggal_akhir."' AND a.id = tindakan_hd_history.id ORDER BY observasi_dialisis_hd_history.waktu_pencatatan ASC LIMIT 1) AS td_pre_2,
								(SELECT observasi_dialisis_hd_history.tekanan_darah_1 FROM tindakan_hd_history a JOIN observasi_dialisis_hd_history ON observasi_dialisis_hd_history.transaksi_hd_id = a.id WHERE a.pasien_id = ".$pasien_id." AND a.tanggal BETWEEN '".$tanggal_awal."' AND '".$tanggal_akhir."' AND a.id = tindakan_hd_history.id ORDER BY observasi_dialisis_hd_history.waktu_pencatatan DESC LIMIT 1) AS td_post_1,
								(SELECT observasi_dialisis_hd_history.tekanan_darah_2 FROM tindakan_hd_history a JOIN observasi_dialisis_hd_history ON observasi_dialisis_hd_history.transaksi_hd_id = a.id WHERE a.pasien_id = ".$pasien_id." AND a.tanggal BETWEEN '".$tanggal_awal."' AND '".$tanggal_akhir."' AND a.id = tindakan_hd_history.id ORDER BY observasi_dialisis_hd_history.waktu_pencatatan DESC LIMIT 1) AS td_post_2
				FROM tindakan_hd_history
				LEFT JOIN tindakan_hd_penaksiran_history ON tindakan_hd_penaksiran_history.tindakan_hd_id = tindakan_hd_history.id
				LEFT JOIN observasi_dialisis_hd_history ON observasi_dialisis_hd_history.transaksi_hd_id = tindakan_hd_history.id
				WHERE tindakan_hd_history.pasien_id = ".$pasien_id." 
				AND tindakan_hd_history.tanggal BETWEEN '".$tanggal_awal."' AND '".$tanggal_akhir."'
				GROUP BY tindakan_hd_history.pasien_id, date(tindakan_hd_history.tanggal)";

		return $this->db->query($sql);
	}

	public function get_data_hd_pasien($current_tindakan, $pasien_id, $flag)
	{
		$format = "";

		if ($flag == "prev") {
			$format = "SELECT
					tindakan_hd_history.id,
					tindakan_hd_history.pasien_id
					FROM `tindakan_hd_history`
					WHERE
					tindakan_hd_history.id < $current_tindakan AND
					tindakan_hd_history.pasien_id = $pasien_id
					ORDER BY
					tindakan_hd_history.id DESC
					LIMIT 1";
		}elseif ($flag == "next") {
			$format = "SELECT
					tindakan_hd_history.id,
					tindakan_hd_history.pasien_id
					FROM `tindakan_hd_history`
					WHERE
					tindakan_hd_history.id > $current_tindakan AND
					tindakan_hd_history.pasien_id = $pasien_id
					LIMIT 1";
		}
		
		return $this->db->query($format);
	}

	public function get_jumlah_tindakan($bulan,$tahun)
	{
		$SQL = "SELECT COUNT(*) as jumlah_tindakan FROM tindakan_hd_history WHERE `status` = 3 AND is_active = 1 AND month(tanggal) = '$bulan' AND year(tanggal) = '$tahun' AND penjamin_id	!= 1";

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

        $SQL = "SELECT COUNT(*) as jumlah_tindakan FROM tindakan_hd_history WHERE pasien_id = $pasien_id AND is_active = 1 AND penjamin_id IN (2,3,5,6,7,8,9) AND date(tanggal) BETWEEN '$first_week' AND '$last_week'";

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

		$SQL = "SELECT tindakan_hd_history.shift, pasien.nama as nama_pasien, bed.kode, user.nama as nama_dokter, tindakan_hd_history.created_date FROM `tindakan_hd_history` 
				JOIN pasien ON tindakan_hd_history.pasien_id = pasien.id
				JOIN bed ON tindakan_hd_history.bed_id = bed.id
				JOIN user ON tindakan_hd_history.dokter_id = user.id
				WHERE tindakan_hd_history.`status` = $status
				AND date(tindakan_hd_history.tanggal) = '$date'
				ORDER BY shift ASC, tindakan_hd_history.id ASC;";
		return $this->db->query($SQL);
	}

	public function get_data_tiga_bulan($pasien_id, $date)
	{		
		$date = date('Y-m-d', strtotime($date));
		$date_past = date('Y-m-d', strtotime($date.' -3 months'));

		$SQL = "SELECT
					tindakan_hd_history.tanggal,
					tindakan_hd_history.berat_awal,
					tindakan_hd_history.berat_akhir,
					tindakan_hd_penaksiran_history.uf_goal,
					tindakan_hd_penaksiran_history.time_of_dialysis,
					tindakan_hd_penaksiran_history.quick_of_blood,
					tindakan_hd_penaksiran_history.quick_of_dialysis
				FROM
					`tindakan_hd_history`
				JOIN tindakan_hd_penaksiran_history ON tindakan_hd_history.id = tindakan_hd_penaksiran_history.tindakan_hd_id
				WHERE
					date(tindakan_hd_history.tanggal) <= '$date'
				AND date(tindakan_hd_history.tanggal) >= '$date_past'
				AND tindakan_hd_history.pasien_id = $pasien_id
				AND STATUS = 3
				ORDER BY date(tindakan_hd_history.tanggal) DESC;";

		return $this->db->query($SQL);
	}

	public function get_data_per_tanggal($pasien_id, $date)
	{		
		$date = date('Y-m-d', strtotime($date));
		$SQL = "SELECT
					tindakan_hd_history.tanggal,
					tindakan_hd_history.berat_awal,
					tindakan_hd_history.berat_akhir,
					tindakan_hd_penaksiran_history.uf_goal,
					tindakan_hd_penaksiran_history.time_of_dialysis,
					tindakan_hd_penaksiran_history.quick_of_blood,
					tindakan_hd_penaksiran_history.quick_of_dialysis
				FROM
					`tindakan_hd_history`
				JOIN tindakan_hd_penaksiran_history ON tindakan_hd_history.id = tindakan_hd_penaksiran_history.tindakan_hd_id
				WHERE
					date(tindakan_hd_history.tanggal) = '$date'
				AND tindakan_hd_history.pasien_id = $pasien_id
				AND STATUS = 3
				ORDER BY date(tindakan_hd_history.tanggal) DESC;";

		return $this->db->query($SQL);
	}

	public function get_data_tindakan_bpjs($tgl_awal, $tgl_akhir){

		$SQL = "SELECT
					*
				FROM
					`tindakan_hd_history`
				WHERE
					date(tanggal) >= '$tgl_awal'
				AND date(tanggal) <= '$tgl_akhir'
				AND penjamin_id != 1
				AND penjamin_id IS NOT NULL
				AND is_active = 1
				ORDER BY
					tanggal ASC;";

		return $this->db->query($SQL);
	}
		
	public function get_data_tindakan_double($tgl_awal, $tgl_akhir){

		$SQL = "SELECT
					*
				FROM
					`tindakan_hd_history`
				WHERE DATE(tanggal) >= '$tgl_awal'
				AND DATE(tanggal) <= '$tgl_akhir'
				AND is_active = 1
				AND penjamin_id <> 1
				GROUP BY
					pasien_id, date(tanggal)

				HAVING (COUNT(pasien_id) > 1 AND COUNT(date(tanggal)) > 1)
				ORDER BY tanggal ASC";

		return $this->db->query($SQL);
	}



}
