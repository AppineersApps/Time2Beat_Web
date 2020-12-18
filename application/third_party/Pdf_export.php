<?php
require_once('tcpdf/config/tcpdf_config.php');

require_once 'tcpdf/tcpdf.php';

// extend TCPF with custom functions
class PDF_Export extends TCPDF
{

    public $module_name;
    public $ctrl_object;
    public $content_info;

    public function setCIObject()
    {
        $this->CI = & get_instance();
    }

    public function setModule($module = '')
    {
        $this->module_name = $module_name;
    }

    public function setContent($content = array())
    {
        $this->content_info = $content;
    }

    public function setController($ctrl_obj = '')
    {
        $this->ctrl_object = $ctrl_obj;
    }

    public function initialize($header = 'Records')
    {
        $this->setCIObject();

        // set document information
        $this->SetCreator(PDF_CREATOR);
        $this->SetAuthor(PDF_AUTHOR);
        $this->SetTitle(PDF_TITLE);
        $this->SetSubject(PDF_SUBJECT);
        $this->SetKeywords(PDF_KEYWORDS);

        // set default header data
        $this->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $header, '', array(0, 0, 0), array(255, 255, 255));
        #$this->setFooterData(array(0, 0, 0), array(255, 255, 255));
        // set header and footer fonts
        $this->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $this->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $this->SetHeaderMargin(PDF_MARGIN_HEADER);
        $this->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $this->setImageScale(PDF_IMAGE_SCALE_RATIO);

        if ($this->CI->config->item('MULTI_LINGUAL_PROJECT') == "Yes") {

//            if (in_array($this->CI->session->userdata('DEFAULT_LANG'), array("AR", "FA"))) {
            // set some language dependent data:
            $lg = array();
            $lg['a_meta_charset'] = 'UTF-8';
            $lg['a_meta_dir'] = 'rtl';
            $lg['a_meta_language'] = 'fa';
            $lg['w_page'] = 'page';

            // set some language-dependent strings (optional)
            $this->setLanguageArray($lg);

            //After Write
            $this->setRTL(false);

            // set font
            $this->SetFont('dejavusans', '', PDF_FONT_SIZE_DATA);
//            } else {
//
//                // set font
//                $this->SetFont(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA);
//            }
        } else {

            // set font
            $this->SetFont(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA);
        }

        // add zoom
        $this->SetDisplayMode(100);

        if (is_object($this->ctrl_object) && method_exists($this->ctrl_object, "citPDFInitializeCallback")) {
            $callback_res = $this->ctrl_object->citPDFInitializeCallback($this, $header);
            if ($callback_res == 2) {
                return;
            }
        }
        if (method_exists($this->CI->general, "citPDFInitializeCallback")) {
            $callback_res = $this->CI->general->citPDFInitializeCallback($this, $header);
            if ($callback_res == 2) {
                return;
            }
        }

        // add page
        $this->AddPage();
    }

    public function writeGridTable($headers = array(), $data = array(), $widths = array(), $aligns = array())
    {
        $this->setCIObject();

        if (is_object($this->ctrl_object) && method_exists($this->ctrl_object, "citPDFWriteTableCallback")) {
            $callback_res = $this->ctrl_object->citPDFWriteTableCallback($this, $headers, $data, $widths, $aligns);
            if ($callback_res == 2) {
                return;
            }
        }
        if (method_exists($this->CI->general, "citPDFWriteTableCallback")) {
            $callback_res = $this->CI->general->citPDFWriteTableCallback($this, $headers, $data, $widths, $aligns);
            if ($callback_res == 2) {
                return;
            }
        }

        $header_style = "font-size:15px;";
        if (empty($this->content_info['pdf_header_style'])) {
            $header_style = $this->content_info['pdf_header_style'];
        }
        $widths = $this->getProperWidths($widths);
        //header html
        $headers_html = '
    <thead>
    <tr>';
        for ($i = 0; $i < count($headers); $i++) {
            $aligns[$i] = in_array(strtolower($aligns[$i]), array("center", "right")) ? strtolower($aligns[$i]) : "left";
            $headers_html .= '
        <th width="' . $widths[$i] . '%" align="' . $aligns[$i] . '" bgcolor="#EAEAEA" style="' . $header_style . '" border="1"><b>' . $headers[$i] . '</b></th>';
        }
        $headers_html .= '
    </tr>
    </thead>';

        //data html
        $data_html = '<tbody>';
        for ($i = 0; $i < count($data); $i++) {
            $data_html .= '
    <tr>';
            $j = 0;
            foreach ($data[$i] as $key => $val) {
                if (is_array($val) && $val['file']) {
                    if ($val['file'] == 1) {
                        $value = '<img src="' . $val['data'] . '" width="' . $val['width'] . '" height="' . $val['height'] . '" />';
                    } elseif ($val['file'] == 2) {
                        $value = '<a href="' . $val['data'] . '" color="#01bbe4">Download</a>';
                    } else {
                        $value = $val['data'];
                    }
                } else {
                    $value = $val;
                }
                $data_html .= '
        <td width="' . $widths[$j] . '%" align="' . $aligns[$j] . '" border="1">' . $value . '</td>';
                $j++;
            }
            $data_html .= '
    </tr>';
        }
        $data_html .= '
    </tbody>';

        // table html
        $table_html = '';
        if (!empty($this->content_info['pdf_content_before_table'])) {
            $table_html .= $this->content_info['pdf_content_before_table'];
        }
        $table_html .= '
<table width="100%" cellpadding="4" cellspacing="0" border="0">
    ' . $headers_html . '
    ' . $data_html . '
</table>';
        if (!empty($this->content_info['pdf_content_after_table'])) {
            $table_html .= $this->content_info['pdf_content_after_table'];
        }
        $this->writeHTML($table_html);
    }

    public function getProperWidths($widths = array(), $limit = 100)
    {
        $sum = array_sum($widths);
        for ($i = 0; $i < count($widths); $i++) {
            if ($sum != $limit) {
                $widths[$i] = round(($widths[$i] / $sum) * $limit);
            }
        }
        $tot = array_sum($widths);
        if ($tot > $limit) {
            $widths[count($widths) - 1] = end($widths) - ($tot - $limit);
        }
        return $widths;
    }
}

class PDF_Custom_Export extends PDF_Export
{

    public $custom_logo_path = "";
    public $custom_heading_text = "";

    public function Header()
    {
        // Logo
        $image_file = $this->CI->config->item('site_path') . "public/images/front/logo.png";
        if ($this->custom_logo_path != "") {
            $image_file = $this->custom_logo_path;
        }
        $this->Image($image_file, 20, 5, 35, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        if ($this->custom_heading_text != "") {
            $this->SetFont('helvetica', 'B', 20);
            // Title
            $this->Cell(0, 15, $this->custom_heading_text, 0, false, 'C', 0, '', 0, false, 'T', 'M');
        }
    }
}
