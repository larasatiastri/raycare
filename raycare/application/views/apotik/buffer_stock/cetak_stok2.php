
<html>
<head>
	   <style>
        body {
                font-family: "Open Sans", sans-serif;

        }
        .text{
            font-size: 8pt;
        }
        .text2{
            font-size: 9pt;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            text-align: left;
            padding: 4px;
        }

        tr:nth-child(even){background-color: #f2f2f2}

        th {
            background-color: #2462AC;
            color: white;
        }      

        
        #header 
            {
                width: 100%;
                border:0px solid green;
                margin-bottom:7px;
            }
        #body
        {
            width: 100%;
            margin: auto;
        }

        #body-table td{
            height: 75px;
        }

        #body-table{
            border-collapse:collapse;
            color:#333;
            width: 100%;
        }

        #body-table #signature{
            width: 20%;
            text-align: center;
            height: 0px;
        }

        #body-table th, #body-table td{
            vertical-align:top;
            padding:5px 10px;
            border:1px solid #000;
        }

        #title {
            text-transform: uppercase;
            text-align: center;
            font-size: 10px;
            /*margin-left: 30px;*/
            text-decoration:overline;
        }

        #no_surat {
            text-align: center;
            /*margin-left: 30px;*/
            text-decoration:overline;
        }

        .title-child{
            font-size: 10px;
        }

        .head{
            display: block;
            width: 100%;
            margin: auto;
            border:0px solid red;
            margin:0px;
            /*padding-left:10px;*/
        }
        .logo-a4{
            float: left;
            width : 150px;
            height: 70px;
            float:left;
            background-color:#fff;
            padding-right:180px;
        }
        .logo-a4-margin{
            float: left;
            width:55px;
            height: 70px; 
        }
        .logo-a4 img{
            width: 18px !important;
            height: 20px;
        }

        .logo-a4 div{
            margin-left:63px;
            padding:1px;
            background-color:#ed3237;
            border-radius:3px;
        }

        .rs-code p{
            color:#FFF;
            margin:0px;
            padding:0px;
            font-size:8px;
            text-align:center;
        }

        .address {
            width: 158px;
            height: 70px;
            float:left;
            border-left:1px solid #2462AC;
            padding-left:10px;
            padding-right:10px;

        }

        .address span{
            font-size:8px; color:#2462AC;
        }

        .socmed {
            width: 158px;
            height: 70px;
            float:left;
            background-color:#FFF;
            border-left:1px solid #2462AC;
            padding-left:10px;
        }

        .socmed span{
            font-size:8px;
            color:#2462AC;
        }
    </style>
</head>
	<body>
		  <?php 
                if (file_exists(FCPATH.config_item('site_logo_real')) && is_file(FCPATH.config_item('site_logo_real'))) 
                {
                    $image_header = base_url().config_item('site_logo_real');
                }
                else 
                {
                    $image_header = base_url()."assets/mb/global/image/logo/logo-real.png";
                }
            ?>
        <div id="header">
            <div class="head">
                <div class="logo-a4">
                    <img src="<?=$image_header?>">

                    <div class="rs-code">
                        <p>RS CODE : <?=$form_cabang['kode']?></p>
                    </div>
                </div>
                <div class="logo-a4-margin"></div>
                <div class="address">
                    <span><b style="">Address&nbsp;:</b></span>
                    <br>
                    <span><?=$cabang_alamat[0]['alamat']?></span>
                    <br>
                    <br>
                    <span><b>P.</b> <?=$cabang_telepon[0]['nomor']?></span>
                    <br>
                    <span><b>F.</b> <?=$cabang_fax[0]['nomor']?></span>
                </div>
                <div class="socmed">
                    <span><b>E.</b> <?=rtrim($data_email,', ')?></span>
                    <br>
                    <span><b>Follow &amp; Visit</b></span><br>
                    <span>fb : <?=$cabang_fb[0]['url']?></span><br>
                    <span>twitter <?=$cabang_twitter[0]['url']?></span><br>
                    <span><?=$cabang_website[0]['url']?></span>
                </div>
            </div>
        </div>
		
		<form>
			<div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption" align="center" style="font-size:16px;text-transform: uppercase;padding-bottom:10px;color:#2462AC;">
                            <strong><span class="caption-subject"><?=translate("Daftar stok yang kosong di ", $this->session->userdata("language"))?><?=$gudang['nama']?></span></strong>

                            

                        </div>
                    </div>
                    <div class="portlet-body">
                        <table class="table" id="table_buffer_stok">
                        <thead>
                        <tr>
                            <th width="1%"><?=translate("No", $this->session->userdata("language"))?> </th>
                            <th width="12%"><?=translate("Kode", $this->session->userdata("language"))?> </th>
                            <th><?=translate("Nama", $this->session->userdata("language"))?> </th>
                            <th><?=translate("Stok", $this->session->userdata("language"))?> </th>
                            <th><?=translate("Satuan", $this->session->userdata("language"))?> </th>
                            <th><?=translate("BN", $this->session->userdata("language"))?> </th>
                            <th><?=translate("ED", $this->session->userdata("language"))?> </th>
                        </tr>
                        
                        </thead>
                        <tbody  border="1">
                        <?php
                        	$i = 1;
                         	foreach ($result2 as $row) {
                        ?>
                        	<tr>
                        		<td><?=$i?></td>
                        		<td><?=$row['kode']?></td>
                        		<td><?=$row['nama']?></td>
                        		<td><?=0?></td>
                        		<td><?=$row['unit']?></td>
                        		<td></td>
                        		<td></td>
                        	</tr>
                        <?php
                        		$i++;
                         	}
                         ?>
                        </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
        </div> 
		</form>
	</body>
</html>
