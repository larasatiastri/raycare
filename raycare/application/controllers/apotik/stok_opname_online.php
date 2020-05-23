<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stok_opname_online extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = 'f92040676a49366c830a41f4b5b41df9';                  // untuk check bit_access

    private $menus = array();
    
    private $menu_tree = array();       // untuk keperluan item menu dengan class 'open', 'selected'

    public function __construct()
    {       
        parent::__construct();

        if( !$this->input->is_ajax_request())
        {
            $user_level = $this->user_m->level_id();
            $this->menus = $this->user_level_menu_m->get_nested($user_level);
        }

        $this->load->model('master/item/item_m');
        $this->load->model('master/item/item_satuan_m');
        $this->load->model('master/item/item_kategori_m');
        $this->load->model('master/item/item_sub_kategori_m');
        $this->load->model('apotik/item_identitas_m');
        $this->load->model('apotik/inventory_history_m');
        $this->load->model('apotik/inventory_history_detail_m');
        $this->load->model('apotik/inventory_history_identitas_m');
        $this->load->model('apotik/inventory_history_identitas_detail_m');
        $this->load->model('apotik/gudang_m');
        // $this->load->model('apotik/retur_pembelian/item_identitas_m');
        // $this->load->model('apotik/inventory_m');    
        $this->load->model('apotik/stok_opname_online/stok_opname_online_set_m');
        $this->load->model('apotik/stok_opname_online/stok_opname_online_m');
        $this->load->model('apotik/stok_opname_online/stok_opname_online_detail_m');
        $this->load->model('apotik/stok_opname_online/stok_opname_online_identitas_m');
        $this->load->model('apotik/stok_opname_online/stok_opname_online_identitas_detail_m');
        $this->load->model('apotik/stok_opname_online/inventory_m');
        $this->load->model('apotik/stok_opname_online/inventory_identitas_m');
        $this->load->model('apotik/stok_opname_online/inventory_identitas_detail_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/apotik/stok_opname_online/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Stok Opname Online', $this->session->userdata('language')), 
            'header'         => translate('Stok Opname Online', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'apotik/stok_opname_online/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    /**
     * [list description]
     * @return [type] [description]
     */
    public function listing($gudang_id=null, $kategori=null, $sub_kategori=null, $item_id=null)
    {        
        $result = $this->item_m->get_datatable_stok($kategori, $sub_kategori, $item_id);
        // die_dump($result);
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        // die_dump($records);

        $i=0;
        $action = '';
        $user = $this->session->userdata('user_id');
        // $so_id = $this->stok_opname_online_m->get_by(array('user_id' => $user, 'status' => 1));
        // $so_id_data = object_to_array($so_id);
        foreach($records->result_array() as $row)
        {
            $item_satuan = $this->item_satuan_m->get_by(array('item_id' => $row['item_id']));

            $option_satuan = array();
            foreach ($item_satuan as $satuan) {
                $option_satuan[$satuan->id] = $satuan->nama;
            }

            $so_online = $this->stok_opname_online_set_m->get_by(array('user_id !=' => $user, 'gudang_id' => $gudang_id, 'item_id' => $row['item_id']));
            // die_dump($this->db->last_query());

            if($so_online)
            {
                $action = '';
            }
            else
            {
                $identitas = $this->item_m->get_by(array('id' => $row['item_id']));
                $identitas_data = object_to_array($identitas);
                
                // die_dump($so_id_data);
                if($identitas_data[0]['is_identitas'] == 1)
                {
                    $action = '<a title="'.translate('Input Stok', $this->session->userdata('language')).'" href="'.base_url().'apotik/stok_opname_online/modal_identitas/'.$row['item_id'].'/'.$row['item_satuan_id'].'/'.$i.'/'.$gudang_id.'" data-target="#ajax_notes1" data-toggle="modal" class="btn btn-primary input-stok-modal"><i class="fa fa-sort-amount-asc"></i></a>';
                }
                else
                {
                    $action = '<a title="'.translate('Input', $this->session->userdata('language')).'" class="btn btn-primary input-stok" id="input_stok_'.$i.'" data-id="'.$i.'" data-item="'.htmlentities(json_encode($row)).'" data-row="'.$i.'"><i class="fa fa-sort-amount-asc"></i></a>
                        <a title="'.translate('Save', $this->session->userdata('language')).'" data-id="'.$i.'" class="btn green-haze input-stok-save hidden" id="save_stok_'.$i.'" data-item="'.htmlentities(json_encode($row)).'" data-row="'.$i.'"><i class="fa fa-check"></i></a>
                        <a title="'.translate('Back', $this->session->userdata('language')).'" data-id="'.$i.'" class="btn yellow input-stok-back hidden" id="back_stok_'.$i.'" data-item="'.htmlentities(json_encode($row)).'" data-row="'.$i.'"><i class="fa fa-undo"></i></a>';
                }                
            }

            $jumlah_item = '0';

            $search = '';
            $data_jumlah = $this->inventory_m->get_data_inventory_satuan($row['item_id'],$row['item_satuan_id'],$gudang_id)->result_array();
            // die(dump($this->db->last_query()));
            if(count($data_jumlah)){
                $jumlah_item = '';
                foreach ($data_jumlah as $row_jumlah) {
                    $jumlah_item = $row_jumlah['jumlah'].' '.$row_jumlah['nama_satuan'].', ';
                }

                $jumlah_item = rtrim($jumlah_item,', ');
            }

            $output['data'][] = array(
                $i,
                '<div class="text-center">'.$row['item_kode'].'</div>',
                '<input type="hidden" id="items['.$i.'][item_id]" name="items['.$i.'][item_id]" value="'.$row['item_id'].'">'.$row['item_nama'].' ['.$row['satuan'].']<div id="simpan_identitas_'.$i.'" class="hidden"></div>',
                '<input type="hidden" id="items['.$i.'][item_satuan_id]" name="items['.$i.'][item_satuan_id]" value="'.$row['item_satuan_id'].'">
                 <input type="hidden" id="jumlahIn_'.$i.'" name="items['.$i.'][jumlah]">
                 <input type="hidden" id="jumlahFin_'.$i.'" name="items['.$i.'][jumlahfin]">
                 <input type="hidden" id="jumlahCh_'.$i.'" name="items['.$i.'][jumlahCh]"><div class="text-left">'.$row['kategori'].' / '.$row['sub_kategori'].'</div>',
                '<div class="input-group stok-jumlah hidden" id="input_jumlah_'.$i.'">
                        <input type="number" class="form-control" min="0" value="" id="jumlah_'.$i.'" name="jumlah[]" data-row="'.$i.'">'.form_dropdown('satuan_id', $option_satuan, '','id="satuan_id" class="form-control"').'
                </div><div class="text-left"><label id="jumlahEl_'.$i.'">'.$jumlah_item.'</label></div>',
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_search_item($kategori_sub = null)
    {
        // $id = '1';
        $result = $this->item_m->get_datatable_item($kategori_sub);
        // die_dump($this->db->last_query()); 

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        $i=0;
        foreach($records->result_array() as $row)
        {
            $action = '';
            if($row['is_active']==1)
            {
                
                $action = '<a title="'.translate('Select', $this->session->userdata('language')).'" data-id="'.$i.'" data-item="'.htmlentities(json_encode($row)).'" " class="btn btn-primary select"><i class="fa fa-check"></i></a>';

                // die_dump($info_stok);

                $output['aaData'][] = array(
                    '<div class="text-center">'.$row['id'].'</div>',
                    '<div class="text-center">'.$row['kode'].'</div>',
                    $row['nama'],
                    '<div class="text-center">'.$row['kategori_item'].'</div>',
                    '<div class="text-center">'.$row['keterangan'].'</div>',
                    '<div class="text-center">'.$action.'</div>',
                );
            $i++;
            }

            
        }

        echo json_encode($output);
    }

    public function modal_identitas($item_id, $item_satuan_id, $row, $gudang)
    {
        $satuan = $this->item_satuan_m->get_by(array('id' => $item_satuan_id));
        $nama_satuan = object_to_array($satuan);

        // die_dump($nama_satuan);

        $data = array(
            'row_id' => $row,
            'item_id' => $item_id,
            'item_satuan_id' => $item_satuan_id,
            'item_satuan_nama' => $nama_satuan[0]['nama'],
            'gudang_id' => $gudang
        );

        // die_dump($data);
        $this->load->view('apotik/stok_opname_online/modals/modal_identitas', $data);
    
    }

    public function show_modal_identitas()
    {
        $gudang_id = $this->input->post('gudang_id');
        $item_id = $this->input->post('item_id');
        $item_satuan_id = $this->input->post('item_satuan_id');
        $search = $this->input->post('like');

        $html = '';
        $item_identitas= $this->item_identitas_m->get_item_identitas($item_id)->result_array(); 
        $identitas_row_template='';
        $check;
        $info_id_inventory = '';
        $item_identitas_detail = '';
            
        $type = '';
        $get_group_indetitas = $this->inventory_m->get_data_inventory($item_id,$item_satuan_id,$gudang_id,$search)->result_array();
        // die(dump($this->db->last_query()));
        $i = 1;
        if(count($get_group_indetitas) != 0){
            $total = 1;
            foreach ($get_group_indetitas as $group) {
                $type .= '<tr id="identitas_row_'.$total.'" class="table_item"><td>
                        <label class="control-label">'.$group['bn_sn_lot'].'</label>
                        <input type="hidden" id="inventory_detail_'.$total.'_bn_sn_lot" name="inventory_detail['.$total.'][bn_sn_lot]" value="'.$group['bn_sn_lot'].'">
                      </td>';

                $type .= '<td>
                        <label class="control-label">'.date('d M Y', strtotime($group['expire_date'])).'</label>
                        <input type="hidden" id="inventory_detail_'.$total.'_expire_date" name="inventory_detail['.$total.'][expire_date]" value="'.$group['expire_date'].'">
                      </td>';
       
                $type .= '<td><label class="control-label">'.$group['jumlah'].'</label>
                        <input type="hidden" id="inventory_detail_'.$total.'_stok" name="inventory_detail['.$total.'][stock]" class="stock_item" value="'.$group['jumlah'].'">
                     </td>
                      <td>
                        <input type="number" class="form-control text-right jumlah_item" id="inventory_detail_'.$total.'_jumlah" name="inventory_detail['.$total.'][jumlah]" min="0" value="'.$group['jumlah'].'"> 
                        <input type="hidden" id="inventory_detail_'.$total.'_inventory_id" name="inventory_detail['.$total.'][inventory_id]" value="'.$group['inventory_id'].'">
                        
                      </td></tr>';
                $total++;
            }

            $identitas_row_template .=  $type;

            $i = $total;
        }
        else{
            $i = 1;
        }
            
            $type_kosong = '';
            
            $type_kosong .= '<td><input type="text" class="form-control send-input" id="inventory_detail_{0}" name="inventory_detail[{0}][bn_sn_lot]" placeholder="Batch Number"></td>'; 
            $type_kosong .= '<td><div class="input-group date"> <input type="text" class="form-control send-input" id="inventory_detail_{0}" name="inventory_detail[{0}][expire_date]" placeholder="Expire Date" readonly="readonly"> <span class="input-group-btn"> <button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button> </span> </div></td>';
            $type_kosong .= '<td></td>
                          <td>
                            <input type="number" class="form-control text-right jumlah_item" id="identitas_jumlah_{0}" name="inventory_detail[{0}][jumlah]" min="0"  data-row="{0}" value="0">
                            <input type="hidden" id="inventory_detail_{0}_inventory_id" name="inventory_detail[{0}][inventory_id]" value="">
                          </td>';

            $identitas_row =  '<tr id="identitas_row_{0}" class="table_item">'.$type_kosong.'</tr>';
        
        
        $html .='<span id="tpl_identitas" class="hidden">'.htmlentities($identitas_row).'</span>';

        $html .= '<table class="table table-striped table-bordered table-hover" id="table_identitas">
            <thead>
                <tr class="heading">
                    <th class="text-center" style="width : 16% !important;">'.translate('Batch Number', $this->session->userdata("language")).'</th>
                    <th class="text-center" style="width : 16% !important;">'.translate('Expire Date', $this->session->userdata("language")).'</th>';
                        
                $html .= '<th class="text-center" style="width : 5% !important;">'.translate("Stock", $this->session->userdata("language")).'</th>
                    <th class="text-center" style="width : 15% !important;">'.translate("Jumlah", $this->session->userdata("language")).'</th>
                </tr>
            </thead>
                
            <tbody>'; 
                if($identitas_row_template != ''){
                        $html.= $identitas_row_template;
                    
                    
                }
                $html.= '<input type="hidden" id="identitasCounter" name="identitasCounter" value="'.$i.'">';
                // $html.= $type;
        $html .='</tbody>
        </table>';

        echo $html;
    }

    public function modal_keterangan($so_id)
    {
        $data = array(
            'so_id' => $so_id
        );
        $this->load->view('apotik/stok_opname_online/modals/modal_keterangan.php', $data);
    
    }

    public function save()
    {
        // die_dump($this->input->post());

        $array_input = $this->input->post();

        $user_id = $this->session->userdata('user_id');
        $so_active = $this->stok_opname_online_m->get_by(array('user_id' => $user_id, 'status' => 1), true);
        $i = 1;

        $data_inv_history = array(
            'transaksi_id'  => $so_active->id,
            'transaksi_tipe' => 7
        );
        $inv_history_id = $this->inventory_history_m->save($data_inv_history);

        foreach ($array_input['inventory_detail'] as $data) {
            if($data['inventory_id'] != '')
            {
                if($data['jumlah'] != $data['stock'] && $data['jumlah'] != 0){
                    $data_sod = array(
                        'stok_opname_online_id' => $so_active->id,
                        'item_id'        => $array_input['item_id'],
                        'item_satuan_id' => $array_input['item_satuan_id'],
                        'jumlah_sistem'  => $data['stock'],
                        'jumlah_input'   => $data['jumlah'],
                        'bn_sn_lot'      => $data['bn_sn_lot'],
                        'expire_date'    =>date('Y-m-d', strtotime($data['expire_date'])),
                        'is_active'      => 1
                    );

                    $save_sod_id = $this->stok_opname_online_detail_m->save($data_sod);
                    // die(dump($this->db->last_query()));
                    // die(dump($data['jumlah']));
                    if($data['jumlah'] != 0 && $data['jumlah'] > $data['stock']){
                        $inventory_data  = $this->inventory_m->get_by(array('item_id' => $array_input['item_id'], 'item_satuan_id' => $array_input['item_satuan_id'], 'bn_sn_lot' => $data['bn_sn_lot'], 'expire_date' => date('Y-m-d', strtotime($data['expire_date']))), true);

                        $wheres_inv_jml = array(
                            'jumlah' =>($inventory_data->jumlah) +  ($data['jumlah'] - $data['stock'])
                        );

                        $this->inventory_m->update_by($user_id,$wheres_inv_jml,(array('inventory_id' => $inventory_data->inventory_id)));
                        // die(dump($this->db->last_query()));

                        $data_inv_hist_det = array(
                            'inventory_history_id' => $inv_history_id,
                            'gudang_id'            => $array_input['gudang_id'],
                            'pmb_id'               => 0,
                            'item_id'              => $array_input['item_id'],
                            'item_satuan_id'       => $array_input['item_satuan_id'],
                            'initial_stock'        => $data['stock'],                
                            'change_stock'         => $data['jumlah']-$data['stock'],
                            'final_stock'          => $data['jumlah'],
                            'bn_sn_lot'            => $data['bn_sn_lot'],
                            'expire_date'          =>date('Y-m-d', strtotime($data['expire_date'])),
                            'harga_beli'           => $data['harga_beli'],
                            'total_harga'          => ($data['harga_beli']*($data['jumlah']-$data['stock']))
                        );
                        $inv_history_det_id = $this->inventory_history_detail_m->save($data_inv_hist_det);
                    }

                    if($data['jumlah'] != 0 && $data['jumlah'] < $data['stock']){

                        $inventory_data  = $this->inventory_m->get_by(array('item_id' => $array_input['item_id'], 'item_satuan_id' => $array_input['item_satuan_id'], 'bn_sn_lot' => $data['bn_sn_lot'], 'expire_date' => date('Y-m-d', strtotime($data['expire_date']))));
                        $inventory_data = object_to_array($inventory_data);

                        $x = 1;
                        $sisa = 0;
                        $selisih = $data['stok'] - $data['jumlah'];

                        foreach ($inventory_data as $row_inv) {
                            if($x == 1 && $selisih >= $row_inv['jumlah']){

                                $sisa = $selisih - $row_inv['jumlah'];
                                $sisa_inv = 0;

                                $data_inventory_history_detail = array(
                                    'inventory_history_id' => $inventory_history_id,
                                    'gudang_id'            => $row_inv['gudang_id'],
                                    'pmb_id'               => $row_inv['pmb_id'],
                                    'pembelian_detail_id'  => $row_inv['pembelian_detail_id'],
                                    'box_paket_id'         => NULL,
                                    'kode_box_paket'       => NULL,
                                    'item_id'              => $row_inv['item_id'],
                                    'item_satuan_id'       => $row_inv['item_satuan_id'],
                                    'initial_stock'        => $row_inv['jumlah'],
                                    'change_stock'         => ($row_inv['jumlah'] * (-1)),
                                    'final_stock'          => $sisa_inv,
                                    'harga_beli'           => $row_inv['harga_beli'],
                                    'total_harga'        => $row_inv['jumlah'] * $row_inv['harga_beli'],
                                    'bn_sn_lot'            => $row_inv['bn_sn_lot'],
                                    'expire_date'          => $row_inv['expire_date'],
                                    'created_by'           => $this->session->userdata('user_id'),
                                    'created_date'         => date('Y-m-d H:i:s')
                                );

                                $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                                $delete_inventory = $this->inventory_m->delete_by(array('inventory_id' => $row_inv['inventory_id']));
                            }
                            if($x == 1 && $selisih < $row_inv['jumlah']){

                                $sisa = 0;
                                $sisa_inv = $row_inv['jumlah'] - $selisih;

                                $data_inventory_history_detail = array(
                                    'inventory_history_id' => $inventory_history_id,
                                    'gudang_id'            => $row_inv['gudang_id'],
                                    'pmb_id'               => $row_inv['pmb_id'],
                                    'pembelian_detail_id'  => $row_inv['pembelian_detail_id'],
                                    'box_paket_id'         => NULL,
                                    'kode_box_paket'       => NULL,
                                    'item_id'              => $row_inv['item_id'],
                                    'item_satuan_id'       => $row_inv['item_satuan_id'],
                                    'initial_stock'        => $row_inv['jumlah'],
                                    'change_stock'         => ($selisih * (-1)),
                                    'harga_beli'           => $row_inv['harga_beli'],
                                    'total_harga'          => $selisih * $row_inv['harga_beli'],
                                    'final_stock'          => $sisa_inv,
                                    'bn_sn_lot'            => $row_inv['bn_sn_lot'],
                                    'expire_date'          => $row_inv['expire_date'],
                                    'created_by'           => $this->session->userdata('user_id'),
                                    'created_date'         => date('Y-m-d H:i:s')
                                );

                                $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                                $update_inventory = $this->inventory_m->update_by($this->session->userdata('user'),array('jumlah' => $sisa_inv),array('inventory_id' => $row_inv['inventory_id']));
                            }

                            if($x != 1 && $sisa > 0 && $sisa >= $row_inv['jumlah']){

                                $sisa = $sisa - $row_inv['jumlah'];
                                $sisa_inv = 0;

                                $data_inventory_history_detail = array(
                                    'inventory_history_id' => $inventory_history_id,
                                    'gudang_id'            => $array_input['gudang_ke'],
                                    'pmb_id'               => $row_inv['pmb_id'],
                                    'pembelian_detail_id'  => $row_inv['pembelian_detail_id'],
                                    'box_paket_id'         => NULL,
                                    'kode_box_paket'       => NULL,
                                    'item_id'              => $row_inv['item_id'],
                                    'item_satuan_id'       => $row_inv['item_satuan_id'],
                                    'initial_stock'        => $row_inv['jumlah'],
                                    'change_stock'         => ($row_inv['jumlah'] * (-1)),
                                    'final_stock'          => $sisa_inv,
                                    'harga_beli'           => $row_inv['harga_beli'],
                                    'total_harga'          => $row_inv['jumlah'] * $row_inv['harga_beli'],
                                    'bn_sn_lot'            => $row_inv['bn_sn_lot'],
                                    'expire_date'          => $row_inv['expire_date'],
                                    'created_by'           => $this->session->userdata('user_id'),
                                    'created_date'         => date('Y-m-d H:i:s')
                                );

                                $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                                $delete_inventory = $this->inventory_m->delete_by(array('inventory_id' => $row_inv['inventory_id']));
                            }
                            if($x != 1 && $sisa > 0 && $sisa < $row_inv['jumlah']){

                                $sisa_inv = $row_inv['jumlah'] - $sisa;

                                $data_inventory_history_detail = array(
                                    'inventory_history_id' => $inventory_history_id,
                                    'gudang_id'            => $array_input['gudang_ke'],
                                    'pmb_id'               => $row_inv['pmb_id'],
                                    'pembelian_detail_id'  => $row_inv['pembelian_detail_id'],
                                    'box_paket_id'         => NULL,
                                    'kode_box_paket'       => NULL,
                                    'item_id'              => $row_inv['item_id'],
                                    'item_satuan_id'       => $row_inv['item_satuan_id'],
                                    'initial_stock'        => $row_inv['jumlah'],
                                    'change_stock'         => ($sisa * (-1)),
                                    'final_stock'          => $sisa_inv,
                                    'harga_beli'           => $row_inv['harga_beli'],
                                    'total_harga'          => $sisa * $row_inv['harga_beli'],
                                    'bn_sn_lot'            => $row_inv['bn_sn_lot'],
                                    'expire_date'          => $row_inv['expire_date'],
                                    'created_by'           => $this->session->userdata('user_id'),
                                    'created_date'         => date('Y-m-d H:i:s')
                                );

                                $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                                $sisa = 0;

                                $update_inventory = $this->inventory_m->update_by($this->session->userdata('user'),array('jumlah' => $sisa_inv),array('inventory_id' => $row_inv['inventory_id']));
                            }

                            $x++; 
                        }
                    }
                }
                if($data['jumlah'] == 0){
                    $data_sod = array(
                        'stok_opname_online_id' => $so_active->id,
                        'item_id'        => $array_input['item_id'],
                        'item_satuan_id' => $array_input['item_satuan_id'],
                        'jumlah_sistem'  => $data['stock'],
                        'jumlah_input'   => $data['jumlah'],
                        'bn_sn_lot'      => $data['bn_sn_lot'],
                        'expire_date'    =>date('Y-m-d', strtotime($data['expire_date'])),
                        'is_active'      => 1
                    );

                    $save_sod_id = $this->stok_opname_online_detail_m->save($data_sod);
                    
                
                    $wheres_inv['inventory_id'] = $data['inventory_id'];

                    $delete_inv = $this->inventory_m->delete_by($wheres_inv);

                    

                    $data_inv_hist_det = array(
                        'inventory_history_id' => $inv_history_id,
                        'gudang_id'            => $array_input['gudang_id'],
                        'pmb_id'               => 0,
                        'item_id'              => $array_input['item_id'],
                        'item_satuan_id'       => $array_input['item_satuan_id'],
                        'initial_stock'        => $data['stock'],                
                        'change_stock'         => $data['jumlah']-$data['stock'],
                        'final_stock'          => $data['jumlah'],
                        'bn_sn_lot'      => $data['bn_sn_lot'],
                        'expire_date'    =>date('Y-m-d', strtotime($data['expire_date'])),
                        'harga_beli'           => $data['harga_beli'],
                        'total_harga'          => ($data['harga_beli']*($data['jumlah']-$data['stock']))
                    );
                    $inv_history_det_id = $this->inventory_history_detail_m->save($data_inv_hist_det);
                   
                }
                // $update = $this->stok_opname_online_detail_m->update_identitas($save_so, $array_input['total_stock'], $array_input['total_jumlah']);
            }
            elseif($data['inventory_id'] == '')
            {
                if($data['jumlah'] != '' && $data['jumlah'] != 0){
                    $id_inventory = $this->inventory_m->get_id()->result_array();
                    $data_inventory_id = $id_inventory[0]['id']+1;
                    $date_item =  date('Y-m-d H:i:s');

                    $data_sod = array(
                        'stok_opname_online_id' => $so_active->id,
                        'item_id'        => $array_input['item_id'],
                        'item_satuan_id' => $array_input['item_satuan_id'],
                        'jumlah_sistem'  => $data['stock'],
                        'jumlah_input'   => $data['jumlah'],
                        'bn_sn_lot'      => $data['bn_sn_lot'],
                        'expire_date'    =>date('Y-m-d', strtotime($data['expire_date'])),
                        'is_active'      => 1
                    );

                    $save_sod_id = $this->stok_opname_online_detail_m->save($data_sod);

                    $data_inventory = array(
                        'inventory_id'         => $data_inventory_id,
                        'gudang_id'            => $array_input['gudang_id'],
                        'pmb_id'               => 0,
                        'pembelian_detail_id'  => 0,
                        'item_id'              => $array_input['item_id'],
                        'item_satuan_id'       => $array_input['item_satuan_id'],
                        'jumlah'               => $data['jumlah'],
                        'harga_beli'               => 0,
                        'tanggal_datang'       => date('Y-m-d'),
                        'bn_sn_lot'            => $data['bn_sn_lot'],
                        'expire_date'          =>date('Y-m-d', strtotime($data['expire_date'])),
                        'harga_beli'           => 0,
                        'created_by'           => $user_id,
                        'created_date'           => date('Y-m-d H:i:s'),
                    );
                    $inv_history_det_id = $this->inventory_m->add_data($data_inventory);

                    $data_inv_hist_det = array(
                        'inventory_history_id' => $inv_history_id,
                        'gudang_id'            => $array_input['gudang_id'],
                        'pmb_id'               => 0,
                        'item_id'              => $array_input['item_id'],
                        'item_satuan_id'       => $array_input['item_satuan_id'],
                        'initial_stock'        => 0,                
                        'change_stock'         => $data['jumlah'],
                        'final_stock'          => $data['jumlah'],
                        'bn_sn_lot'            => $data['bn_sn_lot'],
                        'expire_date'          => date('Y-m-d', strtotime($data['expire_date'])),
                        'harga_beli'           => $data['harga_beli'],
                        'total_harga'          => ($data['harga_beli']*($data['jumlah']-$data['stock']))
                    );
                    $inv_history_det_id = $this->inventory_history_detail_m->save($data_inv_hist_det);
                    
                }// die_dump($this->db->last_query());
            }

        $i++;
        }
    }

    public function save_history_so()
    {
        // die_dump($this->input->post());
        $array_input = $this->input->post();

        $data = array(
            'transaksi_id'  => $array_input['so_id'],
            'transaksi_tipe'  => 7
        );

        $inventory_history_id = $this->inventory_history_m->save($data);

        foreach ($array_input['items'] as $data) {
            
            $data_detail = array(
                'inventory_history_id'  => $inventory_history_id,
                'gudang_id'     => $array_input['sub_gudang'],
                'item_id'       => $data['item_id'],
                'item_satuan_id'=> $data['item_satuan_id'],
                'initial_stock' => $data['jumlah'],
                'change_stock'  => $data['jumlahCh'],
                'final_stock'   => $data['jumlahfin']
            );

            $inventory_history_detail = $this->inventory_history_detail_m->save($data_detail);

            $so_detail = array(
                'stok_opname_online_id'  => $array_input['so_id'],
                'item_id'  => $data['item_id'],
                'item_satuan_id'  => $data['item_satuan_id'],
                'jumlah_sistem'   => $data['jumlah'],
                'jumlah_input'    => $data['jumlahfin'],
                'is_active'     => 1
            );

            $so_online_detail = $this->stok_opname_online_detail_m->save($so_detail);

            $i = 1;
            foreach ($array_input['identitas_'.$data['item_id']] as $data_identitas) {
                
                $data_history_identitas = array(
                    'inventory_history_detail_id'  => $inventory_history_detail,
                    'jumlah'    => $data_identitas['jumlah']
                );

                $inventory_history_identitas = $this->inventory_history_identitas_m->save($data_history_identitas);

                $so_identitas = array(
                    'stok_opname_online_detail_id'  => $so_online_detail,
                    'jumlah_sistem'    => $data_identitas['stock'],
                    'jumlah_input'    => $data_identitas['jumlah'],
                );

                $so_online_identitas = $this->stok_opname_online_identitas_m->save($so_identitas);
                // die_dump($inventory_history_identitas);
                foreach ($array_input['identitas_detail_'.$data['item_id'].'_'.$i] as $data_detail) {
                    
                    $data_history_identitas_detail = array(
                        'inventory_history_identitas_id'  => $inventory_history_identitas,
                        'identitas_id'    => $data_detail['id'],
                        'judul'     => $data_detail['judul'],
                        'value'     => $data_detail['value']
                    );

                    // die_dump($data_history_identitas_detail);
                    $inventory_history_identitas_detail = $this->inventory_history_identitas_detail_m->save($data_history_identitas_detail);

                    $so_identitas_detail = array(
                        'stok_opname_online_identitas_id'  => $so_online_identitas,
                        'identitas_id'    => $data_detail['id'],
                        'judul'     => $data_detail['judul'],
                        'value'     => $data_detail['value']
                    );

                    // die_dump($data_history_identitas_detail);
                    $so_online_identitas_detail = $this->stok_opname_online_identitas_detail_m->save($so_identitas_detail);
                    // die_dump($this->db->last_query());
                }
            $i++;
            }
        }     
    }

    public function get_sub_kategori()
    {
        $id_kategori = $this->input->post('id_kategori');
        //die_dump($id_negara);
        $sub_kategori = $this->item_sub_kategori_m->get_data_sub_kategori($id_kategori)->result_array();
        //die_dump($this->db->last_query());        
        $item_sub_kategori  = object_to_array($sub_kategori);

        //die_dump($this->db->last_query());
        echo json_encode($item_sub_kategori);
    }

    public function data_inventory()
    {
        $gudang_id = $this->input->post('gudang_id');
        $item_id = $this->input->post('item_id');
        $item_satuan_id = $this->input->post('item_satuan_id');
        $data_jumlah_awal = $this->input->post('jumlah_awal');
        $data_jumlah = $this->input->post('jumlah');
        $so_id = $this->input->post('so_id');
        
        $data_item = $this->inventory_m->get_by(array('item_id' => $item_id, 'item_satuan_id' => $item_satuan_id));
        // die_dump($this->db->last_query());
        if($data_item)
        {
            $inventory_item = object_to_array($data_item);
            
            $change_stok = 0;
            $stok = 0;
            if($data_jumlah_awal < $data_jumlah) {
                $change_stok = intval($data_jumlah)-intval($data_jumlah_awal);
                $stok = $inventory_item[0]['jumlah']+$change_stok;

                $update_jumlah = $this->inventory_m->save_data($stok, $inventory_item[0]['inventory_id']);
                // die_dump($this->db->last_query());
                // die_dump($change_stok);

                $data_inv_history = array(
                    'transaksi_id'  => $inventory_item[0]['inventory_id'],
                    'transaksi_tipe' => 7
                );
                $inv_history_id = $this->inventory_history_m->save($data_inv_history);

                $data_sod = array(
                    'stok_opname_online_id' => $so_id,
                    'item_id'        => $item_id,
                    'item_satuan_id' => $item_satuan_id,
                    'jumlah_sistem'  => $data_jumlah_awal,
                    'jumlah_input'   => $data_jumlah,
                    'is_active'      => 1
                );

                $save_sod_id = $this->stok_opname_online_detail_m->save($data_sod);

                $data_inv_hist_det = array(
                    'inventory_history_id' => $inv_history_id,
                    'gudang_id' => 1,
                    'pmb_id'    => 0,
                    'item_id'   => $item_id,
                    'item_satuan_id'    => $item_satuan_id,
                    'initial_stock' => $data_jumlah_awal,                
                    'change_stock'    => $data_jumlah-$data_jumlah_awal,
                    'final_stock'   => (0+$data_jumlah),
                    'harga_beli'    => 0,
                    'total_harga' => 0 * (0+$data_jumlah)
                );
                $inv_history_det_id = $this->inventory_history_detail_m->save($data_inv_hist_det);
            }
            else
            {
                $stok = intval($data_jumlah_awal)-intval($data_jumlah);
                // die_dump($stok);
                foreach ($inventory_item as $data_inven_item) 
                {
                    if($data_inven_item['jumlah'] < $stok)
                    {
                        $stok = $stok-$data_inven_item['jumlah'];
                        // die_dump($stok);
                        $delete=$this->inventory_m->delete_inventory($data_inven_item['inventory_id']);
                    }
                    else
                    {
                        $stok = $data_inven_item['jumlah']-$stok;
                        // die_dump($stok);
                        $update_jumlah = $this->inventory_m->save_data($stok, $data_inven_item['inventory_id']);

                        if($stok != 0)
                        {
                            break;
                        }
                    }
                }

                $data_inv_history = array(
                    'transaksi_id'  => $inventory_item[0]['inventory_id'],
                    'transaksi_tipe' => 7
                );
                $inv_history_id = $this->inventory_history_m->save($data_inv_history);

                $data_sod = array(
                    'stok_opname_online_id' => $so_id,
                    'item_id'        => $item_id,
                    'item_satuan_id' => $item_satuan_id,
                    'jumlah_sistem'  => $data_jumlah_awal,
                    'jumlah_input'   => $data_jumlah,
                    'is_active'      => 1
                );

                $save_sod_id = $this->stok_opname_online_detail_m->save($data_sod);

                $data_inv_hist_det = array(
                    'inventory_history_id' => $inv_history_id,
                    'gudang_id' => 1,
                    'pmb_id'    => 0,
                    'item_id'   => $item_id,
                    'item_satuan_id'    => $item_satuan_id,
                    'initial_stock' => $data_jumlah_awal,                
                    'change_stock'    => $data_jumlah-$data_jumlah_awal,
                    'final_stock'   => (0+$data_jumlah),
                    'harga_beli'    => 0,
                    'total_harga' => 0 * (0+$data_jumlah)
                );
                $inv_history_det_id = $this->inventory_history_detail_m->save($data_inv_hist_det);
            }
        }
        else
        {
            $max_inv_id = $this->inventory_m->get_id()->result_array();

            if(count($max_inv_id) != 0)
            {
                $max_id = $max_inv_id[0]['id'] + 1;
            }
            else
            {
                $max_id = 1;
            }

            $data_save_inv = array(
                'inventory_id'  => $max_id,
                'gudang_id' => 1,
                'pmb_id'    => 0,
                'item_id'   => $item_id,
                'item_satuan_id'    => $item_satuan_id,
                'jumlah'    => $data_jumlah,
                'tanggal_datang'    => date('Y-m-d H:i:s'),
                'harga_beli'    => 0,
                'created_by' => $this->session->userdata('user_id'),
                'created_date'  => date('Y-m-d H:i:s')
            );

            $this->inventory_m->add_data($data_save_inv);

            $data_inv_history = array(
                'transaksi_id'  => $max_id,
                'transaksi_tipe' => 7
            );
            $inv_history_id = $this->inventory_history_m->save($data_inv_history);

            $data_inv_hist_det = array(
                'inventory_history_id' => $inv_history_id,
                'gudang_id' => 1,
                'pmb_id'    => 0,
                'item_id'   => $item_id,
                'item_satuan_id'    => $item_satuan_id,
                'initial_stock' => $data_jumlah_awal,                
                'change_stock'    => $data_jumlah-$data_jumlah_awal,
                'final_stock'   => (0+$data_jumlah),
                'harga_beli'    => 0,
                'total_harga' => 0 * (0+$data_jumlah)
            );
            $inv_history_det_id = $this->inventory_history_detail_m->save($data_inv_hist_det);
        }

        // die_dump($this->db->last_query());
        echo json_encode($update_jumlah);
    }

    public function save_so_set()
    {
        $kategori_id     = $this->input->post('kategori_id');
        $sub_kategori_id = $this->input->post('sub_kategori_id');
        $item_id         = $this->input->post('item_id');
        $gudang_id       = $this->input->post('gudang_id');
        $user            = $this->session->userdata('user_id');
        $data_id         = $this->stok_opname_online_set_m->get_id()->result_array();
        // die_dump($item_id);
        $response = new stdClass;

        $data = array(
            'user_id'   => $user,
            'status'    => 1,
            'is_active' => 1
        );

        $so_online = $this->stok_opname_online_m->save($data);


        if($kategori_id != null && $sub_kategori_id != null && $item_id != null)
        {
            // $data_so = $this->inventory_m->get_by(array('gudang_id'));
            $gudang_id = $gudang_id;
            if($data_id == null)
            {
                $data_so = 1;
            }
            else
            {
                $data_so = $data_id[0]['id']+1;
            }

            $data_set =array(
                'stok_opname_online_set_id' => $data_so,
                'stok_opname_online_id' => $so_online,
                'user_id'                   => $user,
                'gudang_id'                 => $gudang_id,
                'item_kategori_id'          => $kategori_id,
                'sub_kategori_id'           => $sub_kategori_id,
                'item_id'                   => $item_id,
                'is_set'                    => 1,
                'created_by'                => $user,
                'created_date'              => date('Y-m-d H:i:s')
            );

            $so_online_set = $this->stok_opname_online_set_m->add_data($data_set);

        }
        else if($kategori_id == null && $sub_kategori_id == null && $item_id == null)
        {
            $gudang_id = $gudang_id;
            $data = $this->item_m->get();
            $data_item = object_to_array($data);
            // die_dump($this->db->last_query());
            foreach ($data_item as $data_item) {
                $data_id = $this->stok_opname_online_set_m->get_id()->result_array();
                if($data_id == null)
                {
                    $data_so = 1;
                }
                else
                {
                    $data_so = $data_id[0]['id']+1;
                }
                $data_set =array(
                    'stok_opname_online_set_id' => $data_so,
                    'stok_opname_online_id' => $so_online,
                    'user_id'                   => $user,
                    'gudang_id'                 => $gudang_id,
                    'item_kategori_id'          => $kategori_id,
                    'sub_kategori_id'           => $sub_kategori_id,
                    'item_id'                   => $data_item['id'],
                    'is_set'                    => 1,
                    'created_by'                => $user,
                    'created_date'              => date('Y-m-d H:i:s')
                );

                $so_online_set = $this->stok_opname_online_set_m->add_data($data_set);
                // die(dump($this->db->last_query()));
            }
        }
        else if($kategori_id != null && $sub_kategori_id == null && $item_id == null)
        {
            $gudang_id = $gudang_id;
            $data_sub_kat = $this->item_sub_kategori_m->get_by(array('item_kategori_id' => $kategori_id));
            $data_sub_kat = object_to_array($data_sub_kat);

            if(count($data_sub_kat))
            {
                foreach ($data_sub_kat as $sub_kat) 
                {
                    $data = $this->item_m->get_by(array('item_sub_kategori' => $sub_kat['id']));
                    $data_item = object_to_array($data);
                    // die_dump($this->db->last_query());
                    foreach ($data_item as $data_item) 
                    {
                        $data_id = $this->stok_opname_online_set_m->get_id()->result_array();
                        if($data_id == null)
                        {
                            $data_so = 1;
                        }
                        else
                        {
                            $data_so = $data_id[0]['id']+1;
                        }
                        $data_set =array(
                            'stok_opname_online_set_id' => $data_so,
                            'stok_opname_online_id' => $so_online,
                            'user_id'                   => $user,
                            'gudang_id'                 => $gudang_id,
                            'item_kategori_id'          => $kategori_id,
                            'sub_kategori_id'           => $sub_kat['id'],
                            'item_id'                   => $data_item['id'],
                            'is_set'                    => 1,
                            'created_by'                => $user,
                            'created_date'              => date('Y-m-d H:i:s')
                        );

                        $so_online_set = $this->stok_opname_online_set_m->add_data($data_set);
                        // die(dump($this->db->last_query()));
                    }
                }
            }
        }
        else if($kategori_id != null && $sub_kategori_id != null && $item_id == null)
        {
            $gudang_id = $gudang_id;
            
            $data = $this->item_m->get_by(array('item_sub_kategori' => $sub_kategori_id));
            $data_item = object_to_array($data);
            // die_dump($this->db->last_query());
            foreach ($data_item as $data_item) 
            {
                $data_id = $this->stok_opname_online_set_m->get_id()->result_array();
                if($data_id == null)
                {
                    $data_so = 1;
                }
                else
                {
                    $data_so = $data_id[0]['id']+1;
                }
                $data_set =array(
                    'stok_opname_online_set_id' => $data_so,
                    'stok_opname_online_id' => $so_online,
                    'user_id'                   => $user,
                    'gudang_id'                 => $gudang_id,
                    'item_kategori_id'          => $kategori_id,
                    'sub_kategori_id'           => $sub_kategori_id,
                    'item_id'                   => $data_item['id'],
                    'is_set'                    => 1,
                    'created_by'                => $user,
                    'created_date'              => date('Y-m-d H:i:s')
                );

                $so_online_set = $this->stok_opname_online_set_m->add_data($data_set);
                // die(dump($this->db->last_query()));
            }
            
            
        }
        // $data_so = $this->inventory_m->get_by(array('gudang_id'));

        

        $response->so_set_id = $data_so;
        $response->so_online_id = $so_online;

        // die_dump($this->db->last_query());        
        //die_dump($this->db->last_query());
        echo json_encode($response);
    }

    public function delete_so_set()
    {
        $gudang_id = $this->input->post('gudang_id');
        $kategori_id = $this->input->post('kategori_id');
        $sub_kategori_id = $this->input->post('sub_kategori_id');
        $item_id = $this->input->post('item_id');
        $user = $this->session->userdata('user_id');
        $data_id = $this->stok_opname_online_set_m->get_by(array('user_id' => $user, 'gudang_id' => $gudang_id, 'item_kategori_id' => $kategori_id, 'sub_kategori_id' => $sub_kategori_id));
        $data = object_to_array($data_id);
        // die_dump($data);
        foreach ($data as $row) {
            $so_online_set = $this->stok_opname_online_set_m->delete_data($row['stok_opname_online_set_id']);
        }

        // die_dump();
       
        // die_dump($this->db->last_query());        
        //die_dump($this->db->last_query());
        echo json_encode($data_id);
    }

    public function update_so()
    {
        $so_id = $this->input->post('so_id');
        $keterangan = $this->input->post('keterangan');
        $user = $this->session->userdata('user_id');
        $status = 2;

        $data_so = array(
            'keterangan' => $this->input->post('keterangan'),
            '`status`' => 2,
        );

        $so_online = $this->stok_opname_online_m->save($data_so, $so_id);

        $delete_so_set = $this->stok_opname_online_set_m->delete_by(array('stok_opname_online_id' => $so_id));
        // die_dump($this->db->last_query());
        echo json_encode($so_online);

    }
}

/* End of file branch.php */
/* Location: ./application/controllers/branch/branch.php */