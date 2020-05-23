<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Akses User", $this->session->userdata("language"))?></span>
		</div>
	</div>
	<div class="portlet-body form">
		<div class="">
			<div class="form-group">
				<div class="col-md-12">

					<?php
					$where = array(
					    // 'url IS NOT NULL' => NULL,		abu
					    // 'url !='          => '',			abu
					    'is_active'       => 1
					);

					$menus = $this->menu_m->get_by($where);
					// die(dump($this->db->last_query()));
					$menu_option = array();
					$user_level_id ='';
					foreach ($menus as $menu)
					{
					    $menu_option[$menu->id] = $menu->nama;
					}

					if ($flag == "add") {
						echo form_dropdown("multi_select[]", $menu_option, '', "id=\"multi_select\" class=\"multi-select\" multiple=\"multiple\"");
					}
					else
					{

						$user_level_menu_option = array();
						$user_level_menu = $this->user_level_menu_m->get_by(array('user_level_id' => $pk_value, 'is_active' => 1));
						
						foreach ($user_level_menu as $menu_select)
						{
					    	$user_level_menu_option[$menu_select->menu_id] = $menu_select->menu_id;
					    	$user_level_menu_id = $menu_select->id;
					    	echo '<input type="hidden" id="user_level_menu_id" name="user_level_menu_id[]" value="'.$user_level_menu_id.'">';
						}

						// die_dump($user_level_menu_option);
						
						echo form_dropdown('multi_select[]', $menu_option, $user_level_menu_option, "id=\"multi_select\" class=\"multi-select\" multiple=\"multiple\"");
							
					}

					
					?>
				</div>
				
			</div>
		</div>
	</div>
</div>


