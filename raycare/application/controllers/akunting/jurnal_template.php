<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jurnal_template extends MY_Controller {

	protected $menu_id = 14;                  // untuk check bit_access

	private $menus = array();
    
	private $menu_tree = array(4, 14);       // untuk keperluan item menu dengan class 'open', 'selected'

	public function __construct()
	{       
		parent::__construct();
		if(!$this->input->is_ajax_request())
		{
			$user_level = $this->user_m->level_id();
            $this->menus = $this->user_level_menu_m->get_nested($user_level);
		}

        $this->load->model('akunting/jurnal_sistem_detail_template_m');
        $this->load->model('akunting/jurnal_sistem_template_m');
        $this->load->model('akunting/jurnal_template_trans_tipe_m');
        $this->load->model('akunting/jurnal_template_trans_tipe_detail_m');
        $this->load->model('akunting/user_m');
        $this->load->model('finance/akun_m');
        
	}

	public function index()
	{
		$assets = array();
        $config = 'assets/akunting/jurnal_template/jurnal_template';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css']));

        $data = array(
            'title'          => config_item('site_name').' | '.translate('Master Jurnal Template', $this->session->userdata('language')), 
            'header'         => translate('Master Jurnal Template', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'akunting/jurnal_template/jurnal_template',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
	}

    public function listing()
    {
        $result = $this->jurnal_sistem_template_m->get_datatable();
        // die_dump($result);
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
        $records = $result->records;
        // die_dump($records);
        foreach($records->result_array() as $row)
        {
            if($row['is_active']==1)

            {
                        // <a title="Delete" href="jurnal_template/delete/'. $row['id'] .'" name="delete[]" id="delete" data-action="delete" data-id="'.$row['id'].'"  class="btn btn-xs red-intense"><i class="fa fa-times"></i></a>
                        
                $action = '<div class="text-center">
                        <a title="View" href="jurnal_template/view/'. $row['id'] .'" class="btn btn-xs grey-cascade"><i class="fa fa-search"></i></a>
                        <a title="Edit" href="jurnal_template/edit/'. $row['id'] .'" class="btn btn-xs blue-chambray"><i class="fa fa-edit"></i></a>
                               
                        </div>';

            } else

            { 
                $action = '<a title="'.translate('Restore', $this->session->userdata('language')).'" href="jurnal_template/restore/'. $row['id'] .'" data-confirm="'.translate('Are you sure you want to restore this Journal Template?', $this->session->userdata('language')).'" name="restore[]" data-action="restore" data-id="'.$row['id'].'" class="btn btn-xs yellow"><i class="fa fa-undo"></i> </a>';
            }


                $type = $this->jurnal_template_trans_tipe_m->get_by(array('id' => $row['tipe_transaksi']));
                $type = object_to_array($type);
                // die_dump($this->db->last_query()); 

                $nama='';
                
                foreach ($type as $type) 
                {
                   $nama = $type['nama'];
                }

                $createby = $this->user_m->get_by(array('id' => $row['created_by']));
                $createby = object_to_array($createby);
                $user = '';
                  
                  foreach ($createby as $createby) 
                  
                  {
                  
                   $user = $createby['nama'];
                  
                  }
                        $output['aaData'][] = array(
                                    $row['nama_template'],
                                    $nama,
                                    $row['keterangan'],
                                    $user,
                                    '<div class="text-center">'.$action.'</div>'
                                   );  
        }

      echo json_encode($output);
    
    }

    
    public function get_account_type()
    {
        $type_id = $this->input->post('tipeId');
        $this->jurnal_template_trans_tipe_m->set_columns(array('subjek','nama_akun'));
        $account = $this->jurnal_template_trans_tipe_m->get_by(array('id' => $type_id));
        $account_array = object_to_array($account);
        echo json_encode($account_array);
    }

	public function add()
	{
		$assets = array();
        $config = 'assets/akunting/jurnal_template/add';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));

        $data = array(
            'title'          => config_item('site_name').'| '.translate('Journal System Template', $this->session->userdata('language')), 
            'header'         => translate('Add Journal System Template', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'akunting/jurnal_template/add',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
	}

	public function edit($id)
	{
		$assets = array();
        $config = 'assets/akunting/jurnal_template/edit';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $this->load->library('form_builder');

        $form_data = $this->jurnal_sistem_template_m->get($id);
        $journal_details_data = $this->jurnal_sistem_detail_template_m->get_by(array('jurnal_sistem_template_id' => $id ));
        // die(dump( $assets['css'] ));
        // die_dump($journal_details_data);

        $data = array(
            'title'          => config_item('site_name').'| '.translate('Journal System Template', $this->session->userdata('language')), 
            'header'         => translate('Edit Journal System Template', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'form_data'      => object_to_array($form_data),
            'journal_details_data'=>  object_to_array($journal_details_data),

            'content_view'   => 'akunting/jurnal_template/edit',
            'pk_value'       => $id,
            );
        
        // Load the view
        $this->load->view('_layout', $data);


	}

	public function view($id)
	{
		$assets = array();
        $config = 'assets/akunting/jurnal_template/view';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
         
        $journals_data = $this->jurnal_sistem_template_m->get($id);
        $tipe = object_to_array($journals_data);
        $bebas = $this->jurnal_template_trans_tipe_m->get($tipe['tipe_transaksi']);
        // $bebas = object_to_array($journals_data -> ['tipe_transaksi']);
        $journal_details_data = $this->jurnal_sistem_detail_template_m->get_by(array('jurnal_sistem_template_id' => $id ));
        // $journals_tipe = $this->jurnal_template_trans_tipe_m->get_by(array('nama' => $journals_data('tipe_transaksi')));
        
        // $journal_tipe = $this->jurnal_template_trans_tipe_m->get_by(array('nama' => $journals_data));
        
        // die_dump($journals_data);

        // die(dump( $assets['css'] ));

        $data = array(
            'title'          => config_item('site_name').'| '.translate('Journal System Template', $this->session->userdata('language')), 
            'header'         => translate('View Journal System Template', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'journal_data'       => object_to_array($journals_data),
            'journal_details_data'=>  object_to_array($journal_details_data),
            'content_view'   => 'akunting/jurnal_template/view',
            'pk_value'      => $id
            );
        
        // Load the view
        $this->load->view('_layout', $data);

	}

    public function save()
    {
        $command = $this->input->post('command');
        $accounts = $this->input->post('account');
        // die_dump($this->input->post());

        if ($command === 'add')
        {
            $data = $this->jurnal_sistem_template_m->array_from_post($this->jurnal_sistem_template_m->fillable());
            // die_dump($data);
            $data['is_active'] = 1;
            $journal_id = $this->jurnal_sistem_template_m->save($data);
          
            $accounts_save = array();
             // die_dump($accounts);
            foreach($accounts as $accounts_array)
            {

                if($accounts_array['debitkredit'] != '')
                {
	                $accounts_save['akun_id'] = $accounts_array['account_id'];
	                $accounts_save['jurnal_sistem_template_id'] = $journal_id;                                   
	                $accounts_save['debit_credit'] = $accounts_array['debitkredit'];
	                $accounts_save['akun_tipe'] = $accounts_array['account_type'];

                	$jurnal_template_detail = $this->jurnal_sistem_detail_template_m->save($accounts_save);
            	}
            }
             // die_dump($this->db->last_query());
             if ($journal_id) 
                {
                    $flashdata = array(
                        "type"     => "success",
                        "msg"      => translate("Account Added", $this->session->userdata("language")),
                        "msgTitle" => translate("Success", $this->session->userdata("language"))    
                        );
                    $this->session->set_flashdata($flashdata);
                }
                redirect("akunting/jurnal_template");
        }
        
        elseif ($command === 'edit')
        {
             $data = $this->jurnal_sistem_template_m->array_from_post($this->jurnal_sistem_template_m->fillable());
             // die_dump($data);
             
             $data['is_active'] = 1;
             $id = $data['id'];
             $journal_id = $this->jurnal_sistem_template_m->save($data,$id);
                  
             $accounts = $this->input->post('account');

             $accounts_save = array();

             foreach($accounts as $accounts_array)
              {
                $accounts_save['id'] = $accounts_array['id_detail'];
                $accounts_save['akun_id'] = $accounts_array['account_id'];
                $accounts_save['jurnal_sistem_template_id'] = $journal_id;
                $accounts_save['debit_credit'] = $accounts_array['debitkredit'];

                if($accounts_array['id_detail'] == NULL && $accounts_array['account_id']!=''){
                    $this->jurnal_sistem_detail_template_m->save($accounts_save);
                }

                if($accounts_array['is_deleted'] == 1){
                    $this->jurnal_sistem_detail_template_m->delete($accounts_save['id']);
                }
                else
                {
                   $jurnal_template_detail = $this->jurnal_sistem_detail_template_m->save($accounts_save,$accounts_save['id']);
                }
        	 }

             if ($jurnal_template_detail) 
                {
                    $flashdata = array(
                        "type"     => "success",
                        "msg"      => translate("Account Edited", $this->session->userdata("language")),
                        "msgTitle" => translate("Success", $this->session->userdata("language"))    
                        );
                    $this->session->set_flashdata($flashdata);
                }

            redirect("akunting/jurnal_template");
        }
    }


    public function listing_account()
    {
        //  $this->load->model('finance/account_categories_m');
        $result = $this->akun_m->get_datatable();
        // die(dump($this->db->last_query()));
  
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
        $records = $result->records;
        
        foreach($records->result_array() as $row)
        {
            $action = '<a title="'.translate('Select', $this->session->userdata('language')).'"  data-item="'.htmlentities(json_encode($row)).'" class="btn btn-xs green-haze select"><i class="fa fa-check"></i></a>';
             $output['aaData'][] = array(
                $row['no_akun'],
                $row['nama'],
                '<div class="text-center">'.$action.'</div>'
               );             
        }

       // die(dump($this->db->last_query()));


      echo json_encode($output);

    }

      public function delete($id)
    {
           
        $data = array(
            'is_active'    => 0
        );

        // save data
        $user_id = $this->jurnal_sistem_template_m->save($data, $id);
        if ($user_id) 
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Security Access Deleted", $this->session->userdata("language")),
                "msgTitle" => translate("Success", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
                redirect("akunting/jurnal_template");
      
    }

    public function restore($id)
    {
           
        $data = array(
            'is_active'    => 1
        );

        // save data
        $user_id = $this->jurnal_sistem_template_m->save($data, $id);
        if ($user_id) 
        {
            $flashdata = array(
                "type"     => "warning",
                "msg"      => translate("Security Access Restored", $this->session->userdata("language")),
                "msgTitle" => translate("Success", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
                redirect("akunting/jurnal_template");
        
    } 

}