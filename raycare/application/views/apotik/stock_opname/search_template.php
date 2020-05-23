<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption">
                    <i class=""></i><?=translate("Data Template", $this->session->userdata("language"))?>
                </div>
            </div>

            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover" id="table_template">
                    <thead>
                        <tr role="row" class="heading">
                
                            <th scope="col" ><div class="text-center"><?=translate("Template", $this->session->userdata("language"))?></div></th>
                            <th scope="col" ><div class="text-center"><?=translate("Aksi", $this->session->userdata("language"))?></div></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr role="row">
                            <td scope="col" ><div class="text-center">BGM - Amir</div></td>
                            <td scope="col" ><div class="text-center"><a title="View" id="view" name="view[]" data-id="1" class="btn btn-sm green"><i class="fa fa-search"></i></a><a title="Select" id="select" name="select[]" data-id="1" class="btn btn-sm blue"><i class="fa fa-check"></i></a>
                                </div>
                            </td>  
                        </tr>
                        <tr role="row">
                            <td scope="col" ><div class="text-center">BGM - Budi</div></td>
                            <td scope="col" ><div class="text-center"><a title="View" id="view" name="view[]" data-id="2" class="btn btn-sm green"><i class="fa fa-search"></i></a><a title="Select" id="select" name="select[]" data-id="2" class="btn btn-sm blue"><i class="fa fa-check"></i></a>
                                </div>
                            </td>  
                        </tr>
                        <tr role="row">
                            <td scope="col" ><div class="text-center">BGM - Joko</div></td>
                            <td scope="col" ><div class="text-center"><a title="View" id="view" name="view[]" data-id="3" class="btn btn-sm green"><i class="fa fa-search"></i></a><a title="Select" id="select" name="select[]" data-id="3" class="btn btn-sm blue"><i class="fa fa-check"></i></a>
                                </div>
                            </td>  
                        </tr>
                        <tr role="row">
                            <td scope="col" ><div class="text-center">BGM - Tono</div></td>
                            <td scope="col" ><div class="text-center"><a title="View" id="view" name="view[]" data-id="4" class="btn btn-sm green"><i class="fa fa-search"></i></a><a title="Select" id="select" name="select[]" data-id="4" class="btn btn-sm blue"><i class="fa fa-check"></i></a>
                                </div>
                            </td>  
                        </tr>    
                            
                            
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="portlet" >
            <div class="portlet-title">
                <div class="caption">
                    <i class=""></i><?=translate("Data Detail Template", $this->session->userdata("language"))?>
                </div>
            </div>

            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover" id="table_detail">
                    <thead>
                        <tr role="row" class="heading">
                
                            <th scope="col" ><div class="text-center"><?=translate("Kode", $this->session->userdata("language"))?></div></th>
                            <th scope="col" ><div class="text-center"><?=translate("Nama", $this->session->userdata("language"))?></div></th>
                            <th scope="col" ><div class="text-center"><?=translate("Gudang", $this->session->userdata("language"))?></div></th>
                        </tr>
                    </thead>
                   
                    <tbody id="data-detail" style="display:none">
                        <tr role="row">
                            <td scope="col" ><div class="text-center">BGSL1</div></td>
                            <td scope="col" ><div class="text-center">Gear</div></td>
                            <td scope="col" ><div class="text-center">Gudang 1</div>
                            </td>  
                        </tr>
                        <tr role="row">
                            <td scope="col" ><div class="text-center">BGSL2</div></td>
                            <td scope="col" ><div class="text-center">Box</div></td>
                            <td scope="col" ><div class="text-center">Gudang 2</div>
                            </td>  
                        </tr>
                        <tr role="row">
                            <td scope="col" ><div class="text-center">BGSL3</div></td>
                            <td scope="col" ><div class="text-center">Shock Breaker</div></td>
                            <td scope="col" ><div class="text-center">Gudang 1</div>
                            </td>  
                        </tr>
                        <tr role="row">
                            <td scope="col" ><div class="text-center">BGSL4</div></td>
                            <td scope="col" ><div class="text-center">Clutch</div></td>
                            <td scope="col" ><div class="text-center">Gudang 2</div>
                            </td>  
                        </tr>    
                            
                            
                    </tbody>
                
                </table>
            </div>

        </div>
    </div>
</div>
