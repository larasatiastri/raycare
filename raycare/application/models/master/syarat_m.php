<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Syarat_m extends MY_Model {

	protected $_table        = 'syarat';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;
///===============Raymond==controller:klaim_asuransi===JANGAN DIRUBAH===============
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
		'syarat.judul'         => 'judul', 
	 	'syarat.id'     => 'id',
	 	'syarat.tipe'     => 'tipe',
	 	'syarat.maksimal_karakter'     => 'maksimal_karakter'
		
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

		// $join1 = array('poliklinik_tindakan', $this->_table . '.id = poliklinik_tindakan.tindakan_id');
		// $join2 = array('poliklinik', 'poliklinik.id = poliklinik_tindakan.poliklinik_id');
		  
		 $join_tables = array();
		//$join_tables = array();

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

	public function syarat($id){
		$sql= "select c.tipe,c.judul,c.maksimal_karakter,c.id from penjamin a join penjamin_syarat b on a.id=b.penjamin_id join syarat c on c.id=b.syarat_id where a.id=?";

		return $this->db->query($sql,$id);
	}

	public function syarat2($id1,$id2){
		$sql= "select c.tipe,c.judul,c.maksimal_karakter,c.id from penjamin a join penjamin_syarat b on a.id=b.penjamin_id join syarat c on c.id=b.syarat_id where a.id=".$id1." and b.syarat_id=".$id2;

		return $this->db->query($sql);
	}

	public function scan($id){
		$sql= "select * from penjamin_scan_dokumen a where a.is_active=1 and a.penjamin_id=?";

		return $this->db->query($sql,$id);
	}

	public function deletesyarat($id){
		$sql= "delete from syarat_detail where syarat_id in (select syarat_id from penjamin_syarat where penjamin_id=?)";
		$sql2= "delete from syarat where id in (select syarat_id from penjamin_syarat where penjamin_id=?)";
		$sql3= "delete from penjamin_syarat where penjamin_id=?";
		$sql4= "delete from penjamin_scan_dokumen where penjamin_id=?";

		$this->db->query($sql,$id);
		$this->db->query($sql2,$id);
		$this->db->query($sql3,$id);
		$this->db->query($sql4,$id);
	}

	public function update_penjamin_id_kelompok($id){
		 
		$sql= "update penjamin_kelompok set penjamin_id=null where penjamin_id=?";

		$this->db->query($sql,$id);
		 
	}

	public function update_syarat_id($id1,$id2){
		 
		$sql= "update penjamin_syarat set syarat_id=".$id1." where syarat_id=".$id2;

		$this->db->query($sql);
		 
	}


	 

}

/* End of file cabang_m.php */
/* Location: ./application/models/master/cabang_m.php */