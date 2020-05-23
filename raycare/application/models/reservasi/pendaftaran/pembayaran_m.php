<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembayaran_m extends MY_Model {

	protected $_table        = 'o_s_pembayaran_transaksi';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		'kode',
		'nama',
		'alamat', 
		'nomor_telepon1', 
		'nomor_telepon2', 
		'nomor_fax', 		
	);

	private $_fillable_edit = array(
		'kode',
		'nama',
		'alamat', 
		'nomor_telepon1', 
		'nomor_telepon2', 
		'nomor_fax', 
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'o_s_pembayaran_transaksi.no_transaksi' => 'no_transaksi', 
		'o_s_pembayaran_transaksi.nama_pasien'  => 'nama', 
		'o_s_pembayaran_transaksi.tanggal'      =>'tanggal', 
		'o_s_pembayaran_transaksi.rupiah'        =>'rupiah', 
		'o_s_pembayaran_transaksi.is_pay'      => 'is_pay', 
		 
		 
		
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($pasien_id=null,$cabang_id=null)
	{	
		$join_tables = array();
		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.pasien_id',$pasien_id);
		$this->db->where($this->_table.'.cabang_id',$cabang_id);
		// $this->db->where('cabang_telepon.is_primary', 1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.pasien_id',$pasien_id);
		$this->db->where($this->_table.'.cabang_id',$cabang_id);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.pasien_id',$pasien_id);
		$this->db->where($this->_table.'.cabang_id',$cabang_id);

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
	   //  die(dump($result->records));
		return $result; 
	}

	public function get_data_poli($cabang_id)
	{
		$this->db->select("cabang_poliklinik.id as id,
							poliklinik.nama as nama,
							cabang_poliklinik.jam_buka as jam_buka,
							cabang_poliklinik.jam_tutup as jam_tutup");
		$this->db->join("poliklinik", $this->_table.".poliklinik_id = poliklinik.id");
		$this->db->where($this->_table.".cabang_id", $cabang_id);

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

	public function get_antrian($poliklinik_id,$dokter_id){
		$sql="SELECT MAX(`antrian`) AS no_antrian   FROM pendaftaran_tindakan LEFT JOIN pasien b ON pendaftaran_tindakan.pasien_id = b.id WHERE pendaftaran_tindakan.poliklinik_id = ".$poliklinik_id." AND pendaftaran_tindakan.dokter_id =".$dokter_id;
		

		return $this->db->query($sql);

	}

	public function get_id_o_s($pasien_id,$cabang_id){
		$sql="SELECT * from o_s_pembayaran_tindakan a where a.pasien_id = ".$pasien_id." AND a.cabang_id =".$cabang_id;
		

		return $this->db->query($sql);

	}

	public function getdatapasien2($id){
		$sql= "select b.nama,c.alamat,b.gender,b.tanggal_lahir,DATEDIFF(now(),tanggal_lahir) AS usia,b.no_member,b.keterangan,d.nama as nama1,e.nama as nama2,b.ref_kode_cabang as kodecabang,ref_kode_rs_rujukan as rujukan,ref_tanggal_rujukan as tgglrujukan,ref_nomor_rujukan as nomorrujukan,b.dokter_pengirim,b.tempat_lahir,b.is_meninggal from  pasien b left join pasien_alamat c on c.pasien_id=b.id left join (select nama,id from info_umum) d on d.id=b.agama_id left join (select nama,id from info_umum) e on e.id=b.golongan_darah_id where b.id=?";

		return $this->db->query($sql,$id);
	}

	 

	public function getdatapasienphone2($id){
		$sql= "select c.nama,nomor from   pasien b  left join (select nomor,pasien_id,is_primary,is_active,subjek.nama from pasien_telepon left join subjek on subjek.id=pasien_telepon.subjek_id where is_primary=1 and is_active=1 and tipe=2)  c on c.pasien_id=b.id where b.id=?";

		return $this->db->query($sql,$id);
	}

	public function getdatapasienalamat($id){
		$sql= "select c.nama,alamat,rt_rw from pasien b left join (select alamat,pasien_id,is_primary,is_active,subjek.nama,rt_rw from pasien_alamat left join subjek on subjek.id=pasien_alamat.subjek_id where is_primary=1 and is_active=1 and tipe=1)  c on c.pasien_id=b.id where b.id=?";

		return $this->db->query($sql,$id);
	}
	public function getdatapasienalamat2($id){
		$sql= "select bb.nama as kelurahan,cc.nama as kecamatan,dd.nama as kota from(select c.nama,alamat,rt_rw,kota_id,kecamatan_id,kelurahan_id from  pasien b  left join (select alamat,pasien_id,is_primary,is_active,subjek.nama,rt_rw,kota_id,kecamatan_id,kelurahan_id from pasien_alamat left join subjek on subjek.id=pasien_alamat.subjek_id where is_primary=1 and is_active=1 and tipe=1)  c on c.pasien_id=b.id where b.id=?) aa left join (select nama,id from region where tipe=4) bb on bb.id=aa.kecamatan_id left join (select nama,id from region where tipe=5) cc on cc.id=aa.kelurahan_id left join (select nama,id from region where tipe=3) dd on dd.id=aa.kota_id";

		return $this->db->query($sql,$id);
	}
	 public function getdatapasienpenyakit($id){
		$sql= "select tipe,c.nama from  pasien b   left join (select pasien_id,penyakit_id,tipe,nama from pasien_penyakit left join penyakit on penyakit.id=pasien_penyakit.penyakit_id where pasien_penyakit.is_active=1 and penyakit.is_active=1) c on c.pasien_id=b.id where b.id=?";

		return $this->db->query($sql,$id);
	}

	public function getdatatindakanfrekuensi($startdate,$enddate,$pasienid){
		$sql= "select count(*) as counts from tindakan_hd where pasien_id=".$pasienid." and tanggal between '".$startdate."' and '".$enddate."'";

		return $this->db->query($sql);
	}

	public function check_jadwal($cabang_id,$bed,$tipe,$date){
		$sql= "select * from jadwal where cabang_id=".$cabang_id." and no_urut_bed=".$bed." and DATE_FORMAT(tanggal, '%Y-%m-%d')='".$date."' and tipe=".$tipe;

		return $this->db->query($sql);
	}

	public function check_jadwal2($cabang_id,$date){
		$sql= "select pasien.nama,jadwal.pasien_id,no_urut_bed,tipe,tanggal from jadwal join pasien on jadwal.pasien_id=pasien.id where jadwal.cabang_id=".$cabang_id."  and DATE_FORMAT(tanggal, '%Y-%m-%d')='".$date."'";

		return $this->db->query($sql);
	}

	public function get_pasien_data($pasien_id){
		$sql= "SELECT pasien.url_photo as url_photo,pasien.id AS id, pasien.no_member  AS no_ktp, pasien.nama AS nama, pasien.gender AS gender, pasien.tanggal_registrasi AS tanggal_registrasi , pasien.tempat_lahir AS tempat_lahir, pasien.tanggal_lahir AS tanggal_lahir, pasien.is_active AS active, pasien_alamat.alamat AS alamat, pasien_telepon.nomor AS nomor, DATEDIFF(CURRENT_DATE, STR_TO_DATE(pasien.tanggal_lahir, '%Y-%m-%d'))/365 AS umur, (SELECT region.nama FROM region WHERE region.tipe = 5 AND pasien_alamat.kelurahan_id = region.id ) AS kelurahan, (SELECT region.nama FROM region WHERE region.tipe = 4 AND pasien_alamat.kecamatan_id = region.id ) AS kecamatan, (SELECT region.nama FROM region WHERE region.tipe = 3 AND pasien_alamat.kota_id = region.id ) AS kota, (SELECT region.nama FROM region WHERE region.tipe = 2 AND pasien_alamat.propinsi_id = region.id ) AS propinsi, (SELECT region.nama FROM region WHERE region.tipe = 1 AND pasien_alamat.negara_id = region.id ) AS negara,
				(SELECT COUNT(rm_transaksi_pasien.pasien_id) FROM rm_transaksi_pasien WHERE pasien.id = rm_transaksi_pasien.pasien_id) AS count_transaksi,
			 	(SELECT COUNT(o_s_pembayaran_transaksi.pasien_id) FROM o_s_pembayaran_transaksi WHERE pasien.id = o_s_pembayaran_transaksi.pasien_id) AS count_tagihan
				FROM pasien
				LEFT JOIN pasien_alamat ON pasien.id = pasien_alamat.pasien_id
				LEFT JOIN pasien_telepon ON pasien.id = pasien_telepon.pasien_id
				WHERE pasien.is_active =  1
				AND pasien_telepon.is_primary =  1
				AND pasien.is_meninggal =  0 and pasien.id=".$pasien_id;

		return $this->db->query($sql);
	}
}

/* End of file pembayaran_m.php */
/* Location: ./application/models/master/pembayaran_m.php */