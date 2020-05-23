<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tindakan_hd_paket_m extends MY_Model {

	protected $_table        = 'tindakan_hd_paket';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
			
	);

	private $_fillable_edit = array(
		
	);

	protected $datatable_columns = array(
		//column di table  => alias
		'bed.nama'         		 => 'nama', 
		'bed.tipe'   			 => 'tipe', 
		'bed.created_date'   	 => 'tggl', 
		 


		
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
 
	 	 
		$join1 = array('pasien_penjamin b',$this->_table . '.id = b.pasien_id');
		$join_tables = array($join1);

		

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

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

	function get_data_id($id_bed)
	{	
		$format = "SELECT * FROM tindakan_hd WHERE bed_id = '".$id_bed."' AND LEFT(tanggal, 10) = '".date('Y-m-d')."' ";	
		return $this->db->query($format)->result();
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

	public function get_nomor_po($cabang)
	{
		$format = "SELECT MAX(SUBSTRING(`no_transaksi`,11,4)) AS max_nomor_po FROM `tindakan_hd` WHERE SUBSTRING(`no_transaksi`,6,4) = DATE_FORMAT(NOW(), '%y%m') and SUBSTRING(`no_transaksi`,2,1)=".$cabang;	
		return $this->db->query($format);
	}

	public function detail_histori_kiri($id,$pasien_id)
	{
		// $format = "select * from tindakan_hd a join pasien b on b.id=a.pasien_id join (select tanggal,pasien_id from tindakan_hd_penaksiran  group by tanggal desc limit 1) c on c.pasien_id=a.pasien_id where a.id=".$id;
		$format = "select a.no_transaksi,b.no_member,b.nama,c.tanggal,a.keterangan,c.tindakan_id from tindakan_hd a left join pasien b on b.id=a.pasien_id left join (select f.tanggal,f.pasien_id,g.tindakan_id from tindakan_hd_penaksiran  f join rm_tindakan_pasien g on f.tindakan_hd_id=g.tindakan_id where  f.pasien_id=".$pasien_id." and tindakan_hd_id in (select b.id from rm_tindakan_pasien a join tindakan_hd b on a.tindakan_id=b.id where a.pasien_id=".$pasien_id.") order by f.tanggal desc limit 1) c on c.pasien_id=a.pasien_id where a.id=".$id;		
		return $this->db->query($format);
	}
	public function detail_histori_kanan($id)
	{
		$format = "select b.nama,a.berat_awal,a.berat_akhir,f.nama as nama_poli,d.kode,e.nama as nama_penjamin from tindakan_hd a left join user b on b.id=a.dokter_id left join  rujukan c on c.tindakan_id=a.id left join bed d on d.id=a.bed_id left join penjamin e on e.id=a.penjamin_id left join poliklinik f on f.id=c.poliklinik_asal_id where a.id=?";
		return $this->db->query($format,$id);
	}

}

/* End of file Item_m.php */
/* Location: ./application/models/master/cabang_m.php */