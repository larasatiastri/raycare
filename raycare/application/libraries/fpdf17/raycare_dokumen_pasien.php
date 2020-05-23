<?php

class raycare_dokumen_pasien extends FPDF
{
    var $angle=0;
    const DPI = 96;
    const MM_IN_INCH = 25.4;
    const A4_HEIGHT = 297;
    const A4_WIDTH = 210;
    // tweak these values (in pixels)
    const MAX_WIDTH = 500;
    const MAX_HEIGHT = 300;
	
	function RAYCARE_DOKUMEN_PASIEN($orientation='P', $unit='mm', $size='A4')
	{
		
		// $this->data = unserialize(base64_decode($data_header));
		//$this->transaksi = unserialize(base64_decode($transaksi));

		$this->FPDF($orientation,$unit,$size);
		//MARGIN AWAL (TANPA LOGO)
		//$this->SetMargins(5, 30, 3);
		$this->SetMargins(5, 3, 3);
	}


 
    function pixelsToMM($val) {
        return $val * self::MM_IN_INCH / self::DPI;
    }
 
    function resizeToFit($imgFilename) {
        list($width, $height) = getimagesize($imgFilename);
 
        $widthScale = (self::MAX_WIDTH / $width);
        $heightScale = (self::MAX_HEIGHT / $height);
 
        $scale = min($widthScale, $heightScale);
 
        $returnWidth = ($width < 1000)?round($this->pixelsToMM($scale * $width * 3)):round($this->pixelsToMM($scale * $width * 4));
        $returnHeight = ($height < 1298)?round($this->pixelsToMM($scale * $height * 3)):round($this->pixelsToMM($scale * $height * 3.6));
        return array(
           $returnWidth,
           $returnHeight
            
        );
    }

    function resizeToFitNotRotate($imgFilename) {
        list($width, $height) = getimagesize($imgFilename);
 
        $widthScale = (self::MAX_WIDTH / $width);
        $heightScale = (self::MAX_HEIGHT / $height);
 
        $scale = min($widthScale, $heightScale);
 
        $returnWidth = ($width < 1000)?round($this->pixelsToMM($scale * $width * 2.15)):round($this->pixelsToMM($scale * $width * 4));
        $returnHeight = ($height < 600)?round($this->pixelsToMM($scale * $height * 2.4)):round($this->pixelsToMM($scale * $height * 2));
        return array(
           $returnWidth,
           $returnHeight
            
        );
    }

    function centreImage($img) {
        list($width, $height) = $this->resizeToFit($img);
 
        // you will probably want to swap the width/height
        // around depending on the page's orientation
        $this->Rotate(90,5,200);
        $this->Image(
            $img, 5,
            200,
            $width,
            $height
        );
    }

     function centreImageWithoutRotate($img) {
        list($width, $height) = $this->resizeToFitNotRotate($img);
 
        // you will probably want to swap the width/height
        // around depending on the page's orientation
       
        $this->Image(
            $img, 5,
            5,
            $width,
            $height
        );
    }

    function Rotate($angle, $x=-1, $y=-1)
    {
        if($x==-1)
            $x=$this->x;
        if($y==-1)
            $y=$this->y;
        if($this->angle!=0)
            $this->_out('Q');
        $this->angle=$angle;
        if($angle!=0)
        {
            $angle*=M_PI/180;
            $c=cos($angle);
            $s=sin($angle);
            $cx=$x*$this->k;
            $cy=($this->h-$y)*$this->k;
            $this->_out(sprintf('q %.5f %.5f %.5f %.5f %.2f %.2f cm 1 0 0 1 %.2f %.2f cm', $c, $s, -$s, $c, $cx, $cy, -$cx, -$cy));
        }
    }

    function _endpage()
    {
        if($this->angle!=0)
        {
            $this->angle=0;
            $this->_out('Q');
        }
        parent::_endpage();
    }




	function Header($data_header)
	{
		
	
	}

    
	function BasicTable($header,$data)
	{
    // Header
        //$this->Cell(10,6,"No",1,0,'C');
        foreach($header as $col)

            $this->Cell($col[1],6,$col[0],1,0,'C');
            $this->Ln();
    // Data
        $x=1;
        foreach($data as $row)
        {   
            //$this->Cell(10,5,$x,1,0,'C');
    
            $i=0;
            foreach($row as $col)
            {
                $this->Cell($header[$i][1],5,$col,1,0,'C');         
                
                $i++;
                
            }
            // foreach($row as $col)
            // {
            //     $this->Cell($header[$i][1],5,"-",1,0,'C');
            //     $i++;
            // }
                
            $this->Ln();    
            $x++;
        }

	}

	function BasicTableItem($header,$data)
	{
    // Header
    	$this->Cell(10,6,"No",1,0,'C');
    	foreach($header as $col)

        	$this->Cell($col[1],6,$col[0],1,0,'C');
    		$this->Ln();
    // Data
    	$x=1;
    	
    	foreach($data as $row)
    	{	
    			$this->Cell(10,5,$x,1,0,'C');
    			//$this->Ln();
    	
    		$i=0;
    		$sum=0;
        	foreach($row as $col)
        	{
        		$this->Cell($header[$i][1],5,$col,1,0,'C');        	
        		$i++;
        		
        	}
            	
        	$this->Ln();	
        	$x++;

    	}
    	$this->Cell(100,5,"TOTAL",1,0,'C');
    	$this->Cell(30,5,$sum,1,0,'C');
	}

	function Footer()
	{
		// $image_footer = base_url()."assets/img/footer.png";
		// $this->Cell( 40, 40, $this->Image($image_footer, $this->GetX()+9, 207, 70,17), 0, 0, 'L', false );
		// $this->Ln(3);
		
	}
}

?>