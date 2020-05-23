<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permintaan_biaya_m extends MY_Model {

	protected $_table        = 'permintaan_biaya';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'permintaan_biaya.id'              => 'id', 
		'permintaan_biaya.diminta_oleh_id' => 'diminta_oleh_id',
		'permintaan_biaya.tipe'            => 'tipe',
		'permintaan_biaya.tanggal'         => 'tanggal',
		'permintaan_biaya.nominal'         => 'nominal',
		'permintaan_biaya.keperluan'       => 'keperluan',
		'permintaan_biaya.`status`'        => '`status`',
		'permintaan_biaya.`status_revisi`' => '`status_revisi`',
		'permintaan_biaya.`status_proses`' => '`status_proses`',
		'permintaan_biaya.is_active'       => 'is_active',
		'user.nama'                        => 'nama_dibuat_oleh',
	);

	protected $datatable_columns_report = array(
		//column di table  => alias
		'permintaan_biaya.id'              => 'id', 
		'permintaan_biaya.diminta_oleh_id' => 'diminta_oleh_id',
		'permintaan_biaya.tipe'            => 'tipe',
		'permintaan_biaya.tanggal'         => 'tanggal',
		'sum(permintaan_biaya.nominal)'         => 'nominal',
		'permintaan_biaya.keperluan'       => 'keperluan',
		'permintaan_biaya.is_active'       => 'is_active',
		'user.nama'                        => 'nama_dibuat_oleh',
	);

	protected $datatable_columns_biaya = array(
		//column di table  => alias
		'permintaan_biaya.id'              => 'id', 
		'permintaan_biaya.diminta_oleh_id' => 'diminta_oleh_id',
		'permintaan_biaya.tipe'            => 'tipe',
		'permintaan_biaya.tanggal'         => 'tanggal',
		'sum(permintaan_biaya.nominal)'         => 'nominal',
		'permintaan_biaya.keperluan'       => 'keperluan',
		'permintaan_biaya.is_active'       => 'is_active',
		'user.nama'                        => 'nama_dibuat_oleh',
		'biaya.id'					   => 'biaya_id',
		'biaya.nama'					   => 'nama_biaya',
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
		$join1 = array('`user`', $this->_table.'.diminta_oleh_id = `user`.id', 'LEFT');
		// $join2 = array('permintaan_biaya_cetak', $this->_table.'.id = permintaan_biaya_cetak.permintaan_biaya_id', 'LEFT');
		// $join3 = array('permintaan_biaya_tipe', 'permintaan_biaya_tipe.permintaan_biaya_id = permintaan_biaya.id ', 'LEFT');
		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by']="permintaan_biaya.tanggal";
		$params['sort_dir']="desc";

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('status <= ', 8);
		$this->db->where('status_proses', 0);
		$this->db->where($this->_table.'.is_active', 1);
		if($this->session->userdata('level_id') != 1){
			$this->db->where('`user`.user_level_id', $this->session->userdata('level_id'));
		}
		
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('status <= ', 8);
		$this->db->where('status_proses', 0);
		$this->db->where($this->_table.'.is_active', 1);
		if($this->session->userdata('level_id') != 1){
			$this->db->where('`user`.user_level_id', $this->session->userdata('level_id'));
		}
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();
		$this->db->where('status <= ', 8);
		$this->db->where('status_proses', 0);
		$this->db->where($this->_table.'.is_active', 1);
		if($this->session->userdata('level_id') != 1){
			$this->db->where('`user`.user_level_id', $this->session->userdata('level_id'));
		}
		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);

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
	public function get_datatable_history()
	{	
		$join1 = array('`user`', $this->_table.'.diminta_oleh_id = `user`.id', 'LEFT');
		// $join2 = array('permintaan_biaya_cetak', $this->_table.'.id = permintaan_biaya_cetak.permintaan_biaya_id', 'LEFT');
		// $join3 = array('permintaan_biaya_tipe', 'permintaan_biaya_tipe.permintaan_biaya_id = permintaan_biaya.id ', 'LEFT');
		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by']="permintaan_biaya.tanggal";
		$params['sort_dir']="desc";

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('status <=', 6);
		$this->db->where('status_proses >=', 1);
		$this->db->where($this->_table.'.is_active', 1);
		if($this->session->userdata('level_id') != 1){
			$this->db->where('`user`.user_level_id', $this->session->userdata('level_id'));
		}
		
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();

		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('status <=', 6);
		$this->db->where('status_proses >=', 1);
		$this->db->where($this->_table.'.is_active', 1);
		if($this->session->userdata('level_id') != 1){
			$this->db->where('`user`.user_level_id', $this->session->userdata('level_id'));
		}
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();
		$this->db->where('status <=', 6);
		$this->db->where('status_proses >=', 1);
		$this->db->where($this->_table.'.is_active', 1);
		if($this->session->userdata('level_id') != 1){
			$this->db->where('`user`.user_level_id', $this->session->userdata('level_id'));
		}
		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);

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

	public function get_datatable_persetujuan()
	{	
		$join1 = array('`user`', $this->_table.'.diminta_oleh_id = `user`.id', 'LEFT');
		// $join2 = array('permintaan_biaya_cetak', $this->_table.'.id = permintaan_biaya_cetak.permintaan_biaya_id', 'LEFT');
		// $join3 = array('permintaan_biaya_tipe', 'permintaan_biaya_tipe.permintaan_biaya_id = permintaan_biaya.id ', 'LEFT');
		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('`status`', 1);
		
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('`status`', 1);
		
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('`status`', 1);
		
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);

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

	
	public function get_no_permintaan()
	{
		$format = "SELECT MAX(SUBSTRING(`nomor_permintaan`,5,3)) AS max_no_permintaan FROM `permintaan_biaya` WHERE RIGHT(`nomor_permintaan`,4) = DATE_FORMAT(NOW(), '%Y');";	
		return $this->db->query($format);
	}

	public function get_datatable_per_tanggal($param_month,$param_year,$divisi_id)
	{	
		$join1 = array('`user`', $this->_table.'.diminta_oleh_id = `user`.id');
		$join2 = array('`user_level`', 'user.user_level_id = `user_level`.id');
		$join3 = array('`divisi`', 'user_level.divisi_id = `divisi`.id','left');
		$join_tables = array($join1,$join2,$join3);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_report);
		$params['sort_by'] = 'permintaan_biaya.tanggal';
		$params['sort_dir'] = 'asc';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('MONTH(permintaan_biaya.tanggal)', $param_month);
		$this->db->where('YEAR(permintaan_biaya.tanggal)', $param_year);
		$this->db->where('divisi.id', $divisi_id);
		$this->db->where('permintaan_biaya.`status`', 5);
		$this->db->group_by('permintaan_biaya.tanggal');
		
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total row count;
		$query = $this->db->select('(1)')->get();
		$total_records = $query->num_rows();
		// $total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('MONTH(permintaan_biaya.tanggal)', $param_month);
		$this->db->where('YEAR(permintaan_biaya.tanggal)', $param_year);
		$this->db->where('divisi.id', $divisi_id);
		$this->db->where('permintaan_biaya.`status`', 5);
		$this->db->group_by('permintaan_biaya.tanggal');
		
		
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total record filtered/search;
		$query = $this->db->select('(1)')->get();
		$total_display_records = $query->num_rows();
		// $total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('MONTH(permintaan_biaya.tanggal)', $param_month);
		$this->db->where('YEAR(permintaan_biaya.tanggal)', $param_year);
		$this->db->where('divisi.id', $divisi_id);
		$this->db->where('permintaan_biaya.`status`', 5);
		$this->db->group_by('permintaan_biaya.tanggal');
		
		
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_report as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_report;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_user_name($tanggal,$divisi_id)
	{
		$SQL = "SELECT `user`.nama FROM permintaan_biaya JOIN user ON permintaan_biaya.diminta_oleh_id = `user`.id
				JOIN user_level ON `user`.user_level_id = user_level.id
				LEFT JOIN divisi ON user_level.divisi_id = divisi.id
				WHERE date(permintaan_biaya.tanggal) = '$tanggal'
				AND divisi.id = '$divisi_id'
				AND permintaan_biaya.`status` = 5
				GROUP BY user.id";

		return $this->db->query($SQL);
	}

	public function get_user_name_biaya_kas($biaya,$month,$year,$divisi_id)
	{
		$SQL = "SELECT `user`.nama FROM permintaan_biaya JOIN user ON permintaan_biaya.diminta_oleh_id = `user`.id
				JOIN user_level ON `user`.user_level_id = user_level.id
				LEFT JOIN divisi ON user_level.divisi_id = divisi.id
				WHERE permintaan_biaya.biaya_id = '$biaya'
				AND MONTH (permintaan_biaya.tanggal) = '$month'
				AND YEAR (permintaan_biaya.tanggal) = '$year'
				AND divisi.id = '$divisi_id'
				AND permintaan_biaya.`status` = 5
				AND permintaan_biaya.`tipe` = 1
				GROUP BY user.id";

		return $this->db->query($SQL);
	}

	public function get_user_name_biaya_reimburse($biaya,$month,$year,$divisi_id)
	{
		$SQL = "SELECT `user`.nama FROM permintaan_biaya 
					JOIN permintaan_biaya_bon ON permintaan_biaya.id = `permintaan_biaya_bon`.permintaan_biaya_id
					JOIN user ON permintaan_biaya.diminta_oleh_id = `user`.id
				JOIN user_level ON `user`.user_level_id = user_level.id
				LEFT JOIN divisi ON user_level.divisi_id = divisi.id
				WHERE permintaan_biaya_bon.biaya_id = '$biaya'
				AND MONTH (permintaan_biaya.tanggal) = '$month'
				AND YEAR (permintaan_biaya.tanggal) = '$year'
				AND divisi.id = '$divisi_id'
				AND permintaan_biaya.`status` = 5
				AND permintaan_biaya.`tipe` = 2
				GROUP BY user.id";

		return $this->db->query($SQL);
	}

	public function get_datatable_per_user($param_month,$param_year,$divisi_id)
	{	
		$join1 = array('`user`', $this->_table.'.diminta_oleh_id = `user`.id');
		$join2 = array('`user_level`', 'user.user_level_id = `user_level`.id');
		$join3 = array('`divisi`', 'user_level.divisi_id = `divisi`.id','left');
		$join_tables = array($join1,$join2,$join3);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_report);
		$params['sort_by'] = 'permintaan_biaya.tanggal';
		$params['sort_dir'] = 'asc';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('MONTH(permintaan_biaya.tanggal)', $param_month);
		$this->db->where('YEAR(permintaan_biaya.tanggal)', $param_year);
		$this->db->where('divisi.id', $divisi_id);
		$this->db->where('permintaan_biaya.`status`', 5);
		$this->db->group_by('permintaan_biaya.diminta_oleh_id');
		
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total row count;
		$query = $this->db->select('(1)')->get();
		$total_records = $query->num_rows();
		// $total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('MONTH(permintaan_biaya.tanggal)', $param_month);
		$this->db->where('YEAR(permintaan_biaya.tanggal)', $param_year);
		$this->db->where('divisi.id', $divisi_id);
		$this->db->where('permintaan_biaya.`status`', 5);
		$this->db->group_by('permintaan_biaya.diminta_oleh_id');
		
		
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total record filtered/search;
		$query = $this->db->select('(1)')->get();
		$total_display_records = $query->num_rows();
		// $total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('MONTH(permintaan_biaya.tanggal)', $param_month);
		$this->db->where('YEAR(permintaan_biaya.tanggal)', $param_year);
		$this->db->where('divisi.id', $divisi_id);
		$this->db->where('permintaan_biaya.`status`', 5);
		$this->db->group_by('permintaan_biaya.diminta_oleh_id');
		
		
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_report as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_report;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_datatable_per_biaya_kasbon($param_month,$param_year,$divisi_id)
	{	
		$join1 = array('`biaya`', $this->_table.'.biaya_id = `biaya`.id');
		$join2 = array('`user`', $this->_table.'.diminta_oleh_id = `user`.id');
		$join3 = array('`user_level`', 'user.user_level_id = `user_level`.id');
		$join4 = array('`divisi`', 'user_level.divisi_id = `divisi`.id','left');
		$join_tables = array($join1,$join2,$join3,$join4);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_biaya);
		$params['sort_by'] = 'permintaan_biaya.tanggal';
		$params['sort_dir'] = 'asc';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('MONTH(permintaan_biaya.tanggal)', $param_month);
		$this->db->where('YEAR(permintaan_biaya.tanggal)', $param_year);
		$this->db->where('divisi.id', $divisi_id);
		$this->db->where('permintaan_biaya.`status`', 5);
		$this->db->where('permintaan_biaya.`tipe`', 1);
		$this->db->group_by('permintaan_biaya.biaya_id');
		
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total row count;
		$query = $this->db->select('(1)')->get();
		$total_records = $query->num_rows();
		// $total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('MONTH(permintaan_biaya.tanggal)', $param_month);
		$this->db->where('YEAR(permintaan_biaya.tanggal)', $param_year);
		$this->db->where('divisi.id', $divisi_id);
		$this->db->where('permintaan_biaya.`status`', 5);
		$this->db->where('permintaan_biaya.`tipe`', 1);

		$this->db->group_by('permintaan_biaya.biaya_id');
		
		
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total record filtered/search;
		$query = $this->db->select('(1)')->get();
		$total_display_records = $query->num_rows();
		// $total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('MONTH(permintaan_biaya.tanggal)', $param_month);
		$this->db->where('YEAR(permintaan_biaya.tanggal)', $param_year);
		$this->db->where('divisi.id', $divisi_id);
		$this->db->where('permintaan_biaya.`status`', 5);
		$this->db->where('permintaan_biaya.`tipe`', 1);

		$this->db->group_by('permintaan_biaya.biaya_id');
		
		
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_biaya as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_biaya;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_datatable_per_biaya_reimburse($param_month,$param_year,$divisi_id)
	{	
		$join1 = array('`permintaan_biaya_bon`', $this->_table.'.id = `permintaan_biaya_bon`.permintaan_biaya_id');
		$join2 = array('`biaya`', 'permintaan_biaya_bon.biaya_id = `biaya`.id');
		$join3 = array('`user`', $this->_table.'.diminta_oleh_id = `user`.id');
		$join4 = array('`user_level`', 'user.user_level_id = `user_level`.id');
		$join5 = array('`divisi`', 'user_level.divisi_id = `divisi`.id','left');
		$join_tables = array($join1,$join2,$join3,$join4,$join5);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_biaya);
		$params['sort_by'] = 'permintaan_biaya.tanggal';
		$params['sort_dir'] = 'asc';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('MONTH(permintaan_biaya.tanggal)', $param_month);
		$this->db->where('YEAR(permintaan_biaya.tanggal)', $param_year);
		$this->db->where('divisi.id', $divisi_id);
		$this->db->where('permintaan_biaya.`status`', 5);
		$this->db->where('permintaan_biaya.`tipe`', 2);
		$this->db->group_by('biaya.id');
		
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total row count;
		$query = $this->db->select('(1)')->get();
		$total_records = $query->num_rows();
		// $total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('MONTH(permintaan_biaya.tanggal)', $param_month);
		$this->db->where('YEAR(permintaan_biaya.tanggal)', $param_year);
		$this->db->where('divisi.id', $divisi_id);
		$this->db->where('permintaan_biaya.`status`', 5);
		$this->db->where('permintaan_biaya.`tipe`', 2);

		$this->db->group_by('biaya.id');
		
		
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total record filtered/search;
		$query = $this->db->select('(1)')->get();
		$total_display_records = $query->num_rows();
		// $total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('MONTH(permintaan_biaya.tanggal)', $param_month);
		$this->db->where('YEAR(permintaan_biaya.tanggal)', $param_year);
		$this->db->where('divisi.id', $divisi_id);
		$this->db->where('permintaan_biaya.`status`', 5);
		$this->db->where('permintaan_biaya.`tipe`', 2);

		$this->db->group_by('biaya.id');
		
		
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_biaya as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_biaya;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

}

/* End of file Permintaan_biaya.php */
/* Location: ./application/models/keuangan/Permintaan_biaya.php */