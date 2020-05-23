<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pendaftaran_tindakan_m extends MY_Model {

	protected $_table      = 'pendaftaran_tindakan';
	protected $_timestamps = true;
	protected $_order_by   = 'id';

	private $_fillable = array(
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'pendaftaran_tindakan.id'            => 'id',
		'pendaftaran_tindakan.shift'            => 'shift',
		'pendaftaran_tindakan.cabang_id'     => 'cabang_id',
		'pendaftaran_tindakan.poliklinik_id' => 'poliklinik_id',
		'pendaftaran_tindakan.dokter_id'     => 'dokter_id',
		'pendaftaran_tindakan.pasien_id'     => 'pasien_id',
		'pendaftaran_tindakan.penjamin_id'   => 'penjamin_id',
		'pendaftaran_tindakan.`status`'      => 'status',
		'pendaftaran_tindakan.status_verif'  => 'status_verif',
		'pendaftaran_tindakan.created_date'  => 'tanggal',
		'poliklinik.nama'                    => 'nama_poliklinik',
		'`user`.nama'                        => 'nama_user',
		'pasien.nama'                        => 'nama_pasien',
		'pasien.no_member'					 => 'no_member',
		'pasien.url_photo'                   => 'pasien_photo',
		'penjamin.nama'                      => 'nama_penjamin',
		'pasien_penjamin.id'          		 => 'pasien_penjamin_id',
		'pasien_penjamin.no_kartu'           => 'no_kartu',
		'pasien_penjamin.is_active'			 => 'is_active',
	);

	protected $datatable_columns_history = array(
		//column di table  => alias
		'pendaftaran_tindakan.id'            => 'id',
		'pendaftaran_tindakan.shift'            => 'shift',
		'pendaftaran_tindakan.cabang_id'     => 'cabang_id',
		'pendaftaran_tindakan.poliklinik_id' => 'poliklinik_id',
		'pendaftaran_tindakan.dokter_id'     => 'dokter_id',
		'pendaftaran_tindakan.pasien_id'     => 'pasien_id',
		'pendaftaran_tindakan.penjamin_id'   => 'penjamin_id',
		'pendaftaran_tindakan.`status`'      => 'status',
		'pendaftaran_tindakan.status_verif'  => 'status_verif',
		'pendaftaran_tindakan.tanggal_verif'  => 'tanggal_verif',
		'pendaftaran_tindakan.created_date'  => 'tanggal',
		'poliklinik.nama'                    => 'nama_poliklinik',
		'`user`.nama'                        => 'nama_user',
		'pasien.nama'                        => 'nama_pasien',
		'pasien.no_member'					 => 'no_member',
		'pasien.url_photo'                   => 'pasien_photo',
		'penjamin.nama'                      => 'nama_penjamin',
		'pasien_penjamin.id'          		 => 'pasien_penjamin_id',
		'pasien_penjamin.no_kartu'           => 'no_kartu',
		'pasien_penjamin.is_active'			 => 'is_active',
		'user_verif.nama'					 => 'user_verif',

	);

	function __construct()
	{
		parent::__construct();
	}

		/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable()
	{
		$join1 = array('poliklinik', 'pendaftaran_tindakan.poliklinik_id = poliklinik.id', 'left');
		$join2 = array('`user`', 'pendaftaran_tindakan.created_by = `user`.id', 'left');
		$join3 = array('pasien', 'pendaftaran_tindakan.pasien_id = pasien.id', 'left');
		$join4 = array('penjamin', 'pendaftaran_tindakan.penjamin_id = penjamin.id', 'left');
		$join5 = array('pasien_penjamin', 'pendaftaran_tindakan.pasien_id = pasien_penjamin.pasien_id AND pendaftaran_tindakan.penjamin_id = pasien_penjamin.penjamin_id', 'left');

		$join_tables = array($join1, $join2, $join3, $join4, $join5);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = $this->_table.'.shift asc, antrian'; 
		$params['sort_dir'] = 'asc'; 

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.`status`', 4);
		$this->db->where($this->_table.'.status_verif', 1);
		$this->db->where($this->_table.'.penjamin_id !=', 1);
		$this->db->where('pasien_penjamin.is_active', 1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.`status`', 4);
		$this->db->where($this->_table.'.status_verif', 1);
		$this->db->where($this->_table.'.penjamin_id !=', 1);
		$this->db->where('pasien_penjamin.is_active', 1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.`status`', 4);
		$this->db->where($this->_table.'.status_verif', 1);
		$this->db->where($this->_table.'.penjamin_id !=', 1);
		$this->db->where('pasien_penjamin.is_active', 1);

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

		return $result; 
	}

	public function get_datatable_history()
	{
		$join1 = array('poliklinik', 'pendaftaran_tindakan.poliklinik_id = poliklinik.id', 'left');
		$join2 = array('`user`', 'pendaftaran_tindakan.created_by = `user`.id', 'left');
		$join3 = array('pasien', 'pendaftaran_tindakan.pasien_id = pasien.id', 'left');
		$join4 = array('penjamin', 'pendaftaran_tindakan.penjamin_id = penjamin.id', 'left');
		$join5 = array('pasien_penjamin', 'pendaftaran_tindakan.pasien_id = pasien_penjamin.pasien_id AND pendaftaran_tindakan.penjamin_id = pasien_penjamin.penjamin_id', 'left');
		$join6 = array('`user`as user_verif', 'pendaftaran_tindakan.user_verif_id = user_verif.id', 'left');

		$join_tables = array($join1, $join2, $join3, $join4, $join5, $join6);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_history);
		$params['sort_by'] = 'pendaftaran_tindakan.tanggal_verif';		
		$params['sort_dir'] = 'desc';		
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.`status`', 4);
		$this->db->where($this->_table.'.status_verif', 2);
		$this->db->where($this->_table.'.penjamin_id !=', 1);
		$this->db->where('pasien_penjamin.is_active', 1);

		$this->db->or_where($this->_table.'.`status`', 4);
		$this->db->where($this->_table.'.status_verif', 3);
		$this->db->where($this->_table.'.penjamin_id !=', 1);
		$this->db->where('pasien_penjamin.is_active', 1);

		$this->db->or_where($this->_table.'.`status`', 1);
		$this->db->where($this->_table.'.status_verif', 2);
		$this->db->where($this->_table.'.penjamin_id !=', 1);
		$this->db->where('pasien_penjamin.is_active', 1);
		
		$this->db->or_where($this->_table.'.`status`', 2);
		$this->db->where($this->_table.'.status_verif', 2);
		$this->db->where($this->_table.'.penjamin_id !=', 1);
		$this->db->where('pasien_penjamin.is_active', 1);

		$this->db->or_where($this->_table.'.`status`', 3);
		$this->db->where($this->_table.'.status_verif', 2);
		$this->db->where($this->_table.'.penjamin_id !=', 1);
		$this->db->where('pasien_penjamin.is_active', 1);

		/*$this->db->or_where($this->_table.'.`status`', 0);
		$this->db->where($this->_table.'.status_verif', 2);
		$this->db->where($this->_table.'.penjamin_id !=', 1);
		$this->db->where('pasien_penjamin.is_active', 1);*/
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.`status`', 4);
		$this->db->where($this->_table.'.status_verif', 2);
		$this->db->where($this->_table.'.penjamin_id !=', 1);
		$this->db->where('pasien_penjamin.is_active', 1);

		$this->db->or_where($this->_table.'.`status`', 4);
		$this->db->where($this->_table.'.status_verif', 3);
		$this->db->where($this->_table.'.penjamin_id !=', 1);
		$this->db->where('pasien_penjamin.is_active', 1);

		$this->db->or_where($this->_table.'.`status`', 1);
		$this->db->where($this->_table.'.status_verif', 2);
		$this->db->where($this->_table.'.penjamin_id !=', 1);
		$this->db->where('pasien_penjamin.is_active', 1);

		$this->db->or_where($this->_table.'.`status`', 2);
		$this->db->where($this->_table.'.status_verif', 2);
		$this->db->where($this->_table.'.penjamin_id !=', 1);
		$this->db->where('pasien_penjamin.is_active', 1);

		$this->db->or_where($this->_table.'.`status`', 3);
		$this->db->where($this->_table.'.status_verif', 2);
		$this->db->where($this->_table.'.penjamin_id !=', 1);
		$this->db->where('pasien_penjamin.is_active', 1);

		/*$this->db->or_where($this->_table.'.`status`', 0);
		$this->db->where($this->_table.'.status_verif', 2);
		$this->db->where($this->_table.'.penjamin_id !=', 1);
		$this->db->where('pasien_penjamin.is_active', 1);*/
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.`status`', 4);
		$this->db->where($this->_table.'.status_verif', 2);
		$this->db->where($this->_table.'.penjamin_id !=', 1);
		$this->db->where('pasien_penjamin.is_active', 1);

		$this->db->or_where($this->_table.'.`status`', 4);
		$this->db->where($this->_table.'.status_verif', 3);
		$this->db->where($this->_table.'.penjamin_id !=', 1);
		$this->db->where('pasien_penjamin.is_active', 1);

		$this->db->or_where($this->_table.'.`status`', 1);
		$this->db->where($this->_table.'.status_verif', 2);
		$this->db->where($this->_table.'.penjamin_id !=', 1);
		$this->db->where('pasien_penjamin.is_active', 1);

		$this->db->or_where($this->_table.'.`status`', 2);
		$this->db->where($this->_table.'.status_verif', 2);
		$this->db->where($this->_table.'.penjamin_id !=', 1);
		$this->db->where('pasien_penjamin.is_active', 1);

		$this->db->or_where($this->_table.'.`status`', 3);
		$this->db->where($this->_table.'.status_verif', 2);
		$this->db->where($this->_table.'.penjamin_id !=', 1);
		$this->db->where('pasien_penjamin.is_active', 1);

		/*$this->db->or_where($this->_table.'.`status`', 0);
		$this->db->where($this->_table.'.status_verif', 2);
		$this->db->where($this->_table.'.penjamin_id !=', 1);
		$this->db->where('pasien_penjamin.is_active', 1);*/

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_history as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_history;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 

		return $result; 
	}
		/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable_active()
	{

		$join_tables = array();

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('is_active',1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('is_active',1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('is_active',1);

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
		
		return $result; 
	}

	/**
	 * [fillable_add description]
	 * @return [type] [description]
	 */
	public function fillable()
	{
		return $this->_fillable;
	}

	public function get_data_is_active()
    {
        $this->db->where("is_active", 1);
        return $this->db->get($this->_table);
    }
}

/* End of file pendaftaran_tindakan_m.php */
/* Location: ./application/models/pendaftaran_tindakan_m.php */