<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sep_tindakan_m extends MY_Model {

	protected $_table        = 'sep_tindakan';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
			
	);

	private $_fillable_edit = array(
		
	);

	protected $datatable_columns = array(
		//column di table          => alias
		'tindakan_hd.id'                      => 'id', 
		'tindakan_hd.pendaftaran_tindakan_id' => 'pendaftaran_tindakan_id', 
		'tindakan_hd.no_transaksi'            => 'no_transaksi', 
		'tindakan_hd.tanggal'                 => 'tanggal', 
		'pasien.url_photo'                    => 'url_photo',
		'pasien.id'                           => 'id_pasien',
		'pasien.nama'                         => 'nama_pasien',
		'penjamin.nama'                       => 'nama_penjamin',
		'pasien_penjamin.no_kartu'            => 'no_kartu',
		'user.nama'                           => 'nama_dokter',
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
		$datatable_columns = array(
		//column di table          => alias
			'tindakan_hd.id'                      => 'id', 
			'tindakan_hd.pendaftaran_tindakan_id' => 'pendaftaran_tindakan_id', 
			'tindakan_hd.no_transaksi'            => 'no_transaksi', 
			'tindakan_hd.status'            	  => 'status', 
			'pasien.url_photo'                    => 'url_photo',
			'pasien.id'                           => 'id_pasien',
			'pasien.nama'                         => 'nama_pasien',
			'pasien.no_member'                         => 'no_member',
			'penjamin.nama'                       => 'nama_penjamin',
			'pasien_penjamin.no_kartu'            => 'no_kartu',
			'(SELECT user.nama FROM user WHERE user.id = tindakan_hd.dokter_id)'  => 'nama_dokter',
			'(SELECT user.nama FROM user WHERE user.id = sep_tindakan.created_by)'  => 'dibuat_oleh',
			'sep_tindakan.no_sep'				  => 'no_sep',
			'sep_tindakan.tanggal_sep'			  => 'tanggal_sep',
			'sep_tindakan.is_active'			  => 'is_active',
			'sep_tindakan.id'					  => 'id_sep'
		);

		$join1 = array('tindakan_hd', $this->_table.'.tindakan_id = tindakan_hd.id');
		$join2 = array('pasien_penjamin', 'tindakan_hd.pasien_id = pasien_penjamin.pasien_id AND tindakan_hd.penjamin_id = pasien_penjamin.penjamin_id');
		$join3 = array('pasien',$this->_table . '.pasien_id = pasien.id');
		$join4 = array('penjamin','tindakan_hd.penjamin_id = penjamin.id');
		$join_tables = array($join1, $join2, $join3, $join4);


		// get params dari input postnya datatable
		$params = $this->datatable_param($datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('tindakan_hd.status != 5 AND sep_tindakan.is_active = 1 AND sep_tindakan.tipe_tindakan = 1 AND pasien_penjamin.is_active = 1 OR tindakan_hd.status =5 AND sep_tindakan.is_active = 1 AND sep_tindakan.tipe_tindakan = 1 AND pasien_penjamin.is_active = 1 OR tindakan_hd.status =5 AND sep_tindakan.is_active = 0 AND sep_tindakan.tipe_tindakan = 1 AND pasien_penjamin.is_active = 1');
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('tindakan_hd.status != 5 AND sep_tindakan.is_active = 1 AND sep_tindakan.tipe_tindakan = 1 AND pasien_penjamin.is_active = 1 OR tindakan_hd.status =5 AND sep_tindakan.is_active = 1 AND sep_tindakan.tipe_tindakan = 1 AND pasien_penjamin.is_active = 1 OR tindakan_hd.status =5 AND sep_tindakan.is_active = 0 AND sep_tindakan.tipe_tindakan = 1 AND pasien_penjamin.is_active = 1');
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('tindakan_hd.status != 5 AND sep_tindakan.is_active = 1 AND sep_tindakan.tipe_tindakan = 1 AND pasien_penjamin.is_active = 1 OR tindakan_hd.status =5 AND sep_tindakan.is_active = 1 AND sep_tindakan.tipe_tindakan = 1 AND pasien_penjamin.is_active = 1 OR tindakan_hd.status =5 AND sep_tindakan.is_active = 0 AND sep_tindakan.tipe_tindakan = 1 AND pasien_penjamin.is_active = 1');

		// tentukan kolom yang mau diselect
		foreach ($datatable_columns as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $datatable_columns;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
  // die(dump($result->records));
		return $result; 
	}

	public function get_data_detail($tindakan_hd_id)
	{
		$format = "SELECT
					sep_tindakan.*, pasien.nama AS nama_pasien,
					pasien.tanggal_lahir,
					pasien.gender,
					icd_code.code_ast,
					icd_code.`name` AS nama_diagnosa,
					pasien.ref_kode_rs_rujukan,
					pasien_penjamin.no_kartu
				FROM
					sep_tindakan
				LEFT JOIN tindakan_hd ON sep_tindakan.tindakan_id = tindakan_hd.id
				LEFT JOIN pasien ON sep_tindakan.pasien_id = pasien.id
				LEFT JOIN icd_code ON sep_tindakan.diagnosa_awal = icd_code.code_ast
				LEFT JOIN pasien_penjamin ON sep_tindakan.pasien_id = pasien_penjamin.pasien_id
				WHERE
					sep_tindakan.tindakan_id = $tindakan_hd_id
				AND pasien_penjamin.penjamin_id = tindakan_hd.penjamin_id
				AND sep_tindakan.tipe_tindakan = 1
				AND pasien_penjamin.is_active = 1";

		return $this->db->query($format);
	}
}
