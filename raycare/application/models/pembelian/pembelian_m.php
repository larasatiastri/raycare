<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembelian_m extends MY_Model {

	protected $_table        = 'pembelian';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'pembelian.id'                 => 'id',
		'pembelian.no_pembelian'       => 'no_po',
		'supplier.id'                  => 'id_sup',
		'supplier.nama'                => 'nama_sup',
		'supplier.kode'                => 'kode_sup',
		'pembelian.tanggal_pesan'      => 'tanggal_pesan',
		'pembelian.tanggal_kadaluarsa' => 'tanggal_kadaluarsa',
		'pembelian.grand_total_po' => 'grand_total',
		'pembelian.keterangan'         => 'keterangan',
		'pembelian.diskon'         => 'diskon',
		'pembelian.pph'         => 'pph',
		'pembelian.pembulatan'         => 'pembulatan',
		'pembelian.tipe_pembayaran'         => 'tipe_pembayaran',
		'pembelian.master_tipe_pembayaran_id'         => 'master_tipe_pembayaran_id',
		'pembelian.status'             => 'status',
		'pembelian.status_cancel'             => 'status_cancel',
		'user.nama'						=> 'user',
		'pembelian.is_kirim'             => 'is_kirim',
	);

	protected $datatable_columns_persetujuan = array(
		//column di table  => alias
		'pembelian.id'				   => 'id',
		'pembelian.no_pembelian'       => 'no_po',
		'supplier.nama'                => 'nama_sup',
		'supplier.kode'                => 'kode_sup',
		'pembelian.tanggal_pesan'      => 'tanggal_pesan',
		'pembelian.tanggal_kadaluarsa' => 'tanggal_kadaluarsa',
		'pembelian.keterangan'         => 'keterangan',
		'user.nama'						=> 'user'
	);

	protected $datatable_columns_persetujuan_history = array(
		//column di table  => alias
		'pembelian.id'				   => 'id',
		'pembelian.no_pembelian'       => 'no_po',
		'supplier.nama'                => 'nama_sup',
		'supplier.kode'                => 'kode_sup',
		'pembelian.tanggal_pesan'      => 'tanggal_pesan',
		'pembelian.tanggal_kadaluarsa' => 'tanggal_kadaluarsa',
		'pembelian.keterangan'         => 'keterangan',
	);

	protected $datatable_columns_proses = array(
		//column di table  => alias
		'pembelian.id'				   => 'id',
		'pembelian.no_pembelian'       => 'no_po',
		'supplier.nama'                => 'nama_sup',
		'supplier.kode'                => 'kode_sup',
		'pembelian.tanggal_pesan'      => 'tanggal_pesan',
		'pembelian.tanggal_kadaluarsa' => 'tanggal_kadaluarsa',
		'pembelian.keterangan'         => 'keterangan',
		'pembelian.status'             => 'status',
		'pembelian.is_kirim'             => 'is_kirim',
	);

	protected $datatable_columns_cetak = array(
		//column di table  => alias
		'pembelian_cetak.pembelian_id'	=> 'id',
		'pembelian_cetak.no_cetak'		=> 'no_cetak',
		'pembelian_cetak.tanggal_cetak'	=> 'tanggal_cetak',
		'user.nama'						=> 'user'
	);


	public function __construct()
	{
		parent::__construct();
	}	

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($tipe, $stat, $jenis)
	{	
		$join1 = array('supplier', $this->_table.'.supplier_id = supplier.id');
		$join2 = array('user', $this->_table.'.created_by = user.id');
		$join_tables = array($join1,$join2);

		if($stat == null)
		{
			$wheres = array();
		}

		if($jenis == 1){
			$jenis = array('1','2');
		}else{
			$jenis = array('3');
		}
		
		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = $this->_table.'.tanggal_pesan';
		$params['sort_dir'] = 'desc';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.status <= 3');
		$this->db->where('pembelian.is_active', 1);
		$this->db->where_in('pembelian.jenis_pembelian', $jenis);
		$this->db->or_where($this->_table.'.status',6);
		$this->db->where('pembelian.is_active', 1);
		$this->db->where_in('pembelian.jenis_pembelian', $jenis);
		$this->db->or_where($this->_table.'.status',7);
		$this->db->where('pembelian.is_active', 1);
		$this->db->where_in('pembelian.jenis_pembelian', $jenis);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.status <= 3');
		$this->db->where('pembelian.is_active', 1);
		$this->db->where_in('pembelian.jenis_pembelian', $jenis);
		$this->db->or_where($this->_table.'.status',6);
		$this->db->where('pembelian.is_active', 1);
		$this->db->where_in('pembelian.jenis_pembelian', $jenis);
		$this->db->or_where($this->_table.'.status',7);
		$this->db->where('pembelian.is_active', 1);
		$this->db->where_in('pembelian.jenis_pembelian', $jenis);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.status <= 3');
		$this->db->where('pembelian.is_active', 1);
		$this->db->where_in('pembelian.jenis_pembelian', $jenis);
		$this->db->or_where($this->_table.'.status',6);
		$this->db->where('pembelian.is_active', 1);
		$this->db->where_in('pembelian.jenis_pembelian', $jenis);
		$this->db->or_where($this->_table.'.status',7);
		$this->db->where('pembelian.is_active', 1);
		$this->db->where_in('pembelian.jenis_pembelian', $jenis);

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

	public function get_datatable_proses($tipe, $jenis)
	{	
		$join1 = array('supplier', $this->_table.'.supplier_id = supplier.id');
		$join_tables = array($join1);

		$tgl_sekarang = date('Y-m-d');

		if($jenis == 1){
			$jenis = array('1','2');
		}else{
			$jenis = array('3');
		}

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_proses);
		$params['sort_by'] = $this->_table.'.tanggal_pesan';
		$params['sort_dir'] = 'desc';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.status',4);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.status_cancel',0);
		$this->db->where_in('pembelian.jenis_pembelian', $jenis);
		
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.status',4);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.status_cancel',0);
		$this->db->where_in('pembelian.jenis_pembelian', $jenis);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.status',4);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.status_cancel',0);
		$this->db->where_in('pembelian.jenis_pembelian', $jenis);
		
		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_proses as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_proses;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}
	public function get_datatable_history($tipe, $jenis)
	{	
		$join1 = array('supplier', $this->_table.'.supplier_id = supplier.id');
		$join_tables = array($join1);

		$tgl_sekarang = date('Y-m-d');

		if($jenis == 1){
			$jenis = array('1','2');
		}else{
			$jenis = array('3');
		}
		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_proses);
		$params['sort_by'] = $this->_table.'.tanggal_pesan';
		$params['sort_dir'] = 'desc';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		
		$this->db->where($this->_table.'.status',5);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where_in('pembelian.jenis_pembelian', $jenis);
		$this->db->or_where($this->_table.'.status',8);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where_in('pembelian.jenis_pembelian', $jenis);
		$this->db->or_where($this->_table.'.status',9);
		$this->db->where($this->_table.'.is_active', 1) ;
		$this->db->where_in('pembelian.jenis_pembelian', $jenis);
		$this->db->or_where($this->_table.'.status_cancel',1);
		$this->db->where_in('pembelian.jenis_pembelian', $jenis);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		
		$this->db->where($this->_table.'.status',5);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where_in('pembelian.jenis_pembelian', $jenis);
		$this->db->or_where($this->_table.'.status',8);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where_in('pembelian.jenis_pembelian', $jenis);
		$this->db->or_where($this->_table.'.status',9);
		$this->db->where($this->_table.'.is_active', 1) ;
		$this->db->where_in('pembelian.jenis_pembelian', $jenis);
		$this->db->or_where($this->_table.'.status_cancel',1);
		$this->db->where_in('pembelian.jenis_pembelian', $jenis);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		
		$this->db->where($this->_table.'.status',5);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where_in('pembelian.jenis_pembelian', $jenis);
		$this->db->or_where($this->_table.'.status',8);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where_in('pembelian.jenis_pembelian', $jenis);
		$this->db->or_where($this->_table.'.status',9);
		$this->db->where($this->_table.'.is_active', 1) ;
		$this->db->where_in('pembelian.jenis_pembelian', $jenis);
		$this->db->or_where($this->_table.'.status_cancel',1);
		$this->db->where_in('pembelian.jenis_pembelian', $jenis);
		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_proses as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_proses;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_datatable_cetak()
	{	
		$join1 = array('pembelian_cetak', $this->_table.'.id = pembelian_cetak.pembelian_id');
		$join2 = array('user', 'pembelian_cetak.user_id = user.id');
		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_cetak);

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
		foreach ($this->datatable_columns_cetak as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_cetak;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_datatable_pembelian($stat)
	{	
		$join1 = array('supplier', $this->_table.'.supplier_id = supplier.id');
		$join2 = array('user', $this->_table.'.created_by = user.id');
		$join_tables = array($join1,$join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = $this->_table.'.tanggal_pesan';
		$params['sort_dir'] = 'desc';
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active', 1) ;

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();

		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active', 1) ;

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active', 1) ;

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

	public function get_datatable_persetujuan_pembelian()
	{	
		$join1 = array('supplier', $this->_table.'.supplier_id = supplier.id', 'left');
		$join2 = array('pembelian_detail', $this->_table.'.id = pembelian_detail.pembelian_id', 'left');
		$join3 = array('persetujuan_pembelian', $this->_table.'.id = persetujuan_pembelian.pembelian_id', 'left');
		$join4 = array('user', $this->_table.'.created_by = user.id', 'left');
		$join_tables = array($join1, $join2, $join3, $join4);

		$user_level = $this->session->userdata('level_id');

		// if($order == 1)
		// {
		// 	$wheres = array('persetujuan_pembelian.`status` <' => 3);
		// }

		// $data = $this->persetujuan_pembelian_m->get_by(array('order' => $order));
		// die_dump($data);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_persetujuan);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('persetujuan_pembelian.user_level_id', $user_level);
		// $this->db->where('persetujuan_pembelian.`status` < 3');
		$this->db->group_by('persetujuan_pembelian.pembelian_id');
		// dapatkan total row count;
		$query = $this->db->select('(1)')->get();
		$total_records = $query->num_rows();
		// $total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('persetujuan_pembelian.user_level_id', $user_level);
		// $this->db->where('persetujuan_pembelian.`status` < 3');
		
		$this->db->group_by('persetujuan_pembelian.pembelian_id');
		// dapatkan total record filtered/search;
		$query = $this->db->select('(1)')->get();
		$total_display_records = $query->num_rows();
		// $total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('persetujuan_pembelian.user_level_id', $user_level);
		// $this->db->where('persetujuan_pembelian.`status` < 3');
		
		$this->db->group_by('persetujuan_pembelian.pembelian_id');

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_persetujuan as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_persetujuan;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_datatable_persetujuan_pembelian_history()
	{	
		$join1 = array('supplier', $this->_table.'.supplier_id = supplier.id', 'left');
		$join2 = array('pembelian_detail', $this->_table.'.id = pembelian_detail.pembelian_id', 'left');
		$join3 = array('persetujuan_pembelian_history', $this->_table.'.id = persetujuan_pembelian_history.pembelian_id', 'left');
		$join_tables = array($join1, $join2, $join3);

		$user_level = $this->session->userdata('level_id');

		// if($order == 1)
		// {
		// 	$wheres = array('persetujuan_pembelian_history.`status` <' => 3);
		// }

		// $data = $this->persetujuan_pembelian_history_m->get_by(array('order' => $order));
		// die_dump($data);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_persetujuan_history);
		$params['sort_by'] = 'persetujuan_pembelian_history.id';
		$params['sort_dir'] = 'desc';
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('persetujuan_pembelian_history.user_level_id', $user_level);
		// $this->db->where('persetujuan_pembelian_history.`status` < 3');
		$this->db->group_by('persetujuan_pembelian_history.pembelian_id');
		// dapatkan total row count;
		$query = $this->db->select('(1)')->get();
		$total_records = $query->num_rows();
		// $total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('persetujuan_pembelian_history.user_level_id', $user_level);
		// $this->db->where('persetujuan_pembelian_history.`status` < 3');
		
		$this->db->group_by('persetujuan_pembelian_history.pembelian_id');
		// dapatkan total record filtered/search;
		$query = $this->db->select('(1)')->get();
		$total_display_records = $query->num_rows();
		// $total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('persetujuan_pembelian_history.user_level_id', $user_level);
		// $this->db->where('persetujuan_pembelian_history.`status` < 3');
		
		$this->db->group_by('persetujuan_pembelian_history.pembelian_id');

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_persetujuan_history as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_persetujuan_history;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_datatable_belum_bayar()
	{	
		$join1 = array('supplier', $this->_table.'.supplier_id = supplier.id');
		$join2 = array('user', $this->_table.'.created_by = user.id');
		$join_tables = array($join1,$join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = $this->_table.'.id';
		$params['sort_dir'] = 'desc';
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.status_keuangan', 1) ;
		$this->db->where($this->_table.'.is_active', 1) ;

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();

		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.status_keuangan', 1) ;
		$this->db->where($this->_table.'.is_active', 1) ;

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.status_keuangan', 1) ;
		$this->db->where($this->_table.'.is_active', 1) ;

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


	public function get_datatable_history_by_supp($supplier_id, $jenis)
	{	
		$join1 = array('supplier', $this->_table.'.supplier_id = supplier.id');
		$join_tables = array($join1);

		$tgl_sekarang = date('Y-m-d');

		if($jenis == 1){
			$jenis = array('1','2','3');
		}else{
			$jenis = array('3');
		}
		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_proses);
		$params['sort_by'] = $this->_table.'.tanggal_pesan';
		$params['sort_dir'] = 'desc';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		
		$this->db->where($this->_table.'.supplier_id',$supplier_id);
		$this->db->where($this->_table.'.status',5);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where_in('pembelian.jenis_pembelian', $jenis);
		
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		
		$this->db->where($this->_table.'.supplier_id',$supplier_id);		
		$this->db->where($this->_table.'.status',5);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where_in('pembelian.jenis_pembelian', $jenis);
		
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		
		$this->db->where($this->_table.'.supplier_id',$supplier_id);
		$this->db->where($this->_table.'.status',5);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where_in('pembelian.jenis_pembelian', $jenis);
		
		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_proses as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_proses;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_no_pembelian()
	{
		$format = "SELECT MAX(SUBSTRING(`no_pembelian`,5,3)) AS max_no_pembelian FROM `pembelian` WHERE RIGHT(`no_pembelian`,4) = DATE_FORMAT(NOW(), '%Y') AND pembelian.is_active = 1;";	
		return $this->db->query($format);
	}

	public function get_max_id_pembelian()
	{
		$format = "SELECT MAX(SUBSTRING(`id`,12,4)) AS max_id FROM `pembelian` WHERE SUBSTR(`id`,4,7) = DATE_FORMAT(NOW(), '%m-%Y');";	
		return $this->db->query($format);
	}

	public function get_data_supplier($id)
	{
		 $format = "SELECT
					supplier.id,
					supplier.nama,
					supplier.kode,
					supplier.orang_yang_bersangkutan,
					supplier_alamat.alamat as alamat,
					supplier_alamat.rt_rw as rt_rw,
					(SELECT region.nama FROM region WHERE region.tipe = 5 AND supplier_alamat.kelurahan_id = region.id ) kelurahan,
					(SELECT region.nama FROM region WHERE region.tipe = 4 AND supplier_alamat.kecamatan_id = region.id ) kecamatan,
					(SELECT region.nama FROM region WHERE region.tipe = 3 AND supplier_alamat.kota_id = region.id ) kota,
					(SELECT region.nama FROM region WHERE region.tipe = 2 AND supplier_alamat.provinsi_id = region.id ) propinsi,
					(SELECT region.nama FROM region WHERE region.tipe = 1 AND supplier_alamat.negara_id = region.id ) negara
				   
					FROM
					pembelian ,
					supplier,
					supplier_alamat
					WHERE
					pembelian.supplier_id = supplier.id AND
					supplier_alamat.supplier_id = supplier.id AND
					pembelian.id = '".$id."'
					";

		 return $this->db->query($format);
	}

	public function get_laporan_pembelian($bulan, $tahun)
	{
		// die_dump($);
		$format = "SELECT
					pembelian.no_pembelian AS 'No. Pembelian',
					supplier.nama AS 'Nama Supplier',

					IF (
						supplier.is_pkp = 1,
						'PKP',
						'Non PKP'
					) AS Pajak,
					 supplier.npwp AS NPWP,

					IF (
						pembelian.jenis_pembelian = 1,
						'Obat',

					IF (
						pembelian.jenis_pembelian = 2,
						'Alkes',

					IF (
						pembelian.jenis_pembelian = 3,
						'Umum',
						'Tidak Ada'
					)
					)
					) AS 'Jenis Pembelian',
					 pembelian.tanggal_pesan AS 'Tanggal Pesan',
					 pembelian_invoice.no_invoice AS 'No Invoice',
					 pembelian_invoice.total_invoice AS 'Total Invoice',
					 pembelian_invoice.no_faktur_pajak AS 'No Faktur Pajak',
					 item.nama AS 'Nama Item',
					 pembelian_detail.jumlah_disetujui AS 'Jumlah Beli',
					 item_satuan.nama AS Satuan,
					 pembelian_detail.harga_beli AS 'Harga Beli',
					 pembelian_detail.diskon AS 'Diskon Item',
					 (
						pembelian_detail.jumlah_disetujui * pembelian_detail.harga_beli
					) AS 'Total Harga',
					 pembelian.diskon AS 'Diskon PO',
					 pembelian.pph AS PPN
					FROM
						pembelian
					LEFT JOIN supplier ON pembelian.supplier_id = supplier.id
					LEFT JOIN pembelian_detail ON pembelian.id = pembelian_detail.pembelian_id
					LEFT JOIN pembelian_invoice ON pembelian.id = pembelian_invoice.pembelian_id
					LEFT JOIN item ON pembelian_detail.item_id = item.id
					LEFT JOIN item_satuan ON pembelian_detail.item_satuan_id
					WHERE
						MONTH (pembelian.tanggal_pesan) = '$bulan'
					AND YEAR (pembelian.tanggal_pesan) = '$tahun'";

		return $this->db->query($format);
		die_dump('test');
	}

	public function get_data_no_telp($id)
	{
		$format = "SELECT
					supplier_telp.no_telp as no_telp			   
					FROM
					pembelian ,
					supplier,
					supplier_telp
					WHERE
					pembelian.supplier_id = supplier.id AND
					supplier_telp.supplier_id = supplier.id AND
					pembelian.id =  $id
		";

		return $this->db->query($format);
	}

	public function get_data_item($id, $id_sup)
	{
		$format = "SELECT
						supplier.nama,
						pembelian_detail.diskon,
						pembelian_detail.id AS id_detail,
						pembelian_detail.jumlah_pesan,
						pembelian_detail.is_active AS is_active,
						item_satuan.nama AS satuan,
						item_satuan.id AS id_satuan,
						item.nama AS nama,
						item.kode AS kode,
						item.id AS id,
						supplier_item.minimum_order AS min_order,
						supplier_item.kelipatan_order AS max_order,
						pembelian.id AS id_draf
					FROM
						pembelian
					JOIN pembelian_detail ON pembelian.id = pembelian_detail.pembelian_id
					LEFT JOIN supplier ON pembelian.supplier_id = supplier.id
					LEFT JOIN item ON pembelian_detail.item_id = item.id
					LEFT JOIN item_satuan ON pembelian_detail.item_satuan_id = item_satuan.id
					LEFT JOIN supplier_item ON supplier_item.item_id = item.id
					AND supplier_item.item_satuan_id = item_satuan.id
					WHERE
						pembelian.id = '".$id."'
					AND pembelian.supplier_id = $id_sup AND
					pembelian_detail.is_active = 1
					GROUP BY pembelian_detail.id


					";

		return $this->db->query($format, $id);
	}

	public function get_data_item_view($id, $id_sup)
	{
		$format = "SELECT
						supplier.nama,
						pembelian_detail.diskon,
						pembelian_detail.id AS id_detail,
						pembelian_detail.jumlah_pesan,
						pembelian_detail.is_active AS is_active,
						item_satuan.nama AS satuan,
						item_satuan.id AS id_satuan,
						item.nama AS nama,
						item.kode AS kode,
						item.id AS id,
						supplier_item.minimum_order AS min_order,
						supplier_item.kelipatan_order AS max_order,
						pembelian.id AS id_draf
					FROM
						pembelian
					JOIN pembelian_detail ON pembelian.id = pembelian_detail.pembelian_id
					LEFT JOIN supplier ON pembelian.supplier_id = supplier.id
					LEFT JOIN item ON pembelian_detail.item_id = item.id
					LEFT JOIN item_satuan ON pembelian_detail.item_satuan_id = item_satuan.id
					LEFT JOIN supplier_item ON supplier_item.item_id = item.id
					AND supplier_item.item_satuan_id = item_satuan.id
					WHERE
						pembelian.id = '".$id."'
					AND pembelian.supplier_id = $id_sup AND
					pembelian_detail.is_active = 1
					GROUP BY pembelian_detail.id";

		return $this->db->query($format, $id);
	}

	public function get_satuan($id)
	{
		$format = "SELECT id, nama FROM item_satuan WHERE item_satuan.item_id = '$id'";

		return $this->db->query($format);
	}

	public function get_data($id)
	{
		$format = "SELECT pembelian.tanggal_pesan as tanggal_pesan,
						pembelian.no_pembelian as no_pembelian,
						pembelian.tanggal_kadaluarsa as tanggal_kadaluarsa,
						pembelian.tanggal_garansi as tanggal_kirim,
						pembelian.customer_id as customer_id,
						pembelian.tipe_customer as tipe_customer,
						pembelian.master_tipe_pembayaran_id as master_tipe_pembayaran_id,
						pembelian.tipe_pembayaran as tipe_pembayaran,
						pembelian.ket_tambahan as ket_tambahan,
						pembelian.keterangan as keterangan,
						pembelian.diskon as diskon,
						pembelian.pph as pph,
						pembelian.dp as dp,
						pembelian.grand_total_po as grand_total_po,
						pembelian.grand_total as grand_total,
						pembelian.sisa_bayar as sisa_bayar,
						pembelian.biaya_tambahan as biaya_tambahan,
						pembelian.supplier_id as supplier_id,
						pembelian.is_single_kirim as is_single_kirim,
						pembelian.tanggal_kirim as tanggal_kirim,
						pembelian.pph_23 as pph_23,
						pembelian.pph_23_nominal as pph_23_nominal,
						supplier.tipe as tipe_supplier,
						supplier.nama as nama,
						supplier.kode as kode,
						supplier.id as id,
						supplier.is_harga_flexible as is_harga_flexible,
						supplier.is_pkp as is_pkp,
						supplier_email.email as email,
						supplier_telp.no_telp as no_telp,
						supplier_alamat.alamat as alamat,
						supplier_alamat.rt_rw as rt_rw,
						inf_lokasi.nama_kelurahan as kelurahan,
						inf_lokasi.nama_kecamatan as kecamatan,
						inf_lokasi.nama_kabupatenkota as kota,
						inf_lokasi.nama_propinsi as propinsi
				   FROM pembelian JOIN supplier ON pembelian.supplier_id = supplier.id
				   LEFT JOIN supplier_telp ON supplier_telp.supplier_id = supplier.id
				   LEFT JOIN supplier_alamat ON supplier_alamat.supplier_id = supplier.id
				   LEFT JOIN supplier_email ON supplier_email.supplier_id = supplier.id
				   LEFT JOIN inf_lokasi ON supplier_alamat.kode_lokasi = inf_lokasi.lokasi_kode
				   WHERE 
						supplier_telp.is_primary = 1
						AND
						pembelian.id = '".$id."' ";
				   

		return $this->db->query($format, $id);
	}

	public function get_datatable_lunas($jenis)
	{	
		$join1 = array('supplier', $this->_table.'.supplier_id = supplier.id');
		$join_tables = array($join1);

		if($jenis == 1){
			$jenis = array('1','2');
		}else{
			$jenis = array('3');
		}

		$tgl_sekarang = date('Y-m-d');
		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_proses);
		$params['sort_by'] = $this->_table.'.tanggal_pesan';
		$params['sort_dir'] = 'desc';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where_in('pembelian.jenis_pembelian', $jenis);
		$this->db->where('pembelian.id IN (SELECT id FROM PO_ALL_LUNAS)');
		
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where_in('pembelian.jenis_pembelian', $jenis);
		$this->db->where('pembelian.id IN (SELECT id FROM PO_ALL_LUNAS)');
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where_in('pembelian.jenis_pembelian', $jenis);
		$this->db->where('pembelian.id IN (SELECT id FROM PO_ALL_LUNAS)');
		
		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_proses as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_proses;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}
}

/* End of file cabang_m.php */
/* Location: ./application/models/pembelian/pembelian_m.php */