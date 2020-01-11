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
     * 向图片嵌入居中文字
     * @param int $textOffsetY Y轴偏移量
     * @param string $sourceImageName 被嵌入原图片路径
     * @param string $dstImageName 嵌入后图片路径
     * @param string $text 嵌入文字
     * @param int $fontSize 嵌入文字大小
     * @param string $fontPath 嵌入文字字体
     * @param array $fontColor 嵌入文字字体颜色
     * @param float $fontAlpha 嵌入文字字体alpha
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

        $x = imagesx($dstImage);
        $y = imagesy($dstImage);

        $img = imagecreatetruecolor($x, $y);
        $alpha = imagecolorallocatealpha($img, 0, 0, 0, 127);
        imagefill($img, 0, 0, $alpha);
        imagecopyresampled($img, $dstImage, 0, 0, 0, 0, $x, $y, $x, $y);

        $textProperty = imagettfbbox($fontSize, 0, $fontPath, $text);
        $textWidth = $textProperty[2] - $textProperty[0];
        $fontColor = imagecolorclosestalpha($dstImage, $fontColor[0], $fontColor[1], $fontColor[2], $fontAlpha);
        $textOffsetX = ($width - $textWidth) / 2;

        imagettftext($img, $fontSize, 0, $textOffsetX, $textOffsetY, $fontColor, $fontPath, $text);
        imagesavealpha($img, true);
        imagepng($img, $dstImageName);
        return true;
    }

    /**
     * 嵌入图片
     * @param string $embedImageName 嵌入的图片路径
     * @param string $sourceImageName 被嵌入的原图片路径
     * @param string $dstImageName 嵌入后生成的图片路径
     * @param int $embedX 嵌入图片的宽
     * @param int $embedY 嵌入图片的高
     * @param int $offsetX 嵌入图片的X轴偏移
     * @param int $offsetY 嵌入图片的Y轴便宜
     * @return bool
     */
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
        $color = imagecolorallocate($scaleEmbedImage, 255, 255, 255);
        imagecolortransparent($scaleEmbedImage, $color);
        imagefill($scaleEmbedImage, 0, 0, $color);

        list($embedImageWidth, $embedImageHeight) = getimagesize($embedImageName);
        imagecopyresampled($scaleEmbedImage, $embedImage, 0, 0, 0, 0, $embedX, $embedY, $embedImageWidth, $embedImageHeight);

        //创建被嵌入图片的画布
        $dstImage = imagecreatetruecolor(imagesx($sourceImage), imagesy($sourceImage));
        $color = imagecolorallocatealpha($dstImage, 0, 0, 0, 127);
        imagefill($dstImage, 0, 0, $color);

        $sourceImageWidth = imagesx($sourceImage);
        $sourceImageHeight = imagesy($sourceImage);

        imagecopyresampled($dstImage, $sourceImage, 0, 0, 0, 0, $sourceImageWidth, $sourceImageHeight, $sourceImageWidth, $sourceImageHeight);
        imagecopymerge($dstImage, $scaleEmbedImage, $offsetX, $offsetY, 0, 0, imagesx($scaleEmbedImage), imagesy($scaleEmbedImage), 100);
        imagesavealpha($dstImage, true);
        imagepng($dstImage, $dstImageName);
        return true;
    }

    /**
     * 嵌入文字
     * @param string $text 需要嵌入的文字
     * @param int $textOffsetX 嵌入文字的X轴偏移量
     * @param int $textOffsetY 嵌入文字的Y轴偏移量
     * @param string $sourceImageName 被嵌入的原图片路径
     * @param string $dstImageName 被嵌入后的图片路径
     * @param int $fontSize 嵌入字体大小
     * @param string $fontPath 嵌入字体路径
     * @param array $fontColor 字体颜色rgb
     * @param float $fontAlpha 字体颜色alpha
     * @return bool
     */
    public function embedText(string $text, int $textOffsetX, int $textOffsetY,
                              string $sourceImageName, string $dstImageName,
                              int $fontSize, string $fontPath,
                              array $fontColor = [255, 255, 255], float $fontAlpha = 0
    ): bool
    {
        if (!is_file($sourceImageName)) {
            return false;
        }
        $sourceImage = getimagesize($sourceImageName);
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
        $x = imagesx($dstImage);
        $y = imagesy($dstImage);

        $img = imagecreatetruecolor($x, $y);
        $alpha = imagecolorallocatealpha($img, 0, 0, 0, 127);
        imagefill($img, 0, 0, $alpha);
        imagecopyresampled($img, $dstImage, 0, 0, 0, 0, $x, $y, $x, $y);

        $fontColor = imagecolorclosestalpha($img, $fontColor[0], $fontColor[1], $fontColor[2], $fontAlpha);
        imagettftext($img, $fontSize, 0, $textOffsetX, $textOffsetY, $fontColor, $fontPath, $text);
        imagesavealpha($img, true);

        imagepng($img, $dstImageName);
        return true;
    }

    /**
     * @param $sourceImageName 原图片地址
     * @param $dst
     * @param $x1
     * @param $y1
     * @param $x2
     * @param $y2
     * @param array $fontColor
     * @return bool
     */
    /**
     * 向图片嵌入线条
     * @param string $sourceImageName 原图片地址
     * @param string $dst 嵌入图片后的地址
     * @param int $x1 线条直线起点x轴坐标
     * @param int $y1 线条直线起点y轴坐标
     * @param int $x2 线条直线终点x轴坐标
     * @param int $y2 线条直线终点y轴坐标
     * @param array $fontColor 线条颜色rgba
     * @return bool
     */
    public function imageLine(string $sourceImageName, string $dst, int $x1, int $y1,int $x2,int $y2, array $fontColor): bool
    {
        if (!is_file($sourceImageName)) {
            return false;
        }
        $sourceImage = getimagesize($sourceImageName);
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
        $x = imagesx($dstImage);
        $y = imagesy($dstImage);

        $img = imagecreatetruecolor($x, $y);
        $alpha = imagecolorallocatealpha($img, 0, 0, 0, 127);
        imagefill($img, 0, 0, $alpha);
        imagecopyresampled($img, $dstImage, 0, 0, 0, 0, $x, $y, $x, $y);

        $fontColor = imagecolorclosestalpha($img, $fontColor[0], $fontColor[1], $fontColor[2], $fontColor[3]);
        imageline($img, $x1, $y1, $x2, $y2, $fontColor);
        imagesavealpha($img, true);
        imagepng($img, $dst);
        return true;
    }
}
