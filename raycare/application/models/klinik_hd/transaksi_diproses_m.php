<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transaksi_diproses_m extends MY_Model {

	protected $_table        = 'tindakan_hd_history';
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
		'tindakan_hd_history.no_transaksi'      => 'no_transaksi', 
		'pasien.nama'                   => 'nama', 
		'pasien.tempat_lahir'           => 'tempat_lahir', 
		'pasien.tanggal_lahir'          => 'tanggal_lahir',
		'f.alamat'                      => 'alamat',
		'inf_lokasi.nama_kecamatan'     => 'kecamatan',
		'inf_lokasi.nama_kelurahan'     => 'kelurahan',
		'inf_lokasi.nama_kabupatenkota' => 'kota',
		'tindakan_hd_history.status'            => 'status',
		'user.nama'						=> 'nama_dokter',
		'tindakan_hd_history.id'                => 'id',
		'pasien.id'                     => 'pasienid', 
		'pasien.url_photo'              => 'url_photo', 
		'pasien.no_member'              => 'no_member', 
		'tindakan_hd_history.tanggal'           => 'tanggal', 
		'tindakan_hd_history.shift'           => 'shift', 
		
	);

	protected $datatable_columns2 = array(
		//column di table  => alias
		'tindakan_hd_history.no_transaksi'      => 'no_transaksi', 
		'tindakan_hd_history.tanggal'           => 'tanggal', 
		'pasien.nama'                   => 'nama', 
		'pasien.id'                     => 'pasienid', 
		'pasien.no_member'              => 'no_member', 
		'tindakan_hd_history.berat_awal'        => 'berat_awal',
		'tindakan_hd_history.berat_akhir'       => 'berat_akhir',
		'tindakan_hd_history.status'            => 'status',
		'tindakan_hd_history.id'                => 'id',
		'user.nama'                     => 'nama1',
		'r.nama'                     	=> 'nama3',
		'pasien.url_photo'              => 'url_photo',
		'user.url'                      => 'url_photo1',
		'pasien.no_member'              => 'no_member',
		'tindakan_hd_history.is_sep'            => 'is_sep',
		'tindakan_hd_history.is_single_inv'     => 'is_single_inv',
		'tindakan_hd_history.bed_id'     => 'bed_id',
		'tindakan_hd_history.penjamin_id'     => 'penjamin_id',
 
		
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
		$params['sort_by'] = 'tindakan_hd_history.id';
		$params['sort_dir'] = 'desc';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		 
		// $this->db->where('dokter_id',$this->session->userdata('user_id'));
		$this->db->where('tindakan_hd_history.cabang_id',$this->session->userdata('cabang_id'));
		 $this->db->where_in('tindakan_hd_history.status',$id);
		 $this->db->where('tindakan_hd_history.is_active',1);
		 $this->db->where('date(tindakan_hd_history.created_date)',date('Y-m-d'));
		 
		 
		 
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		// $this->db->where('dokter_id',$this->session->userdata('user_id'));
		 $this->db->where('tindakan_hd_history.cabang_id',$this->session->userdata('cabang_id'));
		$this->db->where_in('tindakan_hd_history.status',$id);
		$this->db->where('tindakan_hd_history.is_active',1);
		$this->db->where('date(tindakan_hd_history.created_date)',date('Y-m-d'));
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		// $this->db->where('dokter_id',$this->session->userdata('user_id'));
		 $this->db->where('tindakan_hd_history.cabang_id',$this->session->userdata('cabang_id'));
		$this->db->where_in('tindakan_hd_history.status',$id);
		$this->db->where('tindakan_hd_history.is_active',1);
		$this->db->where('date(tindakan_hd_history.created_date)',date('Y-m-d'));
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
// kalo alamat itu dihapus ternyata lebih cepat bisa ga dihapus joind sama select alamatnya karna yang lama jadi 3x lipat loadingnya dari alamat

	public function get_datatable2($id=null)
	{	
 
	 	$join1 = array('pasien', $this->_table . '.pasien_id = pasien.id');
	 	$join2 = array('cabang_poliklinik_dokter', $this->_table . '.dokter_id = cabang_poliklinik_dokter.dokter_id');
	 	$join3 = array('pendaftaran_tindakan_history', $this->_table . '.pendaftaran_tindakan_id = pendaftaran_tindakan_history.id','left');
	 	$join4 = array('user r', 'pendaftaran_tindakan_history.created_by = r.id','left');
	 	$join5 = array('user',  'cabang_poliklinik_dokter.dokter_id = user.id');
		  
		$join_tables = array($join1,$join2,$join3,$join4,$join5);
		//$join_tables = array();

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns2);
		$params['sort_by'] = 'tindakan_hd_history.tanggal';
		$params['sort_dir'] = 'desc';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		 $this->db->where('tindakan_hd_history.cabang_id',$this->session->userdata('cabang_id'));
		//$this->db->where('dokter_id',$this->session->userdata('user_id'));
		 $this->db->where_in('tindakan_hd_history.status',$id);
		 $this->db->where('tindakan_hd_history.is_active',1);
		 $this->db->where('date(tindakan_hd_history.tanggal) >= "2016-12-31"');
		 $this->db->group_by('tindakan_hd_history.pasien_id');
		 $this->db->group_by('date(tindakan_hd_history.tanggal)');
		 
		// dapatkan total row count;
		$query = $this->db->select('(1)')->get();
        $total_records = $query->num_rows();
		// $total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		 
		 $this->db->where('tindakan_hd_history.cabang_id',$this->session->userdata('cabang_id'));
		//$this->db->where('dokter_id',$this->session->userdata('user_id'));
		 $this->db->where_in('tindakan_hd_history.status',$id);
		 $this->db->where('tindakan_hd_history.is_active',1);
		 $this->db->where('date(tindakan_hd_history.tanggal) >= "2016-12-31"');

		  $this->db->group_by('tindakan_hd_history.pasien_id');
		 $this->db->group_by('date(tindakan_hd_history.tanggal)');
		// dapatkan total record filtered/search;
		$query = $this->db->select('(1)')->get();
        $total_display_records = $query->num_rows();
		// $total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		 
		 $this->db->where('tindakan_hd_history.cabang_id',$this->session->userdata('cabang_id'));
		//$this->db->where('dokter_id',$this->session->userdata('user_id'));
		 $this->db->where_in('tindakan_hd_history.status',$id);
		 $this->db->where('tindakan_hd_history.is_active',1);
		 $this->db->where('date(tindakan_hd_history.tanggal) >= "2016-12-31"');

		 $this->db->group_by('tindakan_hd_history.pasien_id');
		 $this->db->group_by('date(tindakan_hd_history.tanggal)');
		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns2 as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns2;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
 // die(dump($result->records));
		return $result; 
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

	public function getdatatindakan($id){
		$sql= "select a.created_date,b.nama,a.pasien_id from pendaftaran_tindakan a join user b on a.dokter_id=b.id where a.id=?";

		return $this->db->query($sql,$id);
	}

	public function getdatatindakanfrekuensi($startdate,$enddate,$pasienid){
		$sql= "select count(*) as counts from tindakan_hd_history where pasien_id=".$pasienid." and tanggal between '".$startdate."' and '".$enddate."'";

		return $this->db->query($sql);
	}

	public function getdatapasien($id){
		$sql= "select b.tanggal_registrasi,b.berat_badan_kering, b.nama,b.jangka_waktu, c.alamat,(CASE b.gender when 'L' THEN 'Laki-laki' ELSE 'Perempuan' END) as gender,b.tanggal_lahir,DATEDIFF(now(),tanggal_lahir) AS usia,b.no_member, b.url_photo as url_photo from pendaftaran_tindakan a join pasien b on a.pasien_id=b.id left join pasien_alamat c on c.pasien_id=b.id where a.id=?";

		return $this->db->query($sql,$id);
	}

	public function getdatapasien2($id){
		$sql= "select b.nama,c.alamat,b.gender,b.tanggal_lahir,DATEDIFF(now(),tanggal_lahir) AS usia,b.no_member,b.keterangan,d.nama as nama1,e.nama as nama2,b.ref_kode_cabang as kodecabang,ref_kode_rs_rujukan as rujukan,ref_tanggal_rujukan as tgglrujukan,ref_nomor_rujukan as nomorrujukan,b.dokter_pengirim,b.tempat_lahir from pendaftaran_tindakan a join pasien b on a.pasien_id=b.id left join pasien_alamat c on c.pasien_id=b.id left join (select nama,id from info_umum) d on d.id=b.agama_id left join (select nama,id from info_umum) e on e.id=b.golongan_darah_id where a.id=?";

		return $this->db->query($sql,$id);
	}

	public function getdatapasienphone($id){
		$sql= "select nomor from pendaftaran_tindakan a join pasien b on a.pasien_id=b.id left join (select nomor,pasien_id,is_primary,is_active from pasien_telepon where is_primary=1 and is_active=1)  c on c.pasien_id=b.id where a.id=?";

		return $this->db->query($sql,$id);
	}

	public function getdatapasienphone2($id){
		$sql= "select c.nama,nomor from pendaftaran_tindakan a join pasien b on a.pasien_id=b.id left join (select nomor,pasien_id,is_primary,is_active,subjek.nama from pasien_telepon left join subjek on subjek.id=pasien_telepon.subjek_id where is_primary=1 and is_active=1 and tipe=2)  c on c.pasien_id=b.id where a.id=?";

		return $this->db->query($sql,$id);
	}

	public function getdatapasienalamat($id){
		$sql= "select c.nama,alamat,rt_rw from pendaftaran_tindakan a join pasien b on a.pasien_id=b.id left join (select alamat,pasien_id,is_primary,is_active,subjek.nama,rt_rw from pasien_alamat left join subjek on subjek.id=pasien_alamat.subjek_id where is_primary=1 and is_active=1 and tipe=1)  c on c.pasien_id=b.id where a.id=?";

		return $this->db->query($sql,$id);
	}
	public function getdatapasienalamat2($id){
		$sql= "select bb.nama as kelurahan,cc.nama as kecamatan,dd.nama as kota from(select c.nama,alamat,rt_rw,kota_id,kecamatan_id,kelurahan_id from pendaftaran_tindakan a join pasien b on a.pasien_id=b.id left join (select alamat,pasien_id,is_primary,is_active,subjek.nama,rt_rw,kota_id,kecamatan_id,kelurahan_id from pasien_alamat left join subjek on subjek.id=pasien_alamat.subjek_id where is_primary=1 and is_active=1 and tipe=1)  c on c.pasien_id=b.id where a.id=?) aa left join (select nama,id from region where tipe=4) bb on bb.id=aa.kecamatan_id left join (select nama,id from region where tipe=5) cc on cc.id=aa.kelurahan_id left join (select nama,id from region where tipe=3) dd on dd.id=aa.kota_id";

		return $this->db->query($sql,$id);
	}
	 public function getdatapasienpenyakit($id){
		$sql= "select tipe,c.nama from pendaftaran_tindakan a join pasien b on a.pasien_id=b.id left join (select pasien_id,penyakit_id,tipe,nama from pasien_penyakit left join penyakit on penyakit.id=pasien_penyakit.penyakit_id where pasien_penyakit.is_active=1 and penyakit.is_active=1) c on c.pasien_id=b.id where a.id=?";

		return $this->db->query($sql,$id);
	}

	public function getlasttransaksi($pasien_id)
	{
		$sql = "SELECT * FROM tindakan_hd_history WHERE pasien_id = $pasien_id AND status=3 ORDER BY tanggal DESC LIMIT 1";
		return $this->db->query($sql);
	}

}

/* End of file transaksi_diproses_m.php */
/* Location: ./application/models/klinik_hd/transaksi_diproses_m.php */