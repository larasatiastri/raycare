<html>
<head>
	
</head>
<body>
	<div id="header" style="padding-top:30px;background-color:#FFF;border-bottom:1px solid #2462AC;">
		<?php 
			if (file_exists(FCPATH."assets/mb/global/image/logo/logo-big1.png") && is_file(FCPATH."assets/mb/global/image/logo/logo-big1.png")) 
	        {
	            $image_header = base_url()."assets/mb/global/image/logo/logo-big1.png";
	        }
	        else 
	        {
	            $image_header = base_url()."assets/mb/global/image/logo/logo-global.png";
	        }
		?>
		<?php
			$supplier = $this->supplier_m->get($pembelian['supplier_id']);
			$supplier_alamat = $this->supplier_alamat_m->get_by(array('supplier_id' => $pembelian['supplier_id'], 'is_primary' => 1, 'is_active' => 1), true);
			$lokasi = $this->info_alamat_m->get_by(array('lokasi_kode' => $supplier_alamat->kode_lokasi), true);

			$supplier_telepon = $this->supplier_telepon_m->get_by(array('supplier_id' => $pembelian['supplier_id'], 'subjek_telp_id' => 2, 'is_active' => 1), true);
			$supplier_fax = $this->supplier_telepon_m->get_by(array('supplier_id' => $pembelian['supplier_id'], 'subjek_telp_id' => 8, 'is_active' => 1), true);
			$supplier_mobile = $this->supplier_telepon_m->get_by(array('supplier_id' => $pembelian['supplier_id'], 'subjek_telp_id' => 5, 'is_active' => 1), true);
			$supplier_email = $this->supplier_email_m->get_by(array('supplier_id' => $pembelian['supplier_id'], 'is_primary' => 1, 'is_active' => 1), true);
			$kelurahan = '';
			if(count($lokasi))
			{
				$kelurahan = ucwords(strtolower($lokasi->nama_kelurahan)).', '.ucwords(strtolower($lokasi->nama_kecamatan)).', '.ucwords(strtolower($lokasi->nama_kabupatenkota)).', '.ucwords(strtolower($lokasi->nama_propinsi));
			}

			$tempo = $this->supplier_tipe_pembayaran_m->get($pembelian['tipe_pembayaran']);
			$nama_pembayaran = $this->master_tipe_bayar_m->get($tempo->tipe_bayar_id);

			if($pembelian['tipe_customer'] == 1)
			{
				$customer  = $this->penerima_cabang_m->get($pembelian['customer_id']);
			}
			else
			{
				$customer  = $this->penerima_customer_m->get($pembelian['customer_id']);
			}

			$delivery_time = $this->pembelian_detail_m->get_tanggal_kirim($pembelian['id']);
		?>
		<div class="header-logo" style="float:left;width:200px;background-color:#fff;padding-bottom:8px;">
			<img src="<?=$image_header?>">
		</div>
		<div class="header-info" style="float:right;text-align:right;">
			<div style="font-size:11px !important;color: #2462AC;margin-bottom:6px;">Page {PAGENO} of {nb}</div>
			<div style="font-size:6px;">
				Print Number : <?=$no_cetak?>
			</div>
			<div style="font-size:12px;">
				<?=$pembelian['no_pembelian']?>
			</div>
		</div>
	</div>
</body>
</html>
