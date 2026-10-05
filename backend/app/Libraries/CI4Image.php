<?php

namespace App\Libraries;

class CI4Image
{
    protected $filePath;
    protected $imageResource = null;
    protected $imageType = null;

    public function withFile(string $filePath)
    {
        $this->filePath = $filePath;
        if (file_exists($filePath)) {
            $info = @getimagesize($filePath);
            if ($info) {
                $this->imageType = $info[2];
                switch ($this->imageType) {
                    case IMAGETYPE_JPEG:
                        $this->imageResource = @imagecreatefromjpeg($filePath);
                        break;
                    case IMAGETYPE_PNG:
                        $this->imageResource = @imagecreatefrompng($filePath);
                        break;
                    case IMAGETYPE_GIF:
                        $this->imageResource = @imagecreatefromgif($filePath);
                        break;
                    case IMAGETYPE_WEBP:
                        $this->imageResource = @imagecreatefromwebp($filePath);
                        break;
                }
            }
        }
        return $this;
    }

    public function fit(int $width, int $height, string $position = 'center')
    {
        if ($this->imageResource) {
            $srcW = imagesx($this->imageResource);
            $srcH = imagesy($this->imageResource);

            $dst = imagecreatetruecolor($width, $height);
            if ($this->imageType === IMAGETYPE_PNG) {
                imagealphablending($dst, false);
                imagesavealpha($dst, true);
            }

            imagecopyresampled($dst, $this->imageResource, 0, 0, 0, 0, $width, $height, $srcW, $srcH);
            if (is_resource($this->imageResource) || $this->imageResource instanceof \GdImage) {
                imagedestroy($this->imageResource);
            }
            $this->imageResource = $dst;
        }
        return $this;
    }

    public function resize(int $width, int $height, bool $maintainRatio = true)
    {
        return $this->fit($width, $height);
    }

    public function save(string $targetPath, int $quality = 90)
    {
        $dir = dirname($targetPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        if ($this->imageResource) {
            $ext = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));
            switch ($ext) {
                case 'png':
                    imagepng($this->imageResource, $targetPath);
                    break;
                case 'gif':
                    imagegif($this->imageResource, $targetPath);
                    break;
                case 'webp':
                    imagewebp($this->imageResource, $targetPath, $quality);
                    break;
                default:
                    imagejpeg($this->imageResource, $targetPath, $quality);
                    break;
            }
            return true;
        }

        if ($this->filePath && file_exists($this->filePath) && $this->filePath !== $targetPath) {
            return copy($this->filePath, $targetPath);
        }
        return false;
    }
}
