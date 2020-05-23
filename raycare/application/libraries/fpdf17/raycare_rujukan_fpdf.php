<?php

class raycare_rujukan_fpdf extends FPDF
{
    

    function RAYCARE_RUJUKAN_FPDF($orientation='P', $unit='mm', $size='A4')
    {
        
        // $this->data = unserialize(base64_decode($data_header));
        //$this->transaksi = unserialize(base64_decode($transaksi));

        $this->FPDF($orientation,$unit,$size);
        //MARGIN AWAL (TANPA LOGO)
        //$this->SetMargins(5, 30, 3);
        $this->SetMargins(5, 3, 3);
    }

    function Header($data_header)
    {
        
        $data = unserialize(base64_decode($data_header));

        // NAMBAHIN LOGO
        $image_header = base_url()."assets/global/img/logo.png";
        $this->Cell( 40, 40, $this->Image($image_header, $this->GetX(), $this->GetY()+1, 50,13), 0, 0, 'L', false );
        $this->Ln(3);
        
        // $last_y = $this->GetY() + 20;
        // $this->SetY($last_y);

        // $posisi_x = $this->GetX();
        // $posisi_y = $this->GetY();
        // $this->Line(0, $posisi_y-2, 95, $posisi_y-2);
        // $this->Line(0, $posisi_y-1, 95, $posisi_y-1);
        
        // $last_y = $this->GetY() + 1;
        // $this->SetY($last_y);
        // $this->SetFont('Arial','B',7);
        // $this->Cell(0,0,"TREATMENT RECEIPT NOTE",0,0,'C');
        // $this->Ln(3);

        // $posisi_x = $this->GetX();
        // $posisi_y = $this->GetY();
        // $geser_x = 35;
        // $geser_y = -2;
        // $this->Line(30, $posisi_y-2, 65, $posisi_y-2);

        // $this->SetFont('Arial','',7);
        // $this->Cell(0,0,"TANDA TERIMA JASA",0,0,'C');
        // $this->Ln(4);
        
        $last_y = $this->GetY() + 20;
        $this->SetY($last_y);
        $this->SetFont('Arial','B',7);
        $this->Cell(0,0,"TREATMENT RECEIPT NOTE",0,0,'C');
        $this->Ln(3);

        $posisi_x = $this->GetX();
        $posisi_y = $this->GetY();
        $geser_x = 35;
        $geser_y = -2;
        $this->Line(30, $posisi_y-2, 68, $posisi_y-2);

        $this->SetFont('Arial','',7);
        $this->Cell(0,0,"TANDA TERIMA JASA",0,0,'C');
        $this->Ln(4);

    
    }

    function BasicTable($header,$data)
    {
    // Header
        $this->Cell(10,6,"No",0,0,'C');
        foreach($header as $col)

            $this->Cell($col[1],6,$col[0],0,0,'C');
            $this->Ln();
    // Data
        $x=1;
        foreach($data as $row)
        {   
            $this->Cell(10,5,$x,0,0,'C');
    
            $i=0;
            foreach($row as $col)
            {
                $this->Cell($header[$i][1],5,$col,0,0,'C');         
                
                $i++;
                
            }
                
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