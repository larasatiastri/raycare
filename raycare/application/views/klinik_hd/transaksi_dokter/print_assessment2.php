<?
	
	// die_dump($form_tindakan_hd_penaksiran);
	
?>
<html>
<head>
	<style>
.text{
	font-size: 8pt;
}

.text2{
	font-size: 9pt;
}
</style>
</head>
<body>
<form>
<table width="100%" border="0" style="border: 1px solid black" class="text">
	<tr>
		<td></td>
	</tr>
	
	<tr>
		<td>
			<table width="900" border="0">
				<tr>
					<td colspan="2">Supervising of fluid during Hemodialysis</td>
				</tr>
				<tr>
					<td colspan="2" style="height:10px"></td>
				</tr>
				<tr>
					<td>Intake</td>
					<td>Output</td>
				</tr>
				<tr>
					<td valign="top">
						<table>
							<tr>
								<td>1.</td>
								<td>Remain of Priming</td>
								<td>:</td>
								<td style="border-bottom: 1px solid black" width="100px" ></td>
								<td>cc</td>
							</tr>
							<tr>
								<td>2.</td>
								<td>Wash Out</td>
								<td>:</td>
								<td style="border-bottom: 1px solid black" ></td>
								<td>cc</td>
							</tr>
							<tr>
								<td>3.</td>
								<td>Drip of fluid</td>
								<td>:</td>
								<td style="border-bottom: 1px solid black" ></td>
								<td>cc</td>
							</tr>
							<tr>
								<td>4.</td>
								<td>Blood</td>
								<td>:</td>
								<td style="border-bottom: 1px solid black" ></td>
								<td>cc</td>
							</tr>
							<tr>
								<td>5.</td>
								<td>Drink</td>
								<td>:</td>
								<td style="border-bottom: 1px solid black" ></td>
								<td>cc</td>
							</tr>
						</table>
					</td>
					<td valign="top">
						<table>
							<tr>
								<td>1.</td>
								<td>Vomitting</td>
								<td>:</td>
								<td style="border-bottom: 1px solid black" width="100px" ></td>
								<td>cc</td>
							</tr>
							<tr>
								<td>2.</td>
								<td>Urinate</td>
								<td>:</td>
								<td style="border-bottom: 1px solid black"></td>
								<td>cc</td>
							</tr>
						 </table>
					</td>
					 
				</tr>
			</table>
		</td>
	</tr>
     <tr>
					<td colspan="2" style="height:10px"></td>
				</tr>
  <tr>
		<td>
			<table width="900" border="0">
				<tr>
					<td colspan="2">Blood Transfusion</td>
				</tr>
				<tr>
					<td colspan="2" style="height:10px"></td>
				</tr>
				 
				<tr>
					<td valign="top">
						<table>
							<tr>
								<td>1.</td>
								<td width="100px" >Type</td>
								<td>:</td>
								<td style="border-bottom: 1px solid black" width="100px" ></td>
								<td></td>
							</tr>
							<tr>
								<td>2.</td>
								<td>Quantity</td>
								<td>:</td>
								<td style="border-bottom: 1px solid black" ></td>
								<td>Bag</td>
							</tr>
							<tr>
								<td>3.</td>
								<td>Blood Type</td>
								<td>:</td>
								<td style="border-bottom: 1px solid black" ></td>
								<td></td>
							</tr>
							<tr>
								<td valign="top">4.</td>
								<td valign="top">Serial No</td>
								<td valign="top">:</td>
								<td valign="top"></td>
								<td></td>
							</tr>
							 
						</table>
					</td>
					 
					 
				</tr>
			</table>
		</td>
	</tr>

	 
	 
</table>
<br>
<table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-top: 1px solid black;border-right: 1px solid black;border-top: 1px solid black;border-left: 1px solid black" class="text">
	<tr>
		<td colspan="8" align="center" style="border-bottom: 1px solid black" class="text2" ><b>MONITORING DIALYSIS</b></td>
	</tr>
	<tr>
		<td align="center" style="border-bottom: 1px solid black;border-right: 1px solid black">Hours</td>
		<td align="center"  width="70px" style="border-bottom: 1px solid black;border-right: 1px solid black">Blood Pressure</td>
		<td align="center" width="70px" style="border-bottom: 1px solid black;border-right: 1px solid black">Quick of Blood</td>
		<td align="center" width="70px" style="border-bottom: 1px solid black;border-right: 1px solid black">UF Goal</td>
		<td align="center" width="70px" style="border-bottom: 1px solid black;border-right: 1px solid black">UFR</td>
		<td align="center" width="70px" style="border-bottom: 1px solid black;border-right: 1px solid black">UFV</td>
		<td align="center" style="border-bottom: 1px solid black;border-right: 1px solid black">Nurse</td>
		<td align="center"  width="200px" style="border-bottom: 1px solid black">Explanation</td>
	</tr>
	<?foreach($form_observasi as $observasi){

		$user = $this->user_m->get($observasi['user_id']);
	?>

		<tr>
			<td align="center"  style="border-bottom: 1px solid black;border-right: 1px solid black"></td>
			<td align="center" style="border-bottom: 1px solid black;border-right: 1px solid black"></td>
			<td align="center" style="border-bottom: 1px solid black;border-right: 1px solid black"></td>
			<td align="center" style="border-bottom: 1px solid black;border-right: 1px solid black"></td>
			<td align="center" style="border-bottom: 1px solid black;border-right: 1px solid black"></td>
			<td align="center" style="border-bottom: 1px solid black;border-right: 1px solid black"></td>
			<td align="center" style="border-bottom: 1px solid black;border-right: 1px solid black"></td>
			<td align="center" style="border-bottom: 1px solid black"></td>
		</tr>
	<?}?>
</table>
<br>
<table width="100%"  style="border-top: 1px solid black;border-bottom: 1px solid black;" cellpadding="0" cellspacing="0" class="text">
	<tr>
		<td width="50%" style="border-right: 1px solid black;" valign="top">
			<table width="450">
				<tr>
					<td class="text2"><b>Medicine</b></td>
				</tr>
				<?php 
					$x=0;
					if($form_medicine != '0')
					{
						foreach($form_medicine as $medicine)
						{
							$checked = '';

							($medicine['is_show_assessment']) ? $checked = '' : $checked = '';
					
							echo '<tr><td><input type="checkbox" id="cek[]" '.$checked.' > '.$medicine['nama'].'</td> </tr>';
						}						
					}
					else
					{
						echo '<tr><td><input type="checkbox" id="cek[]" checked = "checked" > Etc </td> </tr>';
					}
				?>
			</table>
		</td>
		<td width="50%" style="padding-left:5px">
			<table width="450" border="0">
				<tr>
					<td class="text2"><b>Examination support</b></td>
				</tr>
				<tr>
					<td class="text2">Laboratory</td>
				</tr>
				<tr>
					<td  valign="top">
						 
						<?=preg_replace('/(\r\n|\n|\r|\f)/U', "<hr color=black style=margin-top:0;margin-bottom:5>&nbsp;", $form_tindakan_hd_penaksiran['laboratory'])?><hr color="black" style="margin-top:0;margin-bottom:0">
					 
					</td>
				</tr>
				 
				<tr>
					<td class="text2">ECG</td>
				</tr>
				<tr>
					<td ><?=preg_replace('/(\r\n|\n|\r|\f)/U', "<hr color=black style=margin-top:0;margin-bottom:5>&nbsp;", $form_tindakan_hd_penaksiran['ecg'])?> <hr color="black" style="margin-top:0;margin-bottom:0"></td>
				</tr>
				 <tr>
					<td height="10px"></td>
		 
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2"></td>
		 
	</tr>
</table>

<br>
<table width="100%"  style="border-bottom: 1px solid black;" cellpadding="0" cellspacing="0" class="text">
	<tr>
		<td width="90px">Priming</td>
		<td>: </td>
	</tr>
	<tr>
		<td>Initiation</td>
		<td>: </td>
	</tr>
	<tr>
		<td>Termination</td>
		<td>:</td>
	</tr>
	 <tr>
		<td colspan="2" style="height:10px"></td>
		 
	</tr>
</table>
<br>
<table  width="100%" border="0"   cellpadding="0" cellspacing="0" style="border-top: 1px solid black;border-right: 1px solid black;border-top: 1px solid black;border-left: 1px solid black" class="text">
	<tr>
		<td colspan="8" align="center" style="border-bottom: 1px solid black" class="text2" ><b>HEMODIALISYS HISTORY</b></td>
	</tr>
	<tr>
		<td align="center" style="border-bottom: 1px solid black;border-right: 1px solid black">Tanggal</td>
		<td align="center"  width="70px" style="border-bottom: 1px solid black;border-right: 1px solid black">Jenis Pelayanan</td>
		<td align="center" width="70px" style="border-bottom: 1px solid black;border-right: 1px solid black">BB Pre HD</td>
		<td align="center" width="70px" style="border-bottom: 1px solid black;border-right: 1px solid black">BB Post HD</td>
		<td align="center" width="70px" style="border-bottom: 1px solid black;border-right: 1px solid black">TD Pre HD</td>
		<td align="center" width="70px" style="border-bottom: 1px solid black;border-right: 1px solid black">TD Post HD</td>
		<td align="center" style="border-bottom: 1px solid black;border-right: 1px solid black">Single Use</td>
		<td align="center"  width="200px" style="border-bottom: 1px solid black">Re Use</td>
	</tr>
	<?foreach($form_hemodialisis_history as $row){?>

	<tr>
		<td align="center"  style="border-bottom: 1px solid black;border-right: 1px solid black"><?=date("d M Y", strtotime($row['tanggal']))?></td>
		<td align="center" style="border-bottom: 1px solid black;border-right: 1px solid black">HD</td>
		<td align="center" style="border-bottom: 1px solid black;border-right: 1px solid black"><?=$row['berat_awal']?></td>
		<td align="center" style="border-bottom: 1px solid black;border-right: 1px solid black"><?=$row['berat_akhir']?></td>
		<td align="center" style="border-bottom: 1px solid black;border-right: 1px solid black"><?=$row['td_pre_1'].'/'.$row['td_pre_2']?></td>
		<td align="center" style="border-bottom: 1px solid black;border-right: 1px solid black"><?=$row['td_post_1'].'/'.$row['td_post_2']?></td>
		<td align="center" style="border-bottom: 1px solid black;border-right: 1px solid black"><?if($row['dialyzer_new']==1){?><input type="checkbox" id="dialyzernew[]"   checked='checked' ><?}?></td>
		<td align="center" style="border-bottom: 1px solid black;border-right: 1px solid black"><?if($row['dialyzer_reuse']==1){?><input type="checkbox" id="dialyzerreuse[]"  checked='checked'  ><?}?></td>
		 
	</tr>
	<?}?>
</table>
</form>
</body>
</html>
