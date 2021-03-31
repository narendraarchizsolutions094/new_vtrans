<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**


* CodeIgniter PDF Library
 *
 * Generate PDF's in your CodeIgniter applications.
 *
 * @package         CodeIgniter
 * @subpackage      Libraries
 * @category        Libraries
 * @author          Chris Harvey
 * @license         MIT License
 * @link            https://github.com/chrisnharvey/CodeIgniter-  PDF-Generator-Library



*/

require_once FCPATH.'third_party/vendor/autoload.php';


use Dompdf\Dompdf;
class Pdf extends DOMPDF
{
/**
 * Get an instance of CodeIgniter
 *
 * @access  protected
 * @return  void
 */
protected function ci()
{
    return get_instance();
}

/**
 * Load a CodeIgniter view into domPDF
 *
 * @access  public
 * @param   string  $view The view to load
 * @param   array   $data The view data
 * @return  void
 */
public function load_view($view, $data = array(),$pdfFilePath1)
{
    define("DOMPDF_UNICODE_ENABLED", true);
    $dompdf = new Dompdf(array('enable_remote' => true));
    $html = $this->ci()->load->view($view, $data, TRUE);

    $dompdf->loadHtml($html);

    // (Optional) Setup the paper size and orientation
    $dompdf->setPaper('A4', 'portrait');

    // Render the HTML as PDF
    $dompdf->render();
  
    // Output the generated PDF to Browser
    //$pdf_string =   $dompdf->output($pdfFilePath1, 'F');
    $pdf = $dompdf->output();
    
    if(!empty($pdfFilePath1)){
        $file_location = $pdfFilePath1;
        file_put_contents($file_location,$pdf); 
    }
    
    $dompdf->stream("quotation.pdf");    
    //exit();

    // echo $pdf_string;
    // file_put_contents($pdfFilePath1, $pdf_string ); 
    //file_put_contents('time.pdf', $output);

   
}
public function create($html,$action=0,$pdfFilePath1='',$size=0){
    $dompdf = new Dompdf(array('enable_remote' => true));
    $dompdf->loadHtml($html);
    if(is_array($size))
    {
        $dompdf->setPaper($size);
    }
    else
        $dompdf->setPaper('A4', 'portrait');
    
    $dompdf->render();    

    $pdf = $dompdf->output();
    
    if(!empty($pdfFilePath1)){
        $file_location = $pdfFilePath1;
        file_put_contents($file_location,$pdf); 
        $title = explode('/',$file_location);
        $title = end($title);
        $dompdf->stream($title, array('Attachment'=>$action));
    }
    else
    {
        $title = 'Quotation.pdf';
        $dompdf->stream($title, array('Attachment'=>$action));
    }
    
}

}