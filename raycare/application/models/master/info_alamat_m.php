<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Info_alamat_m extends MY_Model {

	protected $_table        = 'inf_lokasi';
	protected $_order_by     = 'lokasi_kode';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		'lokasi_kode',
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
		'lokasi_kode' => 'kode',
		'nama_kelurahan' => 'kelurahan',
		'nama_kecamatan'=> 'kecamatan',
		'nama_kabupatenkota' =>'kotkab',
		'nama_propinsi' => 'propinsi'
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
		$this->db->where("nama_propinsi != ''");			
		$this->db->where("nama_kabupatenkota != ''");			
		$this->db->where("nama_kecamatan != ''");			
		$this->db->where("nama_kelurahan != ''");			
		
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where("nama_propinsi != ''");
		$this->db->where("nama_kabupatenkota != ''");
		$this->db->where("nama_kecamatan != ''");
		$this->db->where("nama_kelurahan != ''");
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where("nama_propinsi != ''");
		$this->db->where("nama_kabupatenkota != ''");
		$this->db->where("nama_kecamatan != ''");
		$this->db->where("nama_kelurahan != ''");

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
	public function get_by_nama($nama_lokasi)
	{
		$SQL = "SELECT
			kel.lokasi_kode AS kode,
			kel.lokasi_nama AS kelurahan, (
				SELECT
					kec.lokasi_nama
				FROM
					inf_lokasi kec
				WHERE
					RIGHT (kec.lokasi_kode, 4) = '0000'
				AND LEFT(kec.lokasi_kode,8) = CONCAT_WS(
					'.',
					kel.lokasi_propinsi,
					kel.lokasi_kabupatenkota,
					kel.lokasi_kecamatan)
		)AS kecamatan,
		(
				SELECT
					kotkab.lokasi_nama
				FROM
					inf_lokasi kotkab
				WHERE
					RIGHT (kotkab.lokasi_kode, 7) = '00.0000'
				AND LEFT(kotkab.lokasi_kode,5) = CONCAT_WS(
					'.',
					kel.lokasi_propinsi,
					kel.lokasi_kabupatenkota)
		)AS kotkab,
		(
				SELECT
					prop.lokasi_nama
				FROM
					inf_lokasi prop
				WHERE
					RIGHT (prop.lokasi_kode, 10) = '00.00.0000'
				AND LEFT(prop.lokasi_kode,2) = kel.lokasi_propinsi
		)AS propinsi
				FROM
					`inf_lokasi` kel
				WHERE
					RIGHT (kel.lokasi_kode, 4) != '0000'
				AND kel.lokasi_nama LIKE '%$nama_lokasi%'";

		return $this->db->query($SQL);
	}

}

/* End of file info_alamat_m.php */
/* Location: ./application/models/master/info_alamat_m.php */