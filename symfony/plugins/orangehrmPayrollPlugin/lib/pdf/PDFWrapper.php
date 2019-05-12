<?php


class PDFWrapper
{

    protected $html;
    protected $pdf;
    private static $cpu='';

    /**
     * @return mixed
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * @param mixed $html
     */
    public function setHtml($htmlContent)
    {

        file_put_contents($this->tempHtmlfile,$htmlContent);
    }


    public function __construct()
    {
        $this->tempHtmlfile = tempnam(sys_get_temp_dir(), 'ohrmpayroll').'.html';
        $this->tempPdfFile  = tempnam(sys_get_temp_dir(), 'ohrmpayroll').'.pdf';
        $this->pdfLibCommnad = sfConfig::get('sf_plugins_dir') . DIRECTORY_SEPARATOR . 'orangehrmPayrollPlugin' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR .'pdf' . DIRECTORY_SEPARATOR . 'wkhtmltopdf-' . $this->_getCPU();
    }

    private static function _getCPU(){
        if(self::$cpu==''){
            $arch = php_uname('m');
            if (preg_match("/^x(86_)*64$/", $arch))
                self::$cpu = 'amd64';
            elseif (preg_match("/^(i[3-6]|x)86$/", $arch))
                self::$cpu = 'i386';
            else throw new Exception('WKPDF couldn\'t determine CPU ("'.`grep -i vendor_id /proc/cpuinfo`.'").');                        }
        return self::$cpu;
    }

    public function generatePDF(){

        $pdfCommand = ' "'.$this->pdfLibCommnad.'" '.$this->tempHtmlfile.' '.$this->tempPdfFile.' 2>&1';
        $result = exec($pdfCommand, $output, $returnStatus);

        if($returnStatus !=0){
            unlink($this->tempPdfFile);
            unlink($this->tempHtmlfile);
            throw new Exception('Something goes wrong with pdf genration');

        }

        $this->pdf =$output;
    }

    public function downloadPDF($fileName){
        if(!headers_sent()){
            header('Content-Description: File Transfer');
            header('Cache-Control: public, must-revalidate, max-age=0'); // HTTP/1.1
            header('Pragma: public');
            header('Expires: Fri, 01 Jan 1970 05:00:00 GMT'); // Date in the past
            header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
            // force download dialog
            header('Content-Type: application/force-download');
            header('Content-Type: application/octet-stream', false);
            header('Content-Type: application/download', false);
            header('Content-Type: application/pdf', false);
            // use the Content-Disposition header to supply a recommended filename
            header('Content-Disposition: attachment; filename="'.basename($fileName).'";');
            header('Content-Transfer-Encoding: binary');
            header('Content-Length: '.strlen( file_get_contents($this->tempPdfFile)));

            echo file_get_contents($this->tempPdfFile);
            unlink($this->tempPdfFile);
            unlink($this->tempHtmlfile);
            exit();
        }else{
            throw new Exception('WKPDF download headers were already sent.');
        }
    }

    public function viewPDF($file){
        if(!headers_sent()){
            header('Content-Type: application/pdf');
            header('Cache-Control: public, must-revalidate, max-age=0'); // HTTP/1.1
            header('Pragma: public');
            header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
            header('Content-Length: '.strlen(file_get_contents($this->tempPdfFile)));
            header('Content-Disposition: inline; filename="'.basename($file).'";');
            echo file_get_contents($this->tempPdfFile);
            unlink($this->tempPdfFile);
            unlink($this->tempHtmlfile);
            exit();
        }else{
            throw new Exception('WKPDF embed headers were already sent.');
        }
    }
}