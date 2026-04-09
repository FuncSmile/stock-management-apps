<?php

namespace App\Libraries;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;

class QrGenerator
{
    /**
     * Generate QR Code and save to public uploads
     * 
     * @param string $data Data to be encoded (ID or SKU)
     * @param string $sku SKU for filename
     * @return string Relative path to the generated file
     */
    public function generate(string $data, string $sku): string
    {
        $qrCode = new QrCode(
            data: $data,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin
        );

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        $path = 'uploads/qr/' . $sku . '.png';
        $fullPath = FCPATH . $path;

        // Ensure directory exists
        if (!is_dir(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0775, true);
        }

        $result->saveToFile($fullPath);

        return $path;
    }
}
