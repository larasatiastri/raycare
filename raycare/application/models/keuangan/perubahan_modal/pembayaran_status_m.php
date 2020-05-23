<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembayaran_status_m extends MY_Model {

	protected $_table        = 'pembayaran_status';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'pembayaran_status.id'              => 'id',
		'pembayaran_status.tipe_transaksi'  => 'tipe_transaksi',
		'pembayaran_status.transaksi_nomor' => 'transaksi_nomor',
		'pembayaran_status.nominal'         => 'nominal',
		'supplier.nama'						=> 'nama_supplier'
		
	);

	protected $datatable_columns_kyw = array(
		//column di table  => alias
		'pembayaran_status.id'              => 'id',
		'pembayaran_status.created_date'    => 'tanggal',
		'user.nama'                      => 'nama_user',
		'pembayaran_status.tipe_transaksi'  => 'tipe_transaksi',
		'pembayaran_status.transaksi_nomor' => 'transaksi_nomor',
		'pembayaran_status.nominal'         => 'nominal',
		'pembayaran_status.status'          => 'status',

	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable_ttf()
	{	
		$join1 = array('tanda_terima_faktur' , $this->_table.'.transaksi_id = tanda_terima_faktur.id','left');
		$join2 = array('supplier', 'tanda_terima_faktur.supplier_id = supplier.id','left');

 		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by']  = "supplier.nama";
		$params['sort_dir'] = "asc";

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.tipe_transaksi',4);
		$this->db->where($this->_table.'.status <',2);

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.tipe_transaksi',4);
		$this->db->where($this->_table.'.status <',2);


		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();
		
		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.tipe_transaksi',4);
		$this->db->where($this->_table.'.status <',2);


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
		//die(dump($result->records));
		return $result; 
	}
	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable_po()
	{	
		$join1 = array('pembelian' , $this->_table.'.transaksi_id = pembelian.id','left');
		$join2 = array('supplier', 'pembelian.supplier_id = supplier.id','left');

 		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by']  = "supplier.nama";
		$params['sort_dir'] = "asc";

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.tipe_transaksi',1);
		$this->db->where_in($this->_table.'.status',array(4,10));

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.tipe_transaksi',1);
		$this->db->where_in($this->_table.'.status',array(4,10));


		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();
		
		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.tipe_transaksi',1);
		$this->db->where_in($this->_table.'.status',array(4,10));


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
		//die(dump($result->records));
		return $result; 
	}

			/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable_reimburse()
	{	
		$join1 = array('user' , $this->_table.'.created_by = user.id','left');

 		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_kyw);
		$params['sort_by']="user.nama";
		$params['sort_dir']="asc";

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.tipe_transaksi',3);
		$this->db->where($this->_table.'.status',20);
		$this->db->or_where($this->_table.'.tipe_transaksi',3);
		$this->db->where($this->_table.'.status',17);

		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.tipe_transaksi',3);
		$this->db->where($this->_table.'.status',20);
		$this->db->or_where($this->_table.'.tipe_transaksi',3);
		$this->db->where($this->_table.'.status',17);
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();
		
		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.tipe_transaksi',3);
		$this->db->where($this->_table.'.status',20);
		$this->db->or_where($this->_table.'.tipe_transaksi',3);
		$this->db->where($this->_table.'.status',17);

		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_kyw as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_kyw;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		//die(dump($result->records));
		return $result; 
	}

	public function get_datatable_reimburse_history()
	{	
		$join1 = array('user' , $this->_table.'.created_by = user.id','left');
		$join2 = array('user_level a', 'user.user_level_id = a.id','left');
		$join3 = array('divisi da', 'a.divisi_id = da.id', 'left');
		$join4 = array('user_level b', $this->_table.'.user_level_id = b.id','left');
		$join5 = array('divisi db', $this->_table.'.divisi = b.id','left');

 		$join_tables = array($join1, $join2, $join3, $join4, $join5);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by']="pembayaran_status.created_date";
		$params['sort_dir']="desc";

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.tipe_transaksi',3);
		$this->db->where($this->_table.'.status',5);

		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.tipe_transaksi',3);
		$this->db->where($this->_table.'.status',5);
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();
		
		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.tipe_transaksi',3);
		$this->db->where($this->_table.'.status',5);

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
		//die(dump($result->records));
		return $result; 
	}
	
	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable_biaya()
	{	
		$join1 = array('user' , $this->_table.'.created_by = user.id','left');
		$join2 = array('user_level a', 'user.user_level_id = a.id','left');
		$join3 = array('divisi da', 'a.divisi_id = da.id', 'left');
		$join4 = array('user_level b', $this->_table.'.user_level_id = b.id','left');
		$join5 = array('divisi db', $this->_table.'.divisi = b.id','left');

 		$join_tables = array($join1, $join2, $join3, $join4, $join5);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by']="pembayaran_status.created_date";
		$params['sort_dir']="desc";

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.tipe_transaksi',2);
		$this->db->where($this->_table.'.status <=',20);
		if($this->session->userdata('level_id') != 1){
			$this->db->where('`user`.user_level_id', $this->session->userdata('level_id'));
		}
		$this->db->or_where($this->_table.'.tipe_transaksi',2);
		$this->db->where($this->_table.'.status',21);
		if($this->session->userdata('level_id') != 1){
			$this->db->where('`user`.user_level_id', $this->session->userdata('level_id'));
		}
				$this->datatable_prepare_or($params);

		$this->db->or_where($this->_table.'.tipe_transaksi',3);
		$this->db->where($this->_table.'.status <=',20);
		if($this->session->userdata('level_id') != 1){
			$this->db->where('`user`.user_level_id', $this->session->userdata('level_id'));
		}
				$this->datatable_prepare_or($params);

		$this->db->or_where($this->_table.'.tipe_transaksi',3);
		$this->db->where($this->_table.'.status',21);
		if($this->session->userdata('level_id') != 1){
			$this->db->where('`user`.user_level_id', $this->session->userdata('level_id'));
		}
				$this->datatable_prepare_or($params);

		
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.tipe_transaksi',2);
		$this->db->where($this->_table.'.status <=',20);
		if($this->session->userdata('level_id') != 1){
			$this->db->where('`user`.user_level_id', $this->session->userdata('level_id'));
		}

		$this->db->or_where($this->_table.'.tipe_transaksi',2);
		$this->db->where($this->_table.'.status',21);
		if($this->session->userdata('level_id') != 1){
			$this->db->where('`user`.user_level_id', $this->session->userdata('level_id'));
		}
				$this->datatable_prepare_or($params, true);

		$this->db->or_where($this->_table.'.tipe_transaksi',3);
		$this->db->where($this->_table.'.status <=',20);
		if($this->session->userdata('level_id') != 1){
			$this->db->where('`user`.user_level_id', $this->session->userdata('level_id'));
		}
				$this->datatable_prepare_or($params, true);

		$this->db->or_where($this->_table.'.tipe_transaksi',3);
		$this->db->where($this->_table.'.status',21);
		if($this->session->userdata('level_id') != 1){
			$this->db->where('`user`.user_level_id', $this->session->userdata('level_id'));
		}
				$this->datatable_prepare_or($params, true);

		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();
		
		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.tipe_transaksi',2);
		$this->db->where($this->_table.'.status <=',20);
		if($this->session->userdata('level_id') != 1){
			$this->db->where('`user`.user_level_id', $this->session->userdata('level_id'));
		}
		$this->db->or_where($this->_table.'.tipe_transaksi',2);
		$this->db->where($this->_table.'.status',21);
		if($this->session->userdata('level_id') != 1){
			$this->db->where('`user`.user_level_id', $this->session->userdata('level_id'));
		}
				$this->datatable_prepare_or($params, true, true);

		$this->db->or_where($this->_table.'.tipe_transaksi',3);
		$this->db->where($this->_table.'.status <=',20);
		if($this->session->userdata('level_id') != 1){
			$this->db->where('`user`.user_level_id', $this->session->userdata('level_id'));
		}
				$this->datatable_prepare_or($params, true, true);

		$this->db->or_where($this->_table.'.tipe_transaksi',3);
		$this->db->where($this->_table.'.status',21);
		if($this->session->userdata('level_id') != 1){
			$this->db->where('`user`.user_level_id', $this->session->userdata('level_id'));
		}
				$this->datatable_prepare_or($params, true, true);

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

	public function get_datatable_biaya_kasbon()
	{	
		$join1 = array('user' , $this->_table.'.created_by = user.id','left');
		$join2 = array('user_level a', 'user.user_level_id = a.id','left');
		$join3 = array('divisi da', 'a.divisi_id = da.id', 'left');
		$join4 = array('user_level b', $this->_table.'.user_level_id = b.id','left');
		$join5 = array('divisi db', $this->_table.'.divisi = b.id','left');

 		$join_tables = array($join1, $join2, $join3, $join4, $join5);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by']="pembayaran_status.created_date";
		$params['sort_dir']="desc";

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.tipe_transaksi',2);
		$this->db->where($this->_table.'.status <=',20);
		if($this->session->userdata('level_id') != 1){
			$this->db->where('`user`.user_level_id', $this->session->userdata('level_id'));
		}
		$this->db->or_where($this->_table.'.tipe_transaksi',2);
		$this->db->where($this->_table.'.status',21);
		if($this->session->userdata('level_id') != 1){
			$this->db->where('`user`.user_level_id', $this->session->userdata('level_id'));
		}
		$this->datatable_prepare_or($params);

		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.tipe_transaksi',2);
		$this->db->where($this->_table.'.status <=',20);
		if($this->session->userdata('level_id') != 1){
			$this->db->where('`user`.user_level_id', $this->session->userdata('level_id'));
		}

		$this->db->or_where($this->_table.'.tipe_transaksi',2);
		$this->db->where($this->_table.'.status',21);
		if($this->session->userdata('level_id') != 1){
			$this->db->where('`user`.user_level_id', $this->session->userdata('level_id'));
		}
		$this->datatable_prepare_or($params, true);

		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();
		
		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.tipe_transaksi',2);
		$this->db->where($this->_table.'.status <=',20);
		if($this->session->userdata('level_id') != 1){
			$this->db->where('`user`.user_level_id', $this->session->userdata('level_id'));
		}
		$this->db->or_where($this->_table.'.tipe_transaksi',2);
		$this->db->where($this->_table.'.status',21);
		if($this->session->userdata('level_id') != 1){
			$this->db->where('`user`.user_level_id', $this->session->userdata('level_id'));
		}
		$this->datatable_prepare_or($params, true, true);


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
		$join1 = array('user' , $this->_table.'.created_by = user.id','left');
		$join2 = array('user_level a', 'user.user_level_id = a.id','left');
		$join3 = array('divisi da', 'a.divisi_id = da.id', 'left');
		$join4 = array('user_level b', $this->_table.'.user_level_id = b.id','left');
		$join5 = array('divisi db', $this->_table.'.divisi = b.id','left');

 		$join_tables = array($join1, $join2, $join3, $join4, $join5);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by']="pembayaran_status.id";
		$params['sort_dir']="desc";

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.tipe_transaksi',1);
		$this->db->where($this->_table.'.status >=',12);
		$this->db->or_where($this->_table.'.tipe_transaksi',2);
		$this->db->where($this->_table.'.status',5);
		$this->datatable_prepare_or($params);
		$this->db->or_where($this->_table.'.tipe_transaksi',3);
		$this->db->where($this->_table.'.status',5);
		$this->datatable_prepare_or($params);

		$this->db->or_where($this->_table.'.tipe_transaksi',4);
		$this->db->where($this->_table.'.status >=',3);
		$this->datatable_prepare_or($params);
		
		
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.tipe_transaksi',1);
		$this->db->where($this->_table.'.status >=',12);
		$this->db->or_where($this->_table.'.tipe_transaksi',2);
		$this->db->where($this->_table.'.status',5);
		$this->datatable_prepare_or($params,true);
		
		$this->db->or_where($this->_table.'.tipe_transaksi',3);
		$this->db->where($this->_table.'.status',5);
		$this->datatable_prepare_or($params,true);

		$this->db->or_where($this->_table.'.tipe_transaksi',4);
		$this->db->where($this->_table.'.status >=',3);
		$this->datatable_prepare_or($params,true);
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();
		
		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.tipe_transaksi',1);
		$this->db->where($this->_table.'.status >=',12);
		$this->db->or_where($this->_table.'.tipe_transaksi',2);
		$this->db->where($this->_table.'.status',5);
		$this->datatable_prepare_or($params,true,true);
		
		$this->db->or_where($this->_table.'.tipe_transaksi',3);
		$this->db->where($this->_table.'.status',5);
		$this->datatable_prepare_or($params,true,true);
		
		$this->db->or_where($this->_table.'.tipe_transaksi',4);
		$this->db->where($this->_table.'.status >=',3);
		$this->datatable_prepare_or($params,true,true);
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

	public function get_max_id_pembayaran()
	{
		$format = "SELECT MAX(SUBSTRING(`id`,12,4)) AS max_id FROM `pembayaran_status` WHERE SUBSTR(`id`,4,7) = DATE_FORMAT(NOW(), '%m-%Y');";	
		return $this->db->query($format);
	}

}

/* End of file Pembayaran_status.php */
/* Location: ./application/models/keuangan/Pembayaran_status.php */