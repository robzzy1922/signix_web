<?php

namespace App\Helpers;

class ChartGenerator
{
    private static function createImage($width, $height)
    {
        $image = imagecreatetruecolor($width, $height);
        if (!$image) {
            throw new \Exception('Failed to create image');
        }
        $white = imagecolorallocate($image, 255, 255, 255);
        imagefill($image, 0, 0, $white);
        return $image;
    }

    private static function drawLegend($image, $labels, $colors, $x, $y)
    {
        $font = 5; // Built-in font
        $lineHeight = 20;
        $boxSize = 15;

        foreach ($labels as $i => $label) {
            $color = imagecolorallocate($image, ...self::hexToRgb($colors[$i]));
            imagefilledrectangle($image, $x, $y + ($i * $lineHeight), $x + $boxSize, $y + $boxSize + ($i * $lineHeight), $color);
            imagestring($image, $font, $x + $boxSize + 5, $y + ($i * $lineHeight), $label, imagecolorallocate($image, 0, 0, 0));
        }
    }

    private static function hexToRgb($hex)
    {
        $hex = ltrim($hex, '#');
        return array_map('hexdec', str_split($hex, 2));
    }

    public static function generateMonthlyChart($data)
    {
        try {
            $width = 800;
            $height = 400;
            $padding = 40;
            $image = self::createImage($width, $height);

            // Colors for different status
            $colors = ['#FCD34D', '#34D399', '#60A5FA'];
            $black = imagecolorallocate($image, 0, 0, 0);

            // Draw axes
            imageline($image, $padding, $height - $padding, $width - $padding, $height - $padding, $black);
            imageline($image, $padding, $padding, $padding, $height - $padding, $black);

            // Calculate max value for scaling
            $maxValue = 0;
            foreach ($data['datasets'] as $dataset) {
                $maxValue = max($maxValue, max($dataset['data'] ?? [0]));
            }
            $maxValue = $maxValue > 0 ? ceil($maxValue / 5) * 5 : 5;

            $plotWidth = $width - (2 * $padding);
            $plotHeight = $height - (2 * $padding);
            $xStep = count($data['labels']) > 1 ? $plotWidth / (count($data['labels']) - 1) : $plotWidth;
            $yScale = $maxValue > 0 ? $plotHeight / $maxValue : 0;

            // Plot data
            foreach ($data['datasets'] as $i => $dataset) {
                if (!empty($dataset['data'])) {
                    $color = imagecolorallocate($image, ...self::hexToRgb($colors[$i]));
                    $points = [];

                    foreach ($dataset['data'] as $j => $value) {
                        $x = $padding + ($j * $xStep);
                        $y = $height - $padding - ($value * $yScale);
                        $points[] = $x;
                        $points[] = $y;
                        imagefilledellipse($image, (int)$x, (int)$y, 6, 6, $color);
                    }

                    // Draw lines
                    for ($j = 0; $j < count($points) - 2; $j += 2) {
                        imageline($image, (int)$points[$j], (int)$points[$j + 1],
                                (int)$points[$j + 2], (int)$points[$j + 3], $color);
                    }
                }
            }

            // Draw labels
            foreach ($data['labels'] as $i => $label) {
                $x = $padding + ($i * $xStep);
                imagestring($image, 2, (int)($x - 20), $height - $padding + 5, $label, $black);
            }

            // Draw Y axis labels
            for ($i = 0; $i <= $maxValue; $i += 5) {
                $y = $height - $padding - ($i * $yScale);
                imagestring($image, 2, 5, (int)($y - 7), (string)$i, $black);
            }

            ob_start();
            imagepng($image);
            $imageData = ob_get_clean();
            imagedestroy($image);

            return $imageData;
        } catch (\Exception $e) {
            // Return a blank image on error
            $errorImage = self::createImage(400, 100);
            $textColor = imagecolorallocate($errorImage, 255, 0, 0);
            imagestring($errorImage, 5, 10, 40, "Error generating chart", $textColor);

            ob_start();
            imagepng($errorImage);
            $imageData = ob_get_clean();
            imagedestroy($errorImage);

            return $imageData;
        }
    }

    public static function generateUserActivityChart($data)
    {
        try {
            $width = 400;
            $height = 400;
            $centerX = $width / 2;
            $centerY = $height / 2;
            $radius = min($width, $height) / 3;

            $image = self::createImage($width, $height);
            $colors = ['#4F46E5', '#EF4444', '#9333EA'];

            $total = array_sum($data['datasets'][0]['data'] ?? [0]);
            if ($total > 0) {
                $startAngle = 0;
                foreach ($data['datasets'][0]['data'] as $i => $value) {
                    $color = imagecolorallocate($image, ...self::hexToRgb($colors[$i]));
                    $endAngle = $startAngle + ($value / $total) * 360;
                    imagefilledarc($image, $centerX, $centerY, $radius * 2, $radius * 2,
                                 $startAngle, $endAngle, $color, IMG_ARC_PIE);
                    $startAngle = $endAngle;
                }
            }

            ob_start();
            imagepng($image);
            $imageData = ob_get_clean();
            imagedestroy($image);

            return $imageData;
        } catch (\Exception $e) {
            // Return a blank image on error
            $errorImage = self::createImage(400, 100);
            $textColor = imagecolorallocate($errorImage, 255, 0, 0);
            imagestring($errorImage, 5, 10, 40, "Error generating chart", $textColor);

            ob_start();
            imagepng($errorImage);
            $imageData = ob_get_clean();
            imagedestroy($errorImage);

            return $imageData;
        }
    }

    public static function generateMonthlyActivityChart($data)
    {
        try {
            return self::generateMonthlyChart($data); // Reuse monthly chart logic
        } catch (\Exception $e) {
            // Return a blank image on error
            $errorImage = self::createImage(400, 100);
            $textColor = imagecolorallocate($errorImage, 255, 0, 0);
            imagestring($errorImage, 5, 10, 40, "Error generating chart", $textColor);

            ob_start();
            imagepng($errorImage);
            $imageData = ob_get_clean();
            imagedestroy($errorImage);

            return $imageData;
        }
    }
}
