<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transaksi_dokter_m extends MY_Model {

	protected $_table        = 'pendaftaran_tindakan';
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
		'pendaftaran_tindakan.shift'      => 'shift',
		'pasien.nama'                       => 'nama', 
		'poliklinik.nama'                       => 'nama_poli', 
		'pasien.tempat_lahir'               => 'tempat_lahir', 
		'pasien.tanggal_lahir'              => 'tanggal_lahir',
		'pasien.url_photo'                  => 'url_photo',
		'f.alamat'                          => 'alamat',
		'inf_lokasi.nama_kecamatan'         => 'kecamatan',
		'inf_lokasi.nama_kelurahan'         => 'kelurahan',
		'inf_lokasi.nama_kabupatenkota'     => 'kota',
		'pendaftaran_tindakan.created_date' => 'created_date',
		'pendaftaran_tindakan.antrian'      => 'antrian',
		'pendaftaran_tindakan.id'           => 'id',
		'pendaftaran_tindakan.keterangan'           => 'keterangan',
		'pendaftaran_tindakan.poliklinik_id'           => 'poliklinik_id',
		'pasien.no_member'                  => 'no_member',
		
		
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($cabang_id=null)
	{	
 
		 $join1 = array('pasien', $this->_table . '.pasien_id = pasien.id');
		 $join2 = array('poliklinik', $this->_table . '.poliklinik_id = poliklinik.id');
		 $join3 = array('(select alamat,pasien_id, kode_lokasi from pasien_alamat WHERE is_primary =  1) f', 'f.pasien_id = pasien.id','left');
		 $join4 = array('inf_lokasi','f.kode_lokasi = inf_lokasi.lokasi_kode','left');
		  
		 $join_tables = array($join1,$join2,$join3,$join4);
		//$join_tables = array();

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by']  = 'pendaftaran_tindakan.id';
		$params['sort_dir'] = 'asc';
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		if($this->session->userdata('level_id') == config_item('user_developer') || $this->session->userdata('user_id') == 183)
		{
			$this->db->where('pendaftaran_tindakan.cabang_id',$cabang_id);
			$this->db->where('pendaftaran_tindakan.status',1);
			$this->db->where('pendaftaran_tindakan.status_verif',2);
		}
		else
		{
			$this->db->where('pendaftaran_tindakan.cabang_id',$cabang_id);
			$this->db->where('dokter_id',$this->session->userdata('user_id'));
			$this->db->where('pendaftaran_tindakan.status',1);
			$this->db->where('pendaftaran_tindakan.status_verif',2);
			
		}
		 
		 
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		if($this->session->userdata('level_id') == config_item('user_developer') || $this->session->userdata('user_id') == 183)
		{
			$this->db->where('pendaftaran_tindakan.cabang_id',$cabang_id);
			$this->db->where('pendaftaran_tindakan.status',1);
			$this->db->where('pendaftaran_tindakan.status_verif',2);
		}
		else
		{
			$this->db->where('pendaftaran_tindakan.cabang_id',$cabang_id);
			$this->db->where('dokter_id',$this->session->userdata('user_id'));
			$this->db->where('pendaftaran_tindakan.status',1);
			$this->db->where('pendaftaran_tindakan.status_verif',2);
			
		}
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		if($this->session->userdata('level_id') == config_item('user_developer') || $this->session->userdata('user_id') == 183)
		{
			$this->db->where('pendaftaran_tindakan.cabang_id',$cabang_id);
			$this->db->where('pendaftaran_tindakan.status',1);
			$this->db->where('pendaftaran_tindakan.status_verif',2);
		}
		else
		{
			$this->db->where('pendaftaran_tindakan.cabang_id',$cabang_id);
			$this->db->where('dokter_id',$this->session->userdata('user_id'));
			$this->db->where('pendaftaran_tindakan.status',1);
			$this->db->where('pendaftaran_tindakan.status_verif',2);
			
		}
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
	public function get_datatable_ditolak($cabang_id=null)
	{	
 
		 $join1 = array('pasien', $this->_table . '.pasien_id = pasien.id');
		 $join2 = array('(select alamat,pasien_id, kode_lokasi from pasien_alamat WHERE is_primary =  1) f', 'f.pasien_id = pasien.id','left');
		 $join3 = array('inf_lokasi','f.kode_lokasi = inf_lokasi.lokasi_kode','left');
		  
		 $join_tables = array($join1,$join2,$join3);
		//$join_tables = array();

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by']  = 'pendaftaran_tindakan.id';
		$params['sort_dir'] = 'asc';
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		if($this->session->userdata('level_id') == config_item('user_developer'))
		{
			$this->db->where('pendaftaran_tindakan.cabang_id',$cabang_id);
			$this->db->where('pendaftaran_tindakan.status',1);
			$this->db->where('pendaftaran_tindakan.status_verif',3);
		}
		else
		{
			$this->db->where('pendaftaran_tindakan.cabang_id',$cabang_id);
			$this->db->where('dokter_id',$this->session->userdata('user_id'));
			$this->db->where('pendaftaran_tindakan.status',1);
			$this->db->where('pendaftaran_tindakan.status_verif',3);
			
		}
		 
		 
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		if($this->session->userdata('level_id') == config_item('user_developer'))
		{
			$this->db->where('pendaftaran_tindakan.cabang_id',$cabang_id);
			$this->db->where('pendaftaran_tindakan.status',1);
			$this->db->where('pendaftaran_tindakan.status_verif',3);
		}
		else
		{
			$this->db->where('pendaftaran_tindakan.cabang_id',$cabang_id);
			$this->db->where('dokter_id',$this->session->userdata('user_id'));
			$this->db->where('pendaftaran_tindakan.status',1);
			$this->db->where('pendaftaran_tindakan.status_verif',3);
			
		}
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		if($this->session->userdata('level_id') == config_item('user_developer'))
		{
			$this->db->where('pendaftaran_tindakan.cabang_id',$cabang_id);
			$this->db->where('pendaftaran_tindakan.status',1);
			$this->db->where('pendaftaran_tindakan.status_verif',3);
		}
		else
		{
			$this->db->where('pendaftaran_tindakan.cabang_id',$cabang_id);
			$this->db->where('dokter_id',$this->session->userdata('user_id'));
			$this->db->where('pendaftaran_tindakan.status',1);
			$this->db->where('pendaftaran_tindakan.status_verif',3);
			
		}
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

	public function get_params()
	{
		$params = $this->datatable_param($this->datatable_columns);

		return $params;
	}

	public function getdatatindakan($id){
		$sql= "select a.created_date,b.nama,a.pasien_id from pendaftaran_tindakan a join user b on a.dokter_id=b.id where a.id=?";

		return $this->db->query($sql,$id);
	}

	public function getdatatindakanfrekuensi($startdate,$enddate,$pasienid){
		$sql= "select count(*) as counts from tindakan_hd where pasien_id=".$pasienid." and tanggal between '".$startdate."' and '".$enddate."' AND status = 3";

		return $this->db->query($sql);
	}

	public function getdatapasien($id){
		$sql= "select b.tanggal_registrasi,b.berat_badan_kering, b.nama,b.jangka_waktu, c.alamat,(CASE b.gender when 'L' THEN 'Laki-laki' ELSE 'Perempuan' END) as gender,b.tanggal_lahir,DATEDIFF(now(),tanggal_lahir) AS usia,b.no_member, b.url_photo as url_photo from pendaftaran_tindakan a join pasien b on a.pasien_id=b.id left join pasien_alamat c on c.pasien_id=b.id where a.id=?";

		return $this->db->query($sql,$id);
	}

	public function getdatapasien2($id){
		$sql= "select b.nama,c.alamat,b.gender,b.tanggal_lahir, b.tanggal_daftar, DATEDIFF(now(),tanggal_lahir) AS usia,b.no_member,b.keterangan,d.nama as nama1,e.nama as nama2,b.ref_kode_cabang as kodecabang,ref_kode_rs_rujukan as rujukan,ref_tanggal_rujukan as tgglrujukan,ref_nomor_rujukan as nomorrujukan,b.dokter_pengirim,b.tempat_lahir from pendaftaran_tindakan a join pasien b on a.pasien_id=b.id left join pasien_alamat c on c.pasien_id=b.id left join (select nama,id from info_umum) d on d.id=b.agama_id left join (select nama,id from info_umum) e on e.id=b.golongan_darah_id where a.id=?";

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
		$sql= "SELECT inf_lokasi.nama_kelurahan AS kelurahan, inf_lokasi.nama_kecamatan AS kecamatan, inf_lokasi.nama_kabupatenkota AS kota FROM inf_lokasi WHERE lokasi_kode = (SELECT kode_lokasi FROM pasien_alamat WHERE pasien_id = (SELECT pasien_id FROM pendaftaran_tindakan WHERE id = ? ) AND is_primary = 1)";

		return $this->db->query($sql,$id);
	}
	 public function getdatapasienpenyakit($id){
		$sql= "select tipe,c.nama from pendaftaran_tindakan a join pasien b on a.pasien_id=b.id left join (select pasien_id,penyakit_id,tipe,nama from pasien_penyakit left join penyakit on penyakit.id=pasien_penyakit.penyakit_id where pasien_penyakit.is_active=1 and penyakit.is_active=1) c on c.pasien_id=b.id where a.id=?";

		return $this->db->query($sql,$id);
	}


	public function getdatatindakan2($id){
		$sql= "select a.created_date,b.nama,a.pasien_id,a.berat_awal,a.jangka_waktu from tindakan_hd a join user b on a.dokter_id=b.id where a.id=?";

		return $this->db->query($sql,$id);
	}

	public function getdatatindakanfrekuensi2($startdate,$enddate,$pasienid){
		$sql= "select count(*) as counts from tindakan_hd where pasien_id=".$pasienid." and tanggal between '".$startdate."' and '".$enddate."'";

		return $this->db->query($sql);
	}

	public function getlasttransaksi($pasien_id)
	{
		$sql = "SELECT * FROM tindakan_hd WHERE pasien_id = $pasien_id AND status=3 ORDER BY tanggal DESC LIMIT 1";
		return $this->db->query($sql);
	}

	public function getdatapasien21($id){
		$sql= "select b.tanggal_registrasi,b.nama, b.berat_badan_kering as berat_badan_kering, c.alamat,(CASE b.gender when 'L' THEN 'Laki-laki' ELSE 'Perempuan' END) as gender,b.tanggal_lahir,DATEDIFF(now(),tanggal_lahir) AS usia,b.no_member from tindakan_hd a join pasien b on a.pasien_id=b.id left join pasien_alamat c on c.pasien_id=b.id where a.id=?";

		return $this->db->query($sql,$id);
	}

	public function getdatapasien22($id){
		$sql= "select b.nama,c.alamat,b.gender,b.tanggal_lahir,DATEDIFF(now(),tanggal_lahir) AS usia,b.no_member,b.keterangan,d.nama as nama1,e.nama as nama2,b.ref_kode_cabang as kodecabang,ref_kode_rs_rujukan as rujukan,ref_tanggal_rujukan as tgglrujukan,ref_nomor_rujukan as nomorrujukan,b.dokter_pengirim,b.tempat_lahir from tindakan_hd a join pasien b on a.pasien_id=b.id left join pasien_alamat c on c.pasien_id=b.id left join (select nama,id from info_umum) d on d.id=b.agama_id left join (select nama,id from info_umum) e on e.id=b.golongan_darah_id where a.id=?";

		return $this->db->query($sql,$id);
	}

	public function getdatapasienphone21($id){
		$sql= "select nomor from tindakan_hd a join pasien b on a.pasien_id=b.id left join (select nomor,pasien_id,is_primary,is_active from pasien_telepon where is_primary=1 and is_active=1)  c on c.pasien_id=b.id where a.id=?";

		return $this->db->query($sql,$id);
	}

	public function getdatapasienphone22($id){
		$sql= "select c.nama,nomor from tindakan_hd a join pasien b on a.pasien_id=b.id left join (select nomor,pasien_id,is_primary,is_active,subjek.nama from pasien_telepon left join subjek on subjek.id=pasien_telepon.subjek_id where is_primary=1 and is_active=1 and tipe=2)  c on c.pasien_id=b.id where a.id=?";

		return $this->db->query($sql,$id);
	}

	public function getdatapasienalamat21($id){
		$sql= "select c.nama,alamat,rt_rw from tindakan_hd a join pasien b on a.pasien_id=b.id left join (select alamat,pasien_id,is_primary,is_active,subjek.nama,rt_rw from pasien_alamat left join subjek on subjek.id=pasien_alamat.subjek_id where is_primary=1 and is_active=1 and tipe=1)  c on c.pasien_id=b.id where a.id=?";

		return $this->db->query($sql,$id);
	}
	public function getdatapasienalamat22($id){
		$sql= "select bb.nama as kelurahan,cc.nama as kecamatan,dd.nama as kota from(select c.nama,alamat,rt_rw,kota_id,kecamatan_id,kelurahan_id from tindakan_hd a join pasien b on a.pasien_id=b.id left join (select alamat,pasien_id,is_primary,is_active,subjek.nama,rt_rw,kota_id,kecamatan_id,kelurahan_id from pasien_alamat left join subjek on subjek.id=pasien_alamat.subjek_id where is_primary=1 and is_active=1 and tipe=1)  c on c.pasien_id=b.id where a.id=?) aa left join (select nama,id from region where tipe=4) bb on bb.id=aa.kecamatan_id left join (select nama,id from region where tipe=5) cc on cc.id=aa.kelurahan_id left join (select nama,id from region where tipe=3) dd on dd.id=aa.kota_id";

		return $this->db->query($sql,$id);
	}
	 public function getdatapasienpenyakit2($id){
		$sql= "select tipe,c.nama from tindakan_hd a join pasien b on a.pasien_id=b.id left join (select pasien_id,penyakit_id,tipe,nama from pasien_penyakit left join penyakit on penyakit.id=pasien_penyakit.penyakit_id where pasien_penyakit.is_active=1 and penyakit.is_active=1) c on c.pasien_id=b.id where a.id=?";

		return $this->db->query($sql,$id);
	}


	 public function loadpaket($id){
		$sql= "select a.id,a.paket_id,b.nama,b.harga_total from tindakan_hd_paket a  join paket b on a.paket_id=b.id where tindakan_hd_id=?";

		return $this->db->query($sql,$id);
	}

	public function loadresep($id,$pasienid){
		$sql= "select d.kode,d.nama,a.id,a.tipe_item,a.jumlah,a.dosis,a.satuan_id,a.item_id from tindakan_resep_obat a join (select b.id,kode,b.nama,'1' as tipe from item b join item_satuan c on c.item_id=b.id group by id union all select id,null as kode,nama,'2' as tipe from resep_obat_racikan ) d on d.id=a.item_id where a.tindakan_id=".$id." and a.pasien_id=".$pasienid." and a.tipe_tindakan=1 and a.is_active=1 and d.tipe=a.tipe_item";

		return $this->db->query($sql);
	}

	public function loadresepmanual($id,$pasienid){
		$sql= "select a.id,a.keterangan from tindakan_resep_obat_manual a  where a.tindakan_id='".$id."' and a.pasien_id='".$pasienid."' and tipe_tindakan=1 and is_active=1";

		return $this->db->query($sql);
	}

	public function loadklaim($id,$pasienid){
		$sql= "select * from pasien_klaim a where transaksi_id=".$id." and pasien_id=".$pasienid." and is_active=1";

		return $this->db->query($sql);
	}

	public function loadresepobat($item_id,$satuan_id){
		$sql= "select a.nama as namaitem,a.kode as kode,b.nama as namasatuan  from item a join item_satuan b on b.item_id=a.id where a.id='".$item_id."' and b.id='".$satuan_id."'";

		return $this->db->query($sql);
	}
	public function loadresepracikan($item_id){
		$sql= "select  nama from resep_racik_obat where id=".$item_id;

		return $this->db->query($sql);
	}

	public function getreseptemp($item_id){
		$sql= "select  e.id as id_racikan,b.id,b.kode,b.nama as namaitem,a.jumlah,c.nama as namasatuan from resep_obat_racikan e join resep_obat_racikan_detail a on e.id=a.resep_racik_obat_id join item b on b.id=a.item_id join item_satuan c on c.id=a.item_satuan_id where a.resep_racik_obat_id=".$item_id." and e.is_active=1";

		return $this->db->query($sql);
	}

	public function getresepmanualtemp($item_id){
		$sql= "select  * from  resep_obat_racikan_detail_manual a  where a.resep_obat_racikan_id=".$item_id." and is_active=1";

		return $this->db->query($sql);
	}

	public function getdatapenaksiran($id){
		$sql= "select * from tindakan_hd_penaksiran where tindakan_hd_id=?";

		return $this->db->query($sql,$id);
	}



}

/* End of file cabang_m.php */
/* Location: ./application/models/master/cabang_m.php */