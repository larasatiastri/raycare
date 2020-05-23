<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption">
                    <i class=""></i><?=translate("Data Item", $this->session->userdata("language"))?>
                </div>
            </div>

            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover" id="table_item">
                    <thead>
                        <tr role="row" class="heading">
                
                            <th scope="col" ><div class="text-center"><?=translate("Items Code", $this->session->userdata("language"))?></div></th>
                            <th scope="col" ><div class="text-center"><?=translate("Items Name", $this->session->userdata("language"))?></div></th>
                            <th scope="col" ><div class="text-center"><?=translate("Warehouse", $this->session->userdata("language"))?></div></th>
                            <th scope="col" ><div class="text-center"><?=translate("Actions", $this->session->userdata("language"))?></div></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for($i=1;$i<=13;$i++)
                            {
                                echo'<tr role="row">
                                    <td scope="col" ><div class="text-center">BGSL0'.$i.'</div></td>
                                    <td scope="col" >Selongsong Gas HND Astrea 0'.$i.'</td>
                                    <td scope="col" ><div class="text-center">Gudang BGM 1</div></td>
                                    <td scope="col" ><div class="text-center"><a title="Select" id="select" name="select[]" data-id="'.$i.'" class="btn btn-sm blue"><i class="fa fa-check"></i></a></div></td>  
                                </tr>  ';       
                            }
                            ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
