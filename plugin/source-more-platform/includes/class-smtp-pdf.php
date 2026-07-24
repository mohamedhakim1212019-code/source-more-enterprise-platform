<?php
if (!defined('ABSPATH')) exit;
class SMTP_Simple_PDF {
    private function clean($s){ $s=wp_strip_all_tags((string)$s); $converted=function_exists('iconv')?iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$s):$s; $s=$converted!==false?$converted:$s; return str_replace(['\\','(',')'],['\\\\','\\(','\\)'],$s); }
    private function money($n){ return 'EGP '.number_format((float)$n,0); }
    public function output_lead($id){
        $m=function($k)use($id){return get_post_meta($id,$k,true);};
        $lines=[
          ['Source More Technology',22],['Fleet Savings Assessment Report',17],['Generated: '.date_i18n('d M Y'),10],['',8],
          ['Prepared for',14],[$m('company'),13],['Contact: '.$m('contact_name').' | '.$m('email').' | '.$m('phone'),10],['Industry: '.$m('industry').' | Locations: '.$m('locations'),10],['',8],
          ['Executive Savings Summary',14],['Current annual print cost: '.$this->money($m('current_cost')),12],['Potential annual savings: '.$this->money($m('annual_savings')),14],['Optimized annual cost: '.$this->money($m('optimized_cost')),12],['Three-year savings opportunity: '.$this->money($m('three_year')),12],['',8],
          ['Current Environment',14],['Devices / MFPs: '.$m('devices'),11],['Monthly mono pages: '.number_format((float)$m('mono_pages')),11],['Monthly color pages: '.number_format((float)$m('color_pages')),11],['Mono cost per page: EGP '.$m('mono_cpp'),11],['Color cost per page: EGP '.$m('color_cpp'),11],['Monthly service, rental and maintenance: '.$this->money($m('fixed_cost')),11],['Planning optimization assumption: '.$m('saving_rate').'%',11],['',8],
          ['Recommended Next Steps',14],['1. Validate the device inventory, meter readings and contracts.',11],['2. Analyze utilization, placement, cost per page and service history.',11],['3. Build a right-sized fleet and managed service roadmap.',11],['4. Confirm savings through a professional on-site assessment.',11],['',8],
          ['Important note',12],['This report is an indicative planning estimate, not a commercial quotation. Verified savings require a detailed fleet assessment.',9],['',8],
          ['Source More Technology | One Source, More Value',11],[home_url('/'),9]
        ];
        $content="BT\n"; $y=800;
        foreach($lines as $row){ [$text,$size]=$row; if($y<55){$content.="ET\n";break;} $content.="/F1 {$size} Tf\n1 0 0 1 55 {$y} Tm\n(".$this->clean($text).") Tj\n"; $y-=($size+8); }
        $content.="ET";
        $objects=[];
        $objects[]='<< /Type /Catalog /Pages 2 0 R >>';
        $objects[]='<< /Type /Pages /Kids [3 0 R] /Count 1 >>';
        $objects[]='<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 5 0 R >> >> /Contents 4 0 R >>';
        $objects[]='<< /Length '.strlen($content).' >>' . "\nstream\n".$content."\nendstream";
        $objects[]='<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>';
        $pdf="%PDF-1.4\n"; $offsets=[0];
        foreach($objects as $i=>$obj){$offsets[]=strlen($pdf);$n=$i+1;$pdf.="$n 0 obj\n$obj\nendobj\n";}
        $xref=strlen($pdf); $pdf.="xref\n0 ".(count($objects)+1)."\n0000000000 65535 f \n";
        for($i=1;$i<=count($objects);$i++)$pdf.=sprintf('%010d 00000 n ', $offsets[$i])."\n";
        $pdf.='trailer << /Size '.(count($objects)+1).' /Root 1 0 R >>'."\nstartxref\n$xref\n%%EOF";
        $filename='source-more-fleet-savings-'.sanitize_file_name($m('company')).'.pdf';
        while(ob_get_level()){ob_end_clean();} nocache_headers(); header('X-Content-Type-Options: nosniff'); header('Content-Type: application/pdf'); header('Content-Disposition: attachment; filename="'.$filename.'"'); header('Content-Length: '.strlen($pdf)); echo $pdf;
    }
}
