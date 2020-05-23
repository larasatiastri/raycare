<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bed extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = 'd929fcb902d25ac7f23e5de5f85d9112';                  // untuk check bit_access

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

        $this->load->model('klinik_hd/bed_m');
       
    }
    
    public function index()
    {

        $assets = array();
        $config = 'assets/master/bed/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Master Bed', $this->session->userdata('language')), 
            'header'         => translate('Master Bed', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/bed/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    
    /**
     * [list description]
     * @return [type] [description]
     */
    public function listing()
    {        
        $result = $this->bed_m->get_datatable();

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        // die_dump($records);
        $i=1;
       
        $user_level_id = $this->session->userdata('level_id');
        foreach($records->result_array() as $row)
        {   
            $action = '<div id="btn_edit_'.$i.'"><a title="'.translate('Edit', $this->session->userdata('language')).'" data-index="'.$i.'" name="edit[]" class="btn blue-chambray"><i class="fa fa-edit"></i></a></div><div id="btn_cancel_save_'.$i.'" class="hidden"><a title="'.translate('Batal', $this->session->userdata('language')).'" data-index="'.$i.'" name="batal[]" class="btn btn-danger"><i class="fa fa-undo"></i></a><a title="'.translate('Simpan', $this->session->userdata('language')).'" data-index="'.$i.'" name="simpan[]" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-disk"></i></a></div>';
            $status = '';

            if($row['status'] == 1){
                $status = '<span class="label label-md label-success">Kosong</span>';
            }if($row['status'] == 2){
                $status = '<span class="label label-md label-warning">Booking</span>';
            }if($row['status'] == 3){
                $status = '<span class="label label-md label-danger">Terisi</span>';
            }if($row['status'] == 4){
                $status = '<span class="label label-md bg-grey">Rusak</span>';
            }if($row['status'] == 5){
                $status = '<span class="label label-md bg-blue-chambray">Diakses</span>';
            } 

            $status_option = array(

                '1' => 'Kosong',
                '2' => 'Booking',
                '3' => 'Terisi',
                '4' => 'Rusak',
                '5' => 'Diakses',
            );

            $select_status = form_dropdown('input['.$i.'][status]', $status_option, $row['status'], 'id="input_status_'.$i.'" class="form-control"').'';

            $status_antrian_option = array(

                '0' => '0',
                '1' => '1'            
            );

            $select_status_antrian = form_dropdown('input['.$i.'][status_antrian]', $status_antrian_option, $row['status_antrian'], 'id="input_status_antrian_'.$i.'" class="form-control"').'';

            $user_option = array(
                '' => 'Kosong'
            );

            $user = $this->user_m->get_by(array('is_active' => 1));

            foreach ($user as $key => $usr) {
               $user_option[$usr->id] = $usr->nama;
            }

            $select_user = form_dropdown('input['.$i.'][user_edit_id]', $user_option, $row['user_edit_id'], 'id="input_user_edit_id_'.$i.'" class="form-control"').'';
            
            $output['data'][] = array(
                // $row['id'],
                '<div class="text-center">'.$row['kode'].'<input type="hidden" class="form-control text-right" id="input_bed_id_'.$i.'" name="input['.$i.'][bed_id]"  value="'.$row['id'].'" ></div>',
                '<div class="text-left">'.$row['nama'].'</div>' ,
                '<div class="text-left">'.$row['lantai_id'].'<input type="hidden" class="form-control text-right" id="input_lantai_id_'.$i.'" name="input['.$i.'][lantai_id]"  value="'.$row['lantai_id'].'" ></div>',
                '<div class="text-left">'.$row['mesin_id'].'<input type="hidden" class="form-control text-right" id="input_mesin_id_'.$i.'" name="input['.$i.'][mesin_id]"  value="'.$row['mesin_id'].'" ></div>',
                '<div id="div_status_'.$i.'">'.$status.'</div><div id="div_status_edit_'.$i.'" class="hidden">'.$select_status.'</div>',
                '<div id="div_status_antrian_'.$i.'" class="text-left">'.$row['status_antrian'].'</div><div id="div_status_antrian_edit_'.$i.'" class="hidden">'.$select_status_antrian.'</div>',
                '<div class="text-left" id="div_shift_'.$i.'">'.$row['shift'].'</div><div id="div_shift_edit_'.$i.'" class="text-left hidden"><input class="form-control text-right" id="input_shift_'.$i.'" name="input['.$i.'][shift]"  value="'.$row['shift'].'" ></div>',
                
                '<div class="text-left inline-button-table" id="div_user_'.$i.'"><label id="label_user_akses_'.$i.'" class="label_user_akses">'.$row['user'].' </label></div><div id="div_user_edit_'.$i.'" class="hidden">'.$select_user.'</div>',
                '<div class="text-left inline-button-table">'.$action.'</div>'
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function save()
    {
        $array_input = $this->input->post();
        
        $response = new stdClass;
        $response->success = false;
        $response->msg = translate('Bed gagal diedit', $this->session->userdata('language'));

        $data = array(
            'status' => $array_input['status'],
            'status_antrian' => $array_input['status_antrian'],
            'shift' => $array_input['shift'],
            'user_edit_id' => $array_input['user_edit_id'],
        );
        $edit_bed = $this->bed_m->save($data, $array_input['bed_id']);
        if($edit_bed){
            $response->success = true;
            $response->msg = translate('Bed berhasil diedit', $this->session->userdata('language'));
        }

        die(json_encode($response));

    }

   
}

/* End of file pegawai.php */
/* Location: ./application/controllers/master/pegawai.php */
