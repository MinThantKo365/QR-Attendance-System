<?php

namespace App\Support;

use BaconQrCode\Common\ErrorCorrectionLevel;
use BaconQrCode\Encoder\Encoder;
use RuntimeException;

class QrCodePng
{
    public static function generate(string $content, int $size = 320, int $margin = 2): string
    {
        if (! function_exists('imagecreatetruecolor')) {
            throw new RuntimeException('GD is required to generate QR codes.');
        }

        $qrCode = Encoder::encode($content, ErrorCorrectionLevel::M());
        $matrix = $qrCode->getMatrix();
        $width = $matrix->getWidth();
        $modules = $width + ($margin * 2);
        $scale = max(1, (int) floor($size / $modules));
        $imageSize = $modules * $scale;

        $image = imagecreatetruecolor($imageSize, $imageSize);
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 17, 24, 39);
        imagefill($image, 0, 0, $white);

        for ($y = 0; $y < $width; $y++) {
            for ($x = 0; $x < $width; $x++) {
                if ($matrix->get($x, $y) === 1) {
                    imagefilledrectangle(
                        $image,
                        ($x + $margin) * $scale,
                        ($y + $margin) * $scale,
                        (($x + $margin + 1) * $scale) - 1,
                        (($y + $margin + 1) * $scale) - 1,
                        $black
                    );
                }
            }
        }

        ob_start();
        imagepng($image);
        imagedestroy($image);

        return (string) ob_get_clean();
    }
}
