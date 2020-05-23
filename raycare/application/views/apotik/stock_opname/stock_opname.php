<?php
    

    $td_filter = '<tr role="row" class="filter"> 
                    <td><div class="text-center"></div></td> 
                    <td><div class="text-center"></div></td> 
                    <td><div class="text-center"></div></td> 
                    <td><div class="text-center"></div></td> 
                    <td><div class="text-center"></div></td> 
                    <td><div class="text-center"></div></td> 
                    <td><div class="text-center"> 
                        <select name="order_status" id="status" class="form-control form-filter input-sm order_status"> 
                            <option value="2">'.translate("All", $this->session->userdata("language")).'</option> 
                            <option value="0">'.translate("OK", $this->session->userdata("language")).'</option> 
                            <option value="1">'.translate("NOTICE", $this->session->userdata("language")).'</option> 
                        </select> </div> 
                    </td> 
                    <td><div class="text-center"></div></td> 
                </tr>';
?>
<div class="portlet light">
    <div class="portlet-body form">
 
<!--  ===== -->
<div class="row">
    <div class="col-md-12">
    <div class="portlet light">
        <div class="portlet-title">
            <div class="caption">
                <span class="caption-subject font-blue-sharp bold uppercase"><?=translate('informasi', $this->session->userdata('language'))?></span>
            </div>
        </div>
        <div class="portlet-body form-horizontal">
            <div class="form-wizard">
                <div class="form-body">
                    <div class="form-group"></div>
                    <div class="form-group">
                        <label class="control-label col-md-4"><?=translate("Gudang", $this->session->userdata("language"))?> :</label>
                        
                        <div class="col-md-4">
                            <?php
                                if($wareid!=null){
                                    $wareid=$wareid;
                                }else{
                                    $wareid="";
                                }
                                $warehouse_option = array();
                                $result = $this->warehouse_m->get();
                                foreach($result as $row)
                                {
                                    $warehouse_option[$row->id] = $row->nama;
                                }
                                echo form_dropdown("select_warehouse", $warehouse_option, $wareid, "id=\"select_warehouse\" class=\"form-control\" required");
                            ?>
                         
                        </div>
                    </div>       
                    <div class="form-group hidden">
                        <label class="control-label col-md-4">ID</label>
                        <div class="col-md-1">
                            <input class="form-control" id="id_warehouse" name="id_warehouse">
                        </div>
                    </div>           
                </div>
            </div>
        </div>
    </div>

        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                   <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Data Stok Opname", $this->session->userdata("language"))?></span>
                </div>
                
                <div class="actions"> <!-- <a href="#" class="btn default btn-sm">
                                                <i class="fa fa-pencil icon-black"></i> Edit </a> -->
                    <a href="<?=base_url()?>apotik/stock_opname/add/" class="btn btn-primary add-so">
                        <i class="fa fa-plus"></i>
                        <span class="hidden-480">
                             <?=translate("New Stock Opname", $this->session->userdata("language"))?>
                        </span>
                    </a>
                </div>
            </div>

            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover" id="table_stock_opname">
                    <thead>
                        <tr role="row" class="heading">
                            <th scope="col" width="10%"><div class="text-center"><?=translate("Nomor Stok Opname", $this->session->userdata("language"))?></div></th>
                            <th scope="col" ><div class="text-center"><?=translate("Admin", $this->session->userdata("language"))?></div></th>
                            <th scope="col" ><div class="text-center"><?=translate("Orang", $this->session->userdata("language"))?></div></th>
                            <th scope="col" ><div class="text-center"><?=translate("Tanggal Mulai", $this->session->userdata("language"))?></div></th>
                            <th scope="col" ><div class="text-center"><?=translate("Tanggal Selesai", $this->session->userdata("language"))?></div></th>
                            <th scope="col" width="25%"><div class="text-center"><?=translate("Actions", $this->session->userdata("language"))?></div></th>
                        </tr>
                    </thead>
                    <tbody>
                       
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("History", $this->session->userdata("language"))?></span>
                </div>

            </div>

            <div class="portlet-body">
                <div id="thead-filter-template" class="hidden"><?=htmlentities($td_filter)?></div>
                <table class="table table-striped table-bordered table-hoverd" id="table_stock_opname_history">
                    <thead>
                        <tr role="row" class="heading">
                            <th scope="col" width="10%"><div class="text-center"><?=translate("Nomor Stok Opname", $this->session->userdata("language"))?></div></th>
                            <th scope="col" ><div class="text-center"><?=translate("Admin", $this->session->userdata("language"))?></div></th>
                            <th scope="col" ><div class="text-center"><?=translate("Orang", $this->session->userdata("language"))?></div></th>
 
                            <th scope="col" ><div class="text-center"><?=translate("Tanggal Mulai", $this->session->userdata("language"))?></div></th>
                            <th scope="col" ><div class="text-center"><?=translate("Tanggal Selesai", $this->session->userdata("language"))?></div></th>
                            <th scope="col" ><div class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?></div></th>
                            <th scope="col" ><div class="text-center"><?=translate("Actions", $this->session->userdata("language"))?></div></th>
                        </tr>
                    </thead>
                    <tbody>
                                        
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
    </div>
    </div>
 
