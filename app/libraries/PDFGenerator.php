<?php

/**
 * PDF Generator Helper
 * Wrapper untuk TCPDF atau fallback ke basic HTML-to-PDF
 */
class PDFGenerator
{
    private $title;
    private $author;
    private $content;
    
    public function __construct($title = 'Laporan Saham', $author = 'SahamPintar')
    {
        $this->title = $title;
        $this->author = $author;
        $this->content = '';
    }
    
    /**
     * Set HTML content
     */
    public function setContent($html)
    {
        $this->content = $html;
    }
    
    /**
     * Generate and output PDF
     */
    public function output($filename = 'laporan.pdf', $download = true)
    {
        // Check if TCPDF exists
        $tcpdfPath = APP_PATH . 'libraries/tcpdf/tcpdf.php';
        
        if (file_exists($tcpdfPath)) {
            $this->generateWithTCPDF($filename, $download);
        } else {
            $this->generateWithFPDF($filename, $download);
        }
    }
    
    /**
     * Generate PDF using TCPDF
     */
    private function generateWithTCPDF($filename, $download)
    {
        require_once APP_PATH . 'libraries/tcpdf/tcpdf.php';
        
        // Create PDF object
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        // Set document information
        $pdf->SetCreator($this->author);
        $pdf->SetAuthor($this->author);
        $pdf->SetTitle($this->title);
        
        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        
        // Set margins
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(TRUE, 15);
        
        // Add page
        $pdf->AddPage();
        
        // Set font
        $pdf->SetFont('helvetica', '', 10);
        
        // Write content
        $pdf->writeHTML($this->content, true, false, true, false, '');
        
        // Output
        if ($download) {
            $pdf->Output($filename, 'D'); // Force download
        } else {
            $pdf->Output($filename, 'I'); // Display inline
        }
    }
    
    /**
     * Generate basic PDF without TCPDF (fallback)
     * Uses HTML to PDF conversion via browser print
     */
    private function generateWithFPDF($filename, $download)
    {
        // Fallback: Output as HTML with print CSS
        // This will allow browser's print-to-PDF feature
        
        if ($download) {
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
        } else {
            header('Content-Type: text/html; charset=utf-8');
        }
        
        echo '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>' . htmlspecialchars($this->title) . '</title>
    <style>
        @media print {
            body { margin: 0; padding: 20px; }
            .no-print { display: none; }
        }
        body { 
            font-family: Arial, sans-serif; 
            font-size: 12px;
            line-height: 1.6;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 15px 0;
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 8px; 
            text-align: left; 
        }
        th { 
            background-color: #3b82f6; 
            color: white; 
            font-weight: bold;
        }
        h1 { color: #1e40af; margin-top: 0; }
        h2 { color: #3b82f6; border-bottom: 2px solid #3b82f6; padding-bottom: 5px; }
        .text-success { color: #10b981; }
        .text-danger { color: #ef4444; }
    </style>
    <script>
        // Auto print on load if download requested
        ' . ($download ? 'window.onload = function() { window.print(); };' : '') . '
    </script>
</head>
<body>';
        
        echo $this->content;
        
        echo '
    <div class="no-print" style="margin-top: 30px; text-align: center; border-top: 1px solid #ddd; padding-top: 15px;">
        <p><em>Note: TCPDF library not installed. Using browser print preview.</em></p>
        <p>Press Ctrl+P (Windows) or Cmd+P (Mac) to print/save as PDF</p>
    </div>
</body>
</html>';
        
        exit;
    }
}
