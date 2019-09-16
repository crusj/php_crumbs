<?php
/**
 * author     jianglong
 * date       2019/8/27 上午10:50
 */

namespace crusj\php_crumbs\classes;


/**
 * 向图片嵌入文字或图片
 * Class EmbedToImage
 * @package php_crumbs\classes
 *
 * @author jianglong
 */
class EmbedToImage
{
    /**
     * 向图片嵌入文字
     * @param int $textOffsetY
     * @param string $sourceImageName
     * @param string $dstImageName 文件存储路径
     * @param string $text
     * @param int $fontSize
     * @param string $fontPath
     * @param array $fontColor
     * @param float $fontAlpha
     * @return bool
     * @author jianglong
     */
    public function embedCenterText(int $textOffsetY, string $sourceImageName, string $dstImageName,
                                    string $text, int $fontSize, string $fontPath,
                                    array $fontColor = [255, 255, 255], float $fontAlpha = 0): bool
    {
        if (!is_file($sourceImageName)) {
            return false;
        }
        $sourceImage = getimagesize($sourceImageName);
        $width = $sourceImage[0];//图片宽

        switch ($sourceImage['mime']) {
            case 'image/jpeg':
                $dstImage = imagecreatefromjpeg($sourceImageName);
                break;
            case 'image/png':
                $dstImage = imagecreatefrompng($sourceImageName);
                break;
            default:
                return false;
        }

        $textProperty = imagettfbbox($fontSize, 0, $fontPath, $text);
        $textWidth = $textProperty[2] - $textProperty[0];
        $fontColor = imagecolorclosestalpha($dstImage, $fontColor[0], $fontColor[1], $fontColor[2], $fontAlpha);

        $textOffsetX = ($width - $textWidth) / 2;
        imagettftext($dstImage, $fontSize, 0, $textOffsetX, $textOffsetY, $fontColor, $fontPath, $text);
        imagepng($dstImage, $dstImageName);
        return true;
    }

    public function embedImage(string $embedImageName, string $sourceImageName, string $dstImageName,
                               int $embedX, int $embedY,
                               int $offsetX, int $offsetY

    ): bool
    {
        if (!is_file($embedImageName) || !is_file($sourceImageName)) {
            return false;
        }
        //嵌入的图片
        $embedImageProperty = getimagesize($embedImageName);
        switch ($embedImageProperty['mime']) {
            case 'image/jpeg':
                $embedImage = imagecreatefromjpeg($embedImageName);
                break;
            case 'image/png':
                $embedImage = imagecreatefrompng($embedImageName);
                break;
            default:
                return false;
        }
        //被嵌入的图片
        $sourceImageProperty = getimagesize($sourceImageName);
        switch ($sourceImageProperty['mime']) {
            case 'image/jpeg':
                $sourceImage = imagecreatefromjpeg($sourceImageName);
                break;
            case 'image/png':
                $sourceImage = imagecreatefrompng($sourceImageName);
                break;
            default:
                return false;
        }


        //嵌入图片按照嵌入尺寸进行缩放
        $scaleEmbedImage = imagecreatetruecolor($embedX, $embedY);
        list($embedImageWidth, $embedImageHeight) = getimagesize($embedImageName);
        imagecopyresampled($scaleEmbedImage, $embedImage, 0, 0, 0, 0, $embedX, $embedY, $embedImageWidth, $embedImageHeight);

        //创建被嵌入图片的画布
        $dstImage = imagecreatetruecolor(imagesx($sourceImage), imagesy($sourceImage));
        $color = imagecolorallocate($dstImage, 255, 255, 255);
        imagefill($dstImage, 0, 0, $color);

        $sourceImageWidth = imagesx($sourceImage);
        $sourceImageHeight = imagesy($sourceImage);
        imagecopyresampled($dstImage, $sourceImage, 0, 0, 0, 0, $sourceImageWidth, $sourceImageHeight, $sourceImageWidth, $sourceImageHeight);
        imagecopymerge($dstImage, $scaleEmbedImage,$offsetX,$offsetY,0,0,imagesx($scaleEmbedImage),imagesy($scaleEmbedImage),100);
        imagepng($dstImage,$dstImageName);
        return true;
    }
}
