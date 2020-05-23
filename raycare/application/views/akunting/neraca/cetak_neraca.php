<div style="width: 45%">
    <table class="table table-bordered" id="tabel_current_asset">
        <thead>
            <tr>
                <th colspan="3" class="text-left"> Current Asset</th>
            </tr>
            
        </thead>
                
        <tbody>
            <?php

                $i = 0;
                $total_current_asset = 0;
                foreach ($akun_current_asset as $current) :
                    if($current['parent'] == '0'){
                        $akun_child = $this->akun_m->get_by(array('parent' => $current['akun_id']));
                        $readonly = (count($akun_child) == 0)?'':'readonly="readonly"';
                        $id = (count($akun_child) == 0)?'id="akun_parent"':'id="akun_'.$current['akun_id'].'"';

            ?>
                <tr style="background-color: #357ebd;">
                    <th><?=$current['no_akun']?></th>
                    <th width="60%"><input type="hidden" name="akun_current[<?=$i?>][detail_id]" value="<?=$current['id']?>"><input type="hidden" name="akun_current[<?=$i?>][id]" value="<?=$current['akun_id']?>"><input type="hidden" name="akun_current[<?=$i?>][parent]" value="<?=$current['parent']?>"><?=$current['nama']?></th>
                    <th width="25%" class="text-right"><?=formatrupiah($current['nominal'])?></th>
                </tr>
            <?php 
                $total_current_asset = $total_current_asset + $current['nominal'];   
                }elseif($current['parent'] != '0'){
            ?>
                <tr>
                    <td><?=$current['no_akun']?></td>
                    <td width="60%"><input type="hidden" name="akun_current[<?=$i?>][detail_id]" value="<?=$current['id']?>"><input type="hidden" name="akun_current[<?=$i?>][id]" value="<?=$current['akun_id']?>"><input type="hidden" name="akun_current[<?=$i?>][parent]" value="<?=$current['parent']?>"><?=$current['nama']?></td>
                    <td width="25%" class="text-right"><?=formatrupiah($current['nominal'])?></td>
                </tr>
            <?php       
                }
               
                $i++;
                endforeach;
            ?>
            
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2" class="text-center" > Total Current Asset</th>
                <th id="total_current_asset" class="text-right"> <?=formatrupiah($total_current_asset)?> </th><input type="hidden" name="total_current_asset" id="total_current_asset" value="<?=$total_current_asset?>">
            </tr>
        </tfoot>
        
    </table>
    <br>
    <table class="table table-bordered" id="tabel_fixed_asset">
        <thead>
            <tr>
                <th colspan="3" class="text-left"> Fixed Asset</th>
            </tr>
            
        </thead>
                
        <tbody>
            <?php

                $i = 0;
                $total_fixed_asset = 0;
                foreach ($akun_fixed_asset as $fixed) :
                    if($fixed['parent'] == '0'){
                        $akun_child = $this->akun_m->get_by(array('parent' => $fixed['akun_id']));
                        $readonly = (count($akun_child) == 0)?'':'readonly="readonly"';
                        $id = (count($akun_child) == 0)?'id="akun_parent"':'id="akun_'.$fixed['akun_id'].'"';
            ?>
                <tr style="background-color: #357ebd;">
                    <th><?=$fixed['no_akun']?></th>
                    <th width="60%"><input type="hidden" name="akun_fixed[<?=$i?>][detail_id]" value="<?=$fixed['id']?>"><input type="hidden" name="akun_fixed[<?=$i?>][id]" value="<?=$fixed['akun_id']?>"><input type="hidden" name="akun_fixed[<?=$i?>][parent]" value="<?=$fixed['parent']?>"><?=$fixed['nama']?></th>
                    <th width="25%" class="text-right"><?=formatrupiah($fixed['nominal'])?></th>
                </tr>
            <?php
                $total_fixed_asset = $total_fixed_asset + $fixed['nominal'];    
                }elseif($fixed['parent'] != '0'){
            ?>
                <tr>
                    <td><?=$fixed['no_akun']?></td>
                    <td width="60%"><input type="hidden" name="akun_fixed[<?=$i?>][detail_id]" value="<?=$fixed['id']?>"><input type="hidden" name="akun_fixed[<?=$i?>][id]" value="<?=$fixed['akun_id']?>"><input type="hidden" name="akun_fixed[<?=$i?>][parent]" value="<?=$fixed['parent']?>"><?=$fixed['nama']?></td>
                    <td width="25%" class="text-right"><?=formatrupiah($fixed['nominal'])?></td>
                </tr>
            <?php       
                }
                
                $i++;
                endforeach;
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2" class="text-center"> Total Fixed Asset</th>
                <th class="text-right" id="total_fixed_asset"> <?=formatrupiah($total_fixed_asset)?> </th><input type="hidden" name="total_fixed_asset" id="total_fixed_asset" value="<?=$total_fixed_asset?>"> 
            </tr>
        </tfoot>
        
    </table>
    
</div>
<div style="width: 45%">
    <table class="table table-bordered" id="tabel_liability">
        <thead>
            <tr>
                <th colspan="3" class="text-left"> Liability</th>
            </tr>
            
        </thead>
                
        <tbody>
            <?php

                $i = 0;
                $total_liability = 0;
                foreach ($akun_liability as $liability) :
                    if($liability['parent'] == '0'){
                        $akun_child = $this->akun_m->get_by(array('parent' => $liability['akun_id']));
                        $readonly = (count($akun_child) == 0)?'':'readonly="readonly"';
                        $id = (count($akun_child) == 0)?'id="akun_parent"':'id="akun_'.$liability['akun_id'].'"';
            ?>
                <tr style="background-color: #357ebd;">
                    <th><?=$liability['no_akun']?></th>
                    <th width="60%"><input type="hidden" name="akun_liability[<?=$i?>][detail_id]" value="<?=$liability['id']?>"><input type="hidden" name="akun_liability[<?=$i?>][id]" value="<?=$liability['akun_id']?>"><input type="hidden" name="akun_liability[<?=$i?>][parent]" value="<?=$liability['parent']?>"><?=$liability['nama']?></th>
                    <th width="25%"><?=formatrupiah($liability['nominal'])?></th>
                </tr>
            <?php  
                $total_liability = $total_liability + $liability['nominal'];  
                }elseif($liability['parent'] != '0'){
            ?>
                <tr>
                    <td><?=$liability['no_akun']?></td>
                    <td width="60%"><input type="hidden" name="akun_liability[<?=$i?>][detail_id]" value="<?=$liability['id']?>"><input type="hidden" name="akun_liability[<?=$i?>][id]" value="<?=$liability['akun_id']?>"><input type="hidden" name="akun_liability[<?=$i?>][parent]" value="<?=$liability['parent']?>"><?=$liability['nama']?></td>
                    <td width="25%"><?=formatrupiah($liability['nominal'])?></td>
                </tr>
            <?php       
                }
                $i++;
                endforeach;
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2" class="text-center"> Total Liability</th>
                <th class="text-right" id="total_liability"> <?=formatrupiah($total_liability)?> </th><input type="hidden" name="total_liability" id="total_liability" value="<?=$total_liability?>"> 
            </tr>
        </tfoot>
        
    </table>
    <br>
    <table class="table table-bordered" id="tabel_equity">
        <thead>
            <tr>
                <th colspan="3" class="text-left"> Equity</th>
            </tr>
            
        </thead>
                
        <tbody>
             <?php

                $i = 0;
                $total_equity = 0;
                foreach ($akun_equity as $equity) :
                    if($equity['parent'] == '0'){
                        $akun_child = $this->akun_m->get_by(array('parent' => $equity['akun_id']));
                        $readonly = (count($akun_child) == 0)?'':'readonly="readonly"';
                        $id = (count($akun_child) == 0)?'id="akun_parent"':'id="akun_'.$equity['akun_id'].'"';
            ?>
                <tr style="background-color: #357ebd;">
                    <th><?=$equity['no_akun']?></th>
                    <th width="60%"><input type="hidden" name="akun_equity[<?=$i?>][detail_id]" value="<?=$equity['id']?>"><input type="hidden" name="akun_equity[<?=$i?>][id]" value="<?=$equity['akun_id']?>"><input type="hidden" name="akun_equity[<?=$i?>][parent]" value="<?=$equity['parent']?>"><?=$equity['nama']?></th>
                    <th width="25%"><?=formatrupiah($equity['nominal'])?></th>
                </tr>
            <?php   
                $total_equity = $total_equity + $equity['nominal'];
                }elseif($equity['parent'] != '0'){
            ?>
                <tr>
                    <td><?=$equity['no_akun']?></td>
                    <td width="60%"><input type="hidden" name="akun_equity[<?=$i?>][detail_id]" value="<?=$equity['id']?>"><input type="hidden" name="akun_equity[<?=$i?>][id]" value="<?=$equity['akun_id']?>"><input type="hidden" name="akun_equity[<?=$i?>][parent]" value="<?=$equity['parent']?>"><?=$equity['nama']?></td>
                    <td width="25%"><?=formatrupiah($equity['nominal'])?></td>
                </tr>
            <?php       
                }
                $i++;
                endforeach;
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2" class="text-center"> Total Equity</th>
                <th class="text-right" id="total_equity"> <?=formatrupiah($total_equity)?> </th><input type="hidden" name="total_equity" id="total_equity" value="<?=$total_equity?>">
            </tr>
        </tfoot>
        
    </table>
</div>

