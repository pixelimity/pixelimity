<?php
/**
 * Class For resize Image mimetype
 *
 * @package pixelimity
 */
Class resize
{
    /**
     * @param string $image of image layer from create image
     */
    private $image;

    /**
     * @param integer $width width of image
     */
    private $width;

    /**
     * @param integer $heighy height of image
     */
    private $height;

    /**
     * @param string image resized / final images
     */
    private $imageResized;

    /**
     * PHP5 Constructor
     * @param  string $filename file of image
     * @return void
     */
    public function __construct($fileName)
    {
        /* Open up the file
        --------------------------------------------- */
        $this->image = $this->openImage($fileName);

        /* Get width and height
        --------------------------------------------- */
        $this->width  = imagesx($this->image);
        $this->height = imagesy($this->image);
    }

    /**
     * OpenImage function create new image layer
     *
     * @access private
     * @param  string $file image file
     * @return string $img
     */
    private function openImage($file)
    {
        /* Get extension
         --------------------------------------------- */
        $extension = strtolower(strrchr($file, '.'));

        switch ($extension) {
            case '.jpg':
            case '.jpeg':
                $img = @imagecreatefromjpeg($file);
                break;
            case '.gif':
                $img = @imagecreatefromgif($file);
                break;
            case '.png':
                $img = @imagecreatefrompng($file);
                break;
            default:
                $img = false;
                break;
        }

        return $img;
    }

    /**
     * Resize the image into new size
     *
     * @param  integer $newWidth  new width of image
     * @param  integer $newHeight new height of image
     * @param string option auto|crop|exact|potrait|lansdscape
     * @see getDimension()
     * @return void
     */
    public function resizeImage($newWidth, $newHeight = '', $option="auto")
    {

        /* Get optimal width and height - based on $option
        --------------------------------------------- */
        $optionArray = $this->getDimensions($newWidth, $newHeight, strtolower($option));

        $optimalWidth  = $optionArray['optimalWidth'];
        $optimalHeight = $optionArray['optimalHeight'];

        /* Resample - create image canvas of x, y size
        --------------------------------------------- */
        $this->imageResized = imagecreatetruecolor($optimalWidth, $optimalHeight);
        imagecopyresampled($this->imageResized, $this->image, 0, 0, 0, 0, $optimalWidth, $optimalHeight, $this->width, $this->height);

        /* if option is 'crop', then crop too
        --------------------------------------------- */
        if ($option == 'crop') {
            $this->crop($optimalWidth, $optimalHeight, $newWidth, $newHeight);
        }
    }

    /**
     * Get image dimension
     *
     * @access private
     * @param  integer $newWidth  new width of image
     * @param  integer $newHeight new height of image
     * @return array
     */
    private function getDimensions($newWidth, $newHeight = '', $option)
    {

       switch ($option) {
            case 'exact':
                $optimalWidth = $newWidth;
                $optimalHeight= $newHeight;
                break;
            case 'portrait':
                $optimalWidth = $this->getSizeByFixedHeight($newHeight);
                $optimalHeight= $newHeight;
                break;
            case 'landscape':
                $optimalWidth = $newWidth;
                $optimalHeight= $this->getSizeByFixedWidth($newWidth);
                break;
            case 'auto':
                $optionArray = $this->getSizeByAuto($newWidth);
                $optimalWidth = $optionArray['optimalWidth'];
                $optimalHeight = $optionArray['optimalHeight'];
                break;
            case 'crop':
                $optionArray = $this->getOptimalCrop($newWidth, $newHeight);
                $optimalWidth = $optionArray['optimalWidth'];
                $optimalHeight = $optionArray['optimalHeight'];
                break;
        }

        return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
    }

    /**
     * Get image Width
     *
     * @access private
     * @param  integer $newWidth new width of image
     * @return integer $newWidth
     */
    private function getSizeByFixedHeight($newHeight)
    {
        $ratio = $this->width / $this->height;
        $newWidth = $newHeight * $ratio;

        return $newWidth;
    }

    /**
     * Get image Height
     *
     * @access private
     * @param  integer $newHeight new height of image
     * @return integer $newHeight
     */
    private function getSizeByFixedWidth($newWidth)
    {
        $ratio = $this->height / $this->width;
        $newHeight = $newWidth * $ratio;

        return $newHeight;
    }

    /**
     * Get auto image width and height
     *
     * @access private
     * @param  integer $newWidth new width of image
     * @return array
     */
    private function getSizeByAuto($newWidth)
    {
        $optimalWidth = $newWidth;
        $optimalHeight = $this->getSizeByFixedWidth($newWidth);

        return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
    }

    /**
     * Get image optimal size dimension for crop
     *
     * @access private
     * @param  integer $newWidth  new width of image
     * @param  integer $newHeight new height of image
     * @return array
     */
    private function getOptimalCrop($newWidth, $newHeight)
    {

        $heightRatio = $this->height / $newHeight;
        $widthRatio  = $this->width / $newWidth;

        if ($heightRatio < $widthRatio) {
            $optimalRatio = $heightRatio;
        } else {
            $optimalRatio = $widthRatio;
        }

        $optimalHeight = $this->height / $optimalRatio;
        $optimalWidth  = $this->width  / $optimalRatio;

        return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
    }

    /**
     * Get image optimal size dimension for crop
     *
     * @access private
     * @param  integer $newWidth      new width of image
     * @param  integer $newHeight     new height of image
     * @param  integer $optimalWidth  for size optimal width of image size
     * @param  integer $optimalHeight for size optimal height of image size
     * @return array
     */
    private function crop($optimalWidth, $optimalHeight, $newWidth, $newHeight)
    {
        /* Find center - this will be used for the crop
        --------------------------------------------- */
        $cropStartX = ( $optimalWidth / 2) - ( $newWidth /2 );
        $cropStartY = ( $optimalHeight/ 2) - ( $newHeight/2 );

        $crop = $this->imageResized;
        //imagedestroy($this->imageResized);

        /* Now crop from center to exact requested size
        --------------------------------------------- */
        $this->imageResized = imagecreatetruecolor($newWidth , $newHeight);
        imagecopyresampled($this->imageResized, $crop , 0, 0, $cropStartX, $cropStartY, $newWidth, $newHeight , $newWidth, $newHeight);
    }

    /**
     * Save image on certain location
     *
     * @param  string $savePath direcory destination of image
     * @param integer quality between 0 -100
     * @return void
     */
    public function saveImage($savePath, $imageQuality="100")
    {
        /* Get extension
        --------------------------------------------- */
        $extension = strrchr($savePath, '.');
        $extension = strtolower($extension);

        switch ($extension) {
            case '.jpg':
            case '.jpeg':
                if (imagetypes() & IMG_JPG) {
                    imagejpeg($this->imageResized, $savePath, $imageQuality);
                }
                break;

            case '.gif':
                if (imagetypes() & IMG_GIF) {
                    imagegif($this->imageResized, $savePath);
                }
                break;

            case '.png':

                /* Scale quality from 0-100 to 0-9
                --------------------------------------------- */
                $scaleQuality = round(($imageQuality/100) * 9);

                /* Invert quality setting as 0 is best, not 9
                --------------------------------------------- */
                $invertScaleQuality = 9 - $scaleQuality;

                if (imagetypes() & IMG_PNG) {
                    imagepng($this->imageResized, $savePath, $invertScaleQuality);
                }
                break;

            default:

                /* No extension - No save.
                --------------------------------------------- */
                break;
        }

        imagedestroy($this->imageResized);
    }
}
