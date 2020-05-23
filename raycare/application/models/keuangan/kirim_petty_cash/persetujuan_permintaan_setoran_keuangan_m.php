<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Persetujuan_permintaan_setoran_keuangan_m extends MY_Model
{
	protected $_table        = 'persetujuan_permintaan_setoran_keuangan';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;


	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'setoran_keuangan_kasir.tanggal'                                                => 'tanggal',
		'user.nama'                                                                     => 'nama_dibuat_oleh',
		'setoran_keuangan_kasir.total_setor'                                            => 'total_setor',
		'setoran_keuangan_kasir.subjek'                                                 => 'subjek',
		'persetujuan_permintaan_setoran_keuangan.persetujuan_permintaan_setoran_keuangan_id' => 'persetujuan_permintaan_setoran_keuangan_id', 
		'persetujuan_permintaan_setoran_keuangan.setoran_keuangan_kasir_id'                  => 'setoran_keuangan_kasir_id',
		'persetujuan_permintaan_setoran_keuangan.user_level_id'                              => 'user_level_id',
		'persetujuan_permintaan_setoran_keuangan.`order`'                                    => 'order_persetujuan',
		'persetujuan_permintaan_setoran_keuangan.`status`'                                   => 'status_persetujuan',
		'persetujuan_permintaan_setoran_keuangan.tanggal_baca'                               => 'tanggal_baca',
		'persetujuan_permintaan_setoran_keuangan.dibaca_oleh'                                => 'dibaca_oleh',
		'persetujuan_permintaan_setoran_keuangan.tanggal_persetujuan'                        => 'tanggal_persetujuan',
		'persetujuan_permintaan_setoran_keuangan.disetujui_oleh'                             => 'disetujui_oleh',
		'setoran_keuangan_kasir.created_by'                                             => 'diminta_oleh_id',
	);
	
	function __construct ()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($user_level_id)
	{	
		$join1 = array('setoran_keuangan_kasir', $this->_table.'.setoran_keuangan_kasir_id = setoran_keuangan_kasir.id', 'LEFT');
		$join2 = array('`user`', 'setoran_keuangan_kasir.created_by = `user`.id', 'LEFT');
		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('persetujuan_permintaan_setoran_keuangan.user_level_id', $user_level_id);
		$this->db->where('persetujuan_permintaan_setoran_keuangan.is_active', 1);
		$this->db->where('persetujuan_permintaan_setoran_keuangan.`status` <= 3');
		$this->db->group_by('persetujuan_permintaan_setoran_keuangan.user_level_id');
		$this->db->group_by('persetujuan_permintaan_setoran_keuangan.setoran_keuangan_kasir_id');

		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('persetujuan_permintaan_setoran_keuangan.user_level_id', $user_level_id);
		$this->db->where('persetujuan_permintaan_setoran_keuangan.is_active', 1);
		$this->db->where('persetujuan_permintaan_setoran_keuangan.`status` <= 3');
		$this->db->group_by('persetujuan_permintaan_setoran_keuangan.user_level_id');
		$this->db->group_by('persetujuan_permintaan_setoran_keuangan.setoran_keuangan_kasir_id');
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('persetujuan_permintaan_setoran_keuangan.user_level_id', $user_level_id);
		$this->db->where('persetujuan_permintaan_setoran_keuangan.is_active', 1);
		$this->db->where('persetujuan_permintaan_setoran_keuangan.`status` <= 3');
		$this->db->group_by('persetujuan_permintaan_setoran_keuangan.user_level_id');
		$this->db->group_by('persetujuan_permintaan_setoran_keuangan.setoran_keuangan_kasir_id');

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

	public function get_last_id()
	{
		$sql = "SELECT MAX(persetujuan_permintaan_setoran_keuangan_id) as last_id FROM persetujuan_permintaan_setoran_keuangan";

		return $this->db->query($sql);
	}

	public function data_order($setoran_keuangan_kasir_id, $order)
	{

		$sql = "SELECT
				persetujuan_permintaan_setoran_keuangan.persetujuan_permintaan_setoran_keuangan_id,
				persetujuan_permintaan_setoran_keuangan.setoran_keuangan_kasir_id,
				persetujuan_permintaan_setoran_keuangan.`order` AS `order`,
				persetujuan_permintaan_setoran_keuangan.user_level_id,
				persetujuan_permintaan_setoran_keuangan.`status` AS `status`
				FROM
				persetujuan_permintaan_setoran_keuangan
				WHERE
				persetujuan_permintaan_setoran_keuangan.setoran_keuangan_kasir_id = $setoran_keuangan_kasir_id
				AND
				persetujuan_permintaan_setoran_keuangan.`order` = $order";

		return $this->db->query($sql);

	}

	public function data_order2($setoran_keuangan_kasir_id, $order)
	{

		$sql = "SELECT
				persetujuan_permintaan_setoran_keuangan.persetujuan_permintaan_setoran_keuangan_id,
				persetujuan_permintaan_setoran_keuangan.setoran_keuangan_kasir_id,
				persetujuan_permintaan_setoran_keuangan.`order` AS `order`,
				persetujuan_permintaan_setoran_keuangan.user_level_id,
				persetujuan_permintaan_setoran_keuangan.`status` AS `status`
				FROM
				persetujuan_permintaan_setoran_keuangan
				WHERE
				persetujuan_permintaan_setoran_keuangan.setoran_keuangan_kasir_id = $setoran_keuangan_kasir_id
				AND
				persetujuan_permintaan_setoran_keuangan.`order` < $order";

		return $this->db->query($sql);

	}	

	public function get_max_order($setoran_keuangan_kasir_id)
	{
		$sql = "select max(`order`) as max_order from persetujuan_permintaan_setoran_keuangan where setoran_keuangan_kasir_id = $setoran_keuangan_kasir_id";
		return $this->db->query($sql);
	}

	public function delete_id($id)
	{

		$sql = "DELETE FROM persetujuan_permintaan_setoran_keuangan
				WHERE persetujuan_permintaan_setoran_keuangan_id =  $id";
		
		return $this->db->query($sql);
	}
}

/* End of file persetujuan_permintaan_setoran_keuangan.php */
/* Location: ./application/models/persetujuan_permintaan_setoran_keuangan.php */