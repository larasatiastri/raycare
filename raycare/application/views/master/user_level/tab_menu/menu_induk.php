<div class="portlet light" id="section-parent">
	<div class="portlet-title">
		<div class="caption">
			<?=translate('Menu Utama', $this->session->userdata('language'))?>
		</div>
		<?php $msg = translate("Apakah anda yakin akan membuat menu utama ini?",$this->session->userdata("language"));?>
		<?php $msg_processing = translate("Sedang Diproses",$this->session->userdata("language"));?>
		<div class="actions">
			<a class="btn btn-circle btn-icon-only btn-default add-parent">
                <i class="fa fa-plus"></i>
            </a>										
			<a id="confirm_save" class="btn btn-circle btn-primary" href="#" data-confirm="<?=$msg?>" data-proses="<?=$msg_processing?>" data-toggle="modal">
				<i class="fa fa-check"></i>
				<?=translate("Simpan", $this->session->userdata("language"))?></a>
		</div>
	</div>
	<div class="portlet-body form">
		<div class="form-body">
			<?php
				
				$form_menu_parent = '
				<div class="form-group">
					<label class="control-label col-md-4">'.translate("Nama Menu", $this->session->userdata("language")).' :<span class="required">*</span></label>
					<div class="col-md-8">
						<div class="input-group">
							<input class="form-control" required id="menu_nama_{0}" name="menu_parent[{0}][nama]" placeholder="Nama Menu">
							<span class="input-group-btn">
								<a class="btn red-intense del-this" id="btn_delete_menu_parent_{0}" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4">'.translate("Cabang ID", $this->session->userdata("language")).' :</label>
					<div class="col-md-8">
						<input class="form-control"  id="menu_base_url_{0}" name="menu_parent[{0}][cabang_id]" placeholder="Cabang ID">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4">'.translate("URL", $this->session->userdata("language")).' :</label>
					<div class="col-md-8">
						<input class="form-control"  id="menu_url_{0}" name="menu_parent[{0}][url]" placeholder="URL">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4">'.translate("Icon Class", $this->session->userdata("language")).' :</label>
					<div class="col-md-8">
						<input class="form-control"  id="menu_icon_class_{0}" name="menu_parent[{0}][icon_class]" placeholder="Icon Class">
						<input class="form-control hidden"  id="menu_id_{0}" name="menu_parent[{0}][id]" placeholder="Icon Class">
					</div>
				</div>
				';
			?>

			<input type="hidden" id="tpl-form-parent" value="<?=htmlentities($form_menu_parent)?>">
			<div class="form-body">
				<ul class="list-unstyled">
				</ul>
			</div>
		</div>

	</div>
</div>