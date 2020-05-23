<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Daftar Hutang", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
			
					<?php
					
						$option = array(
							'1'		=> 'Hutang TTF',
							'2'		=> 'Hutang PO COD',
							'3'		=> 'Hutang Reimburse',
						);

						echo form_dropdown('pilihan_hutang', $option, '1', 'id="pilihan_hutang" class="form-control select2" multiple');
					?>
				
		</div>
	</div>
	<div class="portlet-body">

		
			
		
		<div class="portlet box blue-sharp" id="hutang_supplier_ttf">
			<div class="portlet-title" style="margin-bottom: 0px !important;">
				<div class="caption">
					<span class="caption-subject bold uppercase"><?=translate("Outstanding Pembayaran Tukar Faktur", $this->session->userdata("language"))?></span>
				</div>
				<div class="actions">
					<span class="caption-subject bold uppercase" id="text_total_hutang_ttf"></span>
				</div>
			</div>
			<div class="portlet-body">
				<table class="table table-striped table-hover" id="table_daftar_hutang_ttf">
					<thead>
					<tr>
						<th class="text-center"><?=translate("No. Ref", $this->session->userdata("language"))?> </th>
						<th class="text-center"><?=translate("Nama Supplier", $this->session->userdata("language"))?> </th>
						<th class="text-center" style="width: 10%;"><?=translate("Nominal", $this->session->userdata("language"))?> </th>
					</tr>
					</thead>
					<tbody>
					
					</tbody>
					<tfoot>
						<tr>
							<td class="text-right" colspan="2">Total</td>
							<td class="text-right"><b id="total_hutang"></b></td>
						</tr>
						
					</tfoot>
				</table>
			</div>
		</div>
		<div class="portlet box blue-sharp hidden" id="hutang_supplier_po">
			<div class="portlet-title" style="margin-bottom: 0px !important;">
				<div class="caption">
					<span class="caption-subject bold uppercase"><?=translate("Outstanding Pembayaran PO", $this->session->userdata("language"))?></span>
				</div>
				<div class="actions">
					<span class="caption-subject bold uppercase" id="text_total_hutang_po"></span>
				</div>
			</div>
			<div class="portlet-body">
				<table class="table table-striped table-hover" id="table_daftar_hutang_po">
					<thead>
					<tr>
						<th class="text-center"><?=translate("No. Ref", $this->session->userdata("language"))?> </th>
						<th class="text-center"><?=translate("Nama Supplier", $this->session->userdata("language"))?> </th>
						<th class="text-center" style="width: 10%;"><?=translate("Nominal", $this->session->userdata("language"))?> </th>
					</tr>
					</thead>
					<tbody>
					
					</tbody>
					<tfoot>
						<tr>
							<td class="text-right" colspan="2">Total</td>
							<td class="text-right"><b id="total_hutang_po"></b></td>
						</tr>
						
					</tfoot>
				</table>
			</div>
		</div>

		<div class="portlet box blue-sharp hidden" id="hutang_karyawan">
			<div class="portlet-title" style="margin-bottom: 0px !important;">
				<div class="caption">
					<span class="caption-subject bold uppercase"><?=translate("Hutang Ke Karyawan", $this->session->userdata("language"))?></span>
				</div>
				<div class="actions">
					<span class="caption-subject bold uppercase" id="text_total_hutang_rb"></span>
				</div>
			</div>
			<div class="portlet-body">
				<table class="table table-striped table-hover" id="table_daftar_hutang_karyawan">
					<thead>
					<tr>
						<th class="text-center"><?=translate("No. Ref", $this->session->userdata("language"))?> </th>
						<th class="text-center"><?=translate("Nama", $this->session->userdata("language"))?> </th>
						<th class="text-center" style="width: 10%;"><?=translate("Nominal", $this->session->userdata("language"))?> </th>
					</tr>
					</thead>
					<tbody>
					
					</tbody>
					<tfoot>
						<tr>
							<td class="text-right" colspan="2">Total</td>
							<td class="text-right"><b id="total_hutang_karyawan"></b></td>
						</tr>
						
					</tfoot>
				</table>
			</div>
		</div>
		
	</div>
</div>