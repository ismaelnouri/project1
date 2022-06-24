<?php
/**
*génération des factures et devis au format pdf
*/
namespace App\Service;
//use App\Service\HTML2PDF;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
use Spipu\Html2Pdf\Html2Pdf;
 
 
class T_HTML2PDF{

  private $pdf;
 
 
public function create($orientation=null,$format=null,$lang=null,$unicode=null,$encoding=null,$margin=null):void{
$this->pdf = new \Spipu\Html2Pdf\Html2Pdf(
    $orientation ? $orientation : $this->orientation,
    $format ? $format : $this->format,
    $lang ? $lang : $this->lang,
    $unicode ? $unicode : $this->unicode,
    $encoding ? $encoding : $this->encoding,
    $margin ? $margin : $this->margin
  );
}
 
public function generatePdf($affiche, $name){
 // ob_start();
  //$affiche = ob_get_clean();

   // $this->pdf->setDefaultFont('freeserif');
   // $this->pdf->setDefaultFont('times');
     $this->pdf->writeHTML($affiche);
    return $this->pdf->output($name.'.pdf');
}
 
 
}
 
 
// Fin génération des factures et devis au format pdf
