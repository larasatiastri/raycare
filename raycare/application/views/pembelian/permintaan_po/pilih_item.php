<div id="popover_item_content">
    <div class="tabbable-custom nav-justified">
        <ul class="nav nav-tabs nav-justified">
            <li  class="active">
                <a href="#item" data-toggle="tab">
                    <?=translate('Item', $this->session->userdata('language'))?> </a>
            </li>
           
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="item" >
                <?php include('tab_item/item.php') ?>
            </div>
            
        </div>
    </div>
</div>