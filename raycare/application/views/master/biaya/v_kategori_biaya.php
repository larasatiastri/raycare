<?php 
			echo "
			<select name='kategori_biaya_id' id='select-kategori_biaya_id' required>
 			<option value='' disabled selected>Pilih Biaya</option>";
  			foreach ($var_kategori_biaya->result() as $row_kategori_biaya) {  
  			echo "<option value='".$row_kategori_biaya->id."'>".$row_kategori_biaya->nama."</option>";
  		}
  			echo"
			</select>";
			?>