<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Penjualan_obat_detail_m extends MY_Model {

	protected $_table        = 'penjualan_obat_detail';
	protected $_order_by     = 'inventory_id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		
	);

	

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		
	);


	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($gudang_id)
	{		
		$join_tables = array();

		// get params dari input postnya datatable

		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($wheres);

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($wheres);

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($wheres);

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

	// public function get_item_identitas($id)
	// {
	// 	$format = "SELECT
	// 				inventory.id,
	// 				inventory.item_id,
	// 				inventory_identitas.identitas_id,
	// 				inventory_identitas.judul,
	// 				inventory_identitas.`value`,
	// 				inventory_identitas.jumlah,
	// 				identitas.tipe
	// 				FROM
	// 				inventory
	// 				LEFT JOIN inventory_identitas ON inventory.id = inventory_identitas.inventory_id
	// 				LEFT JOIN identitas ON inventory_identitas.identitas_id = identitas.id
	// 				WHERE
	// 				inventory.id = $id";

	// 	return $this->db->query($format);
	// }
	
	public function get_item_satuan($item_id)
	{

		$format = "SELECT *
						FROM 
						inventory
						LEFT JOIN item_satuan on inventory.item_satuan_id = item_satuan.id
						WHERE inventory.item_id =  $item_id
						
						GROUP BY inventory.item_satuan_id
						ORDER BY 'inventory_id'";

		return $this->db->query($format);
	}


	public function get_item_identitas($id)
	{
		$format = "SELECT
					inventory_identitas.inventory_identitas_id as inventory_identitas_id,
					inventory_identitas.inventory_id,
					inventory_identitas.jumlah,
					inventory_identitas_detail.identitas_id,
					inventory_identitas_detail.judul,
					inventory_identitas_detail.`value`,
					identitas.tipe,
					inventory.harga_beli,
					inventory.jumlah as jumlah_inventory
					FROM
					inventory_identitas
					LEFT JOIN inventory_identitas_detail ON inventory_identitas.inventory_identitas_id = inventory_identitas_detail.inventory_identitas_id
					LEFT JOIN identitas ON inventory_identitas_detail.identitas_id = identitas.id
					LEFT JOIN inventory ON inventory_identitas.inventory_id = inventory.inventory_id
					WHERE
					inventory_identitas.inventory_identitas_id = $id
					";

		return $this->db->query($format);
	}

	public function update_jumlah_inventory($jumlah,$modified_by, $modified_date, $id){
		$format = "UPDATE inventory SET jumlah = $jumlah, modified_by = $modified_by, modified_date = '$modified_date' WHERE inventory_id =  $id";
		return $this->db->query($format);
	}

	public function cek_identitas($inventory_id)
	{
		$format = "SELECT *
					FROM `inventory_identitas`
					WHERE
					inventory_identitas.inventory_id = $inventory_id
					";

		return $this->db->query($format);
	}

	public function delete_inventory($inventory_id)
	{
		$format = "DELETE FROM inventory WHERE id = $inventory_id";

		return $this->db->query($format);
	}

	public function get_last_id()
	{
		$format = "SELECT MAX(id) FROM inventory";

		return $this->db->query($format);
	}

	public function insert_inventory($id, $gudang_id, $item_id, $item_satuan_id, $jumlah, $tanggal){

		$id_user = $this->session->userdata('user_id');
		$format="INSERT INTO inventory(id, gudang_id, item_id, item_satuan_id, jumlah, tanggal_datang, created_by, created_date)
				 VALUE ($id, $gudang_id, $item_id, $item_satuan_id, $jumlah, '$tanggal', $id_user, '$tanggal')";

		return $this->db->query($format);
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

	public function get_datatable_penjualan_obat($status_so_history)
	{	

		$join1 = array('item', $this->_table.'.item_id = item.id', 'left');
		$join2 = array('item_sub_kategori', 'item.item_sub_kategori = item_sub_kategori.id', 'left');
		$join3 = array('item_kategori', 'item_kategori.id = item_sub_kategori.item_kategori_id', 'left');
		$join4 = array('item_harga', 'item_harga.item_id = item.id', 'left');
		$join5 = array('item_satuan', 'item_satuan.item_id = item.id', 'left');
		$join6 = array('gudang', $this->_table.'.gudang_id = gudang.id', 'left');

		$join_tables = array($join1, $join2, $join3, $join4, $join5, $join6);
		
		if($status_so_history == 1) 
		{

			$kategori = 1;

		} elseif ($status_so_history == 2) 

		{

			$kategori = 2;

		}

		// $wheres = array(

		// 		'item_harga.cabang_id' => $cabang_id,
		// 		'item_satuan.is_primary' => 1,

		// 	);

		

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_item);
		// die_dump($params);
		$params["sort_by"] = "inventory.inventory_id";
		$params['sord_dir'] = "asc";
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		// $this->db->where($wheres);
		if ($status_so_history == null)
		{
			$this->db->where('item_kategori_id', 1);
			$this->db->or_where('item_kategori_id', 2);
			$this->db->group_by('id', 'asc');


		} else {

			$this->db->where('item_kategori_id', $kategori);
			$this->db->group_by('id', 'asc');
			
		}
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		// $this->db->where($wheres);
		if ($status_so_history == null)
		{
			$this->db->where('item_kategori_id', 1);
			$this->db->or_where('item_kategori_id', 2);
			$this->db->group_by('id', 'asc');


		} else {

			$this->db->where('item_kategori_id', $kategori);
			$this->db->group_by('id', 'asc');
		}
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		// $this->db->where($wheres);
		if ($status_so_history == null)
		{
			$this->db->where('item_kategori_id', 1);
			$this->db->or_where('item_kategori_id', 2);
			$this->db->group_by('id', 'asc');


		} else {
			$this->db->where('item_kategori_id', $kategori);
			$this->db->group_by('id', 'asc');
		}

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_item as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_item;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_nomor_penjualan()
	{
		$format = "SELECT MAX(SUBSTRING(`no_penjualan`,12,3)) AS max_nomor_penjualan FROM `penjualan_obat` WHERE SUBSTRING(`no_penjualan`,5,4) = DATE_FORMAT(NOW(), '%y%m')";	
		return $this->db->query($format);
	}

	public function get_max_id_penjualan_detail()
	{
		$format = "SELECT MAX(SUBSTRING(`id`,14,4)) AS max_id FROM `penjualan_obat_detail` WHERE SUBSTR(`id`,6,7) = DATE_FORMAT(NOW(), '%m-%Y');";	
		return $this->db->query($format);
	}

	public function get_data($penjualan_id)
	{
		$format = "SELECT
						penjualan_obat_detail.id,
						penjualan_obat_detail.penjualan_obat_id,
						penjualan_obat_detail.item_id,
						penjualan_obat_detail.item_satuan_id,
						penjualan_obat_detail.harga_jual,
						item.kode as kode,
						item.nama as nama,
						item_satuan.nama as nama_satuan,
						SUM(penjualan_obat_detail.jumlah) as jumlah
						FROM
						penjualan_obat_detail JOIN item
						ON penjualan_obat_detail.item_id = item.id JOIN item_satuan
						ON penjualan_obat_detail.item_satuan_id = item_satuan.id
						WHERE penjualan_obat_detail.penjualan_obat_id = '$penjualan_id'
						GROUP BY penjualan_obat_detail.item_id,penjualan_obat_detail.item_satuan_id";

		return $this->db->query($format);
	}

	public function get_data_detail($penjualan_id, $item_id, $item_satuan_id)
	{
		$format = "SELECT
						penjualan_obat_detail.id,
						penjualan_obat_detail.penjualan_obat_id,
						penjualan_obat_detail.item_id,
						penjualan_obat_detail.harga_jual,
						penjualan_obat_detail.item_satuan_id,
						penjualan_obat_detail.bn_sn_lot,
						penjualan_obat_detail.expire_date,
						penjualan_obat_detail.identitas_1,
						penjualan_obat_detail.identitas_2,
						penjualan_obat_detail.identitas_3,
						item.kode as kode,
						item.nama as nama,
						item_satuan.nama as nama_satuan,
						penjualan_obat_detail.jumlah
						FROM
						penjualan_obat_detail JOIN item
						ON penjualan_obat_detail.item_id = item.id JOIN item_satuan
						ON penjualan_obat_detail.item_satuan_id = item_satuan.id
						WHERE penjualan_obat_detail.penjualan_obat_id = '$penjualan_id'
						AND penjualan_obat_detail.item_id = '$item_id'
						AND penjualan_obat_detail.item_satuan_id = '$item_satuan_id'";

		return $this->db->query($format);
	}

	public function get_laporan_penjualan($tgl_awal,$tgl_akhir,$pasien_id,$nama_pasien,$item_id)
	{
		$SQL = "SELECT
					penjualan_obat.no_penjualan,
					penjualan_obat.pasien_id,
					penjualan_obat.nama_pasien,
					penjualan_obat.tanggal,
					penjualan_obat_detail.penjualan_obat_id, 
					penjualan_obat_detail.item_id, 
					penjualan_obat_detail.item_satuan_id, 
					penjualan_obat_detail.jumlah, 
					penjualan_obat_detail.bn_sn_lot,
					penjualan_obat_detail.expire_date,
					penjualan_obat_detail.harga_beli,
					penjualan_obat_detail.harga_jual,
					penjualan_obat_detail.diskon_persen,
					penjualan_obat_detail.diskon_nominal,
					penjualan_obat_detail.tipe_obat as tipe_item,
					item.nama,
					item_satuan.nama as satuan
				FROM
					`penjualan_obat_detail`
				JOIN penjualan_obat ON penjualan_obat_detail.penjualan_obat_id = penjualan_obat.id
				JOIN item ON penjualan_obat_detail.item_id = item.id
				JOIN item_satuan ON penjualan_obat_detail.item_satuan_id = item_satuan.id
				JOIN item_sub_kategori ON item.item_sub_kategori = item_sub_kategori.id
				WHERE penjualan_obat.tanggal >= '$tgl_awal' AND penjualan_obat.tanggal <= '$tgl_akhir' 
				AND penjualan_obat.pasien_id != 0 ";

		if($pasien_id != '-' && $nama_pasien != '-'){
			$SQL .= " AND penjualan_obat.pasien_id = '$pasien_id' AND penjualan_obat.nama_pasien = '$nama_pasien'";
		}
		if($item_id != '' && $item_id != 'null'){
            $item_array = str_replace('-', ',', $item_id);
            $SQL .= " AND penjualan_obat_detail.item_id IN ($item_array)";
        }

        $SQL .= "UNION ";
        $SQL .= "SELECT
					invoice.no_invoice as no_penjualan,
					invoice.pasien_id,
					invoice.nama_pasien,
					invoice.created_date as tanggal,
					invoice_detail.invoice_id as penjualan_obat_id, 
					invoice_detail.item_id, 
					invoice_detail.satuan_id as item_satuan_id, 
					invoice_detail.qty as jumlah, 
					invoice_detail.tipe as bn_sn_lot,
					invoice_detail.tipe as expire_date,
					invoice_detail.hpp as harga_beli,
					invoice_detail.harga as harga_jual,
					invoice_detail.diskon_persen,
					invoice_detail.diskon_nominal,
					invoice_detail.tipe_item as tipe_item,
					invoice_detail.nama_tindakan as nama,
					item_satuan.nama as satuan
				FROM invoice_detail JOIN invoice ON invoice_detail.invoice_id = invoice.id
				JOIN item ON invoice_detail.item_id = item.id
				LEFT JOIN item_satuan ON invoice_detail.satuan_id = item_satuan.id
				JOIN item_sub_kategori ON item.item_sub_kategori = item_sub_kategori.id
				WHERE invoice.penjamin_id = 1 AND date(invoice.created_date) >= '$tgl_awal' AND date(invoice.created_date) <= '$tgl_akhir' AND tipe_item != 1";

		if($pasien_id != '-' && $nama_pasien != '-'){
			$SQL .= " AND invoice.pasien_id = '$pasien_id' AND invoice.nama_pasien = '$nama_pasien'";
		}
		if($item_id != '' && $item_id != 'null'){
            $item_array = str_replace('-', ',', $item_id);
            $SQL .= " AND invoice_detail.item_id IN ($item_array)";
        }

		$SQL .= "	AND invoice_detail.item_id NOT IN (SELECT penjualan_obat_detail.item_id
					FROM
						`penjualan_obat_detail`
					JOIN penjualan_obat ON penjualan_obat_detail.penjualan_obat_id = penjualan_obat.id
				
					WHERE date(penjualan_obat.tanggal) = date(invoice.created_date) 
					
				AND penjualan_obat.pasien_id != 0 )";

		$SQL .= " ORDER BY
					tanggal ASC";

		return $this->db->query($SQL);

	}

	public function get_resep_pasien($pasien_id, $tgl)
	{
		$SQL = "SELECT * FROM `tindakan_resep_obat` WHERE `pasien_id` = '$pasien_id' AND date(`created_date`) = '$tgl'";

		return $this->db->query($SQL);
	}

	public function get_resep_pasien_identitas($resep_id, $item_id)
	{
		$resep_id_array = str_replace('-', ',', $resep_id);
		$resep_id_array = rtrim($resep_id_array,',');
		$SQL = "SELECT * FROM `tindakan_resep_obat_detail_identitas` JOIN item_satuan ON tindakan_resep_obat_detail_identitas.item_satuan_id = item_satuan.id WHERE `tindakan_resep_obat_id` IN ($resep_id_array) AND tindakan_resep_obat_detail_identitas.`item_id` = '$item_id'  ";
		
		return $this->db->query($SQL);
	}

	public function get_resep_dialyzer($pasien_id, $tgl)
	{
		$SQL = "SELECT * FROM `permintaan_dialyzer_baru` WHERE `pasien_id` = '$pasien_id' AND date(`created_date`) = '$tgl'";

		return $this->db->query($SQL);
	}

}

