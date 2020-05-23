<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Persetujuan_permintaan_biaya_history_m extends MY_Model {

	protected $_table        = 'persetujuan_permintaan_biaya_history';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'permintaan_biaya.tanggal'                                     => 'tanggal',
		'user.nama'                                                    => 'nama_dibuat_oleh',
		'permintaan_biaya.nominal'                                     => 'nominal',
		'permintaan_biaya.keperluan'                                  => 'keperluan',
		'persetujuan_permintaan_biaya_history.id' => 'persetujuan_permintaan_biaya_id', 
		'persetujuan_permintaan_biaya_history.permintaan_biaya_id'             => 'permintaan_biaya_id',
		'persetujuan_permintaan_biaya_history.user_level_id'                   => 'user_level_id',
		'persetujuan_permintaan_biaya_history.`order`'                         => 'order_persetujuan',
		'persetujuan_permintaan_biaya_history.`status`'                        => 'status_persetujuan',
		'persetujuan_permintaan_biaya_history.tanggal_baca'                    => 'tanggal_baca',
		'persetujuan_permintaan_biaya_history.dibaca_oleh'                     => 'dibaca_oleh',
		'persetujuan_permintaan_biaya_history.tanggal_persetujuan'             => 'tanggal_persetujuan',
		'persetujuan_permintaan_biaya_history.disetujui_oleh'                  => 'disetujui_oleh',
		'permintaan_biaya.is_active'                                   => 'is_active',
		'permintaan_biaya.diminta_oleh_id'                                  => 'diminta_oleh_id',
		// '(SELECT SUM(permintaan_biaya_tipe.jumlah) FROM permintaan_biaya_tipe WHERE permintaan_biaya_id = permintaan_biaya_id)' => 'total_rupiah',
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($user_level_id)
	{	
		$join1 = array('permintaan_biaya', $this->_table.'.permintaan_biaya_id = permintaan_biaya.id', 'LEFT');
		$join2 = array('`user`', 'permintaan_biaya.diminta_oleh_id = `user`.id', 'LEFT');
		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('persetujuan_permintaan_biaya_history.user_level_id', $user_level_id);
		
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('persetujuan_permintaan_biaya_history.user_level_id', $user_level_id);

				// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('persetujuan_permintaan_biaya_history.user_level_id', $user_level_id);
		
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

	public function data_order($permintaan_biaya_id, $order)
	{

		$sql = "SELECT
				persetujuan_permintaan_biaya.persetujuan_permintaan_biaya_id,
				persetujuan_permintaan_biaya.permintaan_biaya_id,
				persetujuan_permintaan_biaya.`order` AS `order`,
				persetujuan_permintaan_biaya.user_level_id,
				persetujuan_permintaan_biaya.`status` AS `status`
				FROM
				persetujuan_permintaan_biaya
				WHERE
				persetujuan_permintaan_biaya.permintaan_biaya_id = $permintaan_biaya_id
				AND
				persetujuan_permintaan_biaya.`order` <= $order";

		return $this->db->query($sql);

	}

	public function data_order2($permintaan_biaya_id, $order)
	{

		$sql = "SELECT
				persetujuan_permintaan_biaya.persetujuan_permintaan_biaya_id,
				persetujuan_permintaan_biaya.permintaan_biaya_id,
				persetujuan_permintaan_biaya.`order` AS `order`,
				persetujuan_permintaan_biaya.user_level_id,
				persetujuan_permintaan_biaya.`status` AS `status`
				FROM
				persetujuan_permintaan_biaya
				WHERE
				persetujuan_permintaan_biaya.permintaan_biaya_id = $permintaan_biaya_id
				AND
				persetujuan_permintaan_biaya.`order` < $order";

		return $this->db->query($sql);

	}	

	public function get_max_order($permintaan_biaya_id)
	{
		$sql = "select max(`order`) as max_order from persetujuan_permintaan_biaya Where permintaan_biaya_id = $permintaan_biaya_id";
		return $this->db->query($sql);
	}

	public function delete_id($id)
	{

		$sql = "DELETE FROM persetujuan_permintaan_biaya
				WHERE persetujuan_permintaan_biaya_id =  $id";
		
		return $this->db->query($sql);
	}
	
	public function update_status($data, $persetujuan_permintaan_biaya_id, $permintaan_biaya_id, $user_id)
	{

		$this->db->where('persetujuan_permintaan_biaya_id', $persetujuan_permintaan_biaya_id);
		$this->db->where('permintaan_biaya_id', $permintaan_biaya_id);
		$this->db->where('user_level_id', $user_id);
		$this->db->update($this->_table, $data);

	}

	public function insert_update($data, $persetujuan_permintaan_biaya_id, $permintaan_biaya_id, $user_level_id)
	{

		$this->db->where('persetujuan_permintaan_biaya_id', $persetujuan_permintaan_biaya_id);
		$this->db->where('permintaan_biaya_id', $permintaan_biaya_id);
		$this->db->where('user_level_id', $user_level_id);
		$this->db->update($this->_table, $data);

	}

	public function get_data_full($permintaan_biaya_id)
	{
		$this->db->select('persetujuan_permintaan_biaya_history.*, user_baca.nama as nama_baca, user_setuju.nama as nama_setuju, user_level.nama as nama_level');
		$this->db->join('user_level', $this->_table.'.user_level_id = user_level.id','left');
		$this->db->join('user user_baca', $this->_table.'.dibaca_oleh = user_baca.id','left');
		$this->db->join('user user_setuju', $this->_table.'.disetujui_oleh = user_setuju.id','left');
		$this->db->where($this->_table.'.permintaan_biaya_id', $permintaan_biaya_id);

		return $this->db->get($this->_table)->result_array();
	}

}

/* End of file Permintaan_biaya.php */
/* Location: ./application/models/keuangan/Permintaan_biaya.php */