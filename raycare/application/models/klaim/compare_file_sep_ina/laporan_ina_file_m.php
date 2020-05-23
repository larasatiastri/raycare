<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_ina_file_m extends MY_Model {

	protected $_table        = 'laporan_ina_file';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(

	);

	private $_fillable_edit = array(
		
	);

	protected $datatable_columns = array(
		//column di table          => alias
		'laporan_ina_file.Tglmsk'                                                 => 'Tglmsk',
		'laporan_ina_file.Norm'                                                   => 'Norm',
		'laporan_ina_file.NamaPasien'                                             => 'NamaPasien',
		"SUBSTRING_INDEX(laporan_ina_file.SEP, '^', 1)"                           => 'no_sep',
		"SUBSTRING_INDEX(SUBSTRING_INDEX(laporan_ina_file.SEP, '^', 2),'^' ,- 1)" => 'no_penjamin_ina',
		// 'pasien_penjamin.no_kartu'                                            => 'no_penjamin'

	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($tgl_awal,$tgl_akhir)
	{	
		$join1 = array('pasien',$this->_table.'.Norm = pasien.no_member','left');
		$join2 = array('tindakan_hd','pasien.id = tindakan_hd.pasien_id','left');
		// $join3 = array('pasien_penjamin','tindakan_hd.pasien_id = pasien_penjamin.pasien_id AND tindakan_hd.penjamin_id = pasien_penjamin.penjamin_id','left');
		
		$join_tables = array($join1);


		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by']	= 'laporan_ina_file.Tglmsk DESC, pasien.id';
		$params['sort_dir']	= 'desc';

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
		return $result; 
	}

	public function truncate_table()
	{
		$SQL = "TRUNCATE TABLE laporan_ina_file";

		return $this->db->query($SQL);
	}

	public function import_file($filename)
	{
		$SQL = "LOAD DATA INFILE '".config_item('file_sep_ina_ori_import').$filename."' INTO TABLE laporan_ina_file FIELDS TERMINATED BY ',' LINES TERMINATED BY '\n' IGNORE 1 LINES (nomor, @datemasukvar, @datepulangvar, no_mr, nama, no_sep , inacbg, top_up, total_tarif, tarif_rs, jenis ) SET tgl_masuk = STR_TO_DATE(@datemasukvar, '%d %M %Y'), tgl_pulang = STR_TO_DATE(@datepulangvar, '%d %M %Y'); ";
		
		return $this->db->query($SQL);
	}

	public function update_data($data,$wheres)
	{
		$this->db->where($wheres);
        return $this->db->update($this->_table,$data);
	}

	public function export_file($filename)
	{
		$SQL = "SELECT 'Kdrs','Klsrs','Norm','Klsrawat','TariffRS','Jnsrawat','Tglmsk','Tglklr','Los','Tgllhr','UmurThn','UmurHari','JK','CaraPlg','Berat','Dutama',
'D1','D2','D3','D4','D5','D6','D7','D8','D9','D10','D11','D12','D13','D14','D15','D16','D17','D18','D19','D20','D21','D22','D23','D24','D25','D26',
'D27','D28','D29','P1','P2','P3','P4','P5','P6','P7','P8','P9','P10','P11','P12','P13','P14','P15','P16','P17','P18','P19','P20','P21','P22','P23',
'P24','P25','P26','P27','P28','P29','P30','adl','Recid','Inacbg','Deskripsi','Tarif','SA','TarifSA','SP','DescSP','TarifSP','SR','DescSR','TarifSR',
'SI','DescSI','TarifSI','SD','DescSD','TarifSD','TotalTarif','NamaPasien','DPJP','SEP','Rujukan','PengesahanSL3','VersiINACBG',REPLACE('C1:C2:C3
3174097',':',';')
UNION ALL
SELECT *
FROM laporan_ina_file
INTO OUTFILE '".config_item('file_ina_modif_exp').$filename."'
FIELDS TERMINATED BY ';'
ESCAPED BY ''
LINES TERMINATED BY ';-;'";
		
		return $this->db->query($SQL);
	}

	public function get_all()
	{
		return $this->db->get($this->_table);
	}

	public function get_data_ina_double($tgl_awal, $tgl_akhir)
	{
		$SQL = "SELECT
					*
				FROM
					`laporan_ina_file`
				WHERE DATE(tgl_pulang) >= '$tgl_awal'
				AND DATE(tgl_pulang) <= '$tgl_akhir'

				GROUP BY
					no_sep, date(tgl_pulang)

				HAVING (COUNT(no_sep) > 1 AND COUNT(date(tgl_pulang)) > 1)
				ORDER BY tgl_pulang ASC";
				
		return $this->db->query($SQL);
	}

	public function get_data_ina_tanpa_sep($tgl_awal, $tgl_akhir)
	{
		$SQL = "SELECT
					*
				FROM
					`laporan_ina_file`
				WHERE DATE(tgl_pulang) >= '$tgl_awal'
				AND DATE(tgl_pulang) <= '$tgl_akhir'
				AND no_sep NOT IN (SELECT no_sep FROM laporan_sep_file)
				AND no_sep <> ''

				GROUP BY
					no_sep, date(tgl_pulang)

				ORDER BY tgl_pulang ASC";
				
		return $this->db->query($SQL);
	}
}
