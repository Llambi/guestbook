<?php


namespace App;


use Imagine\Gd\Imagine;
use Imagine\Image\Box;

class ImageOptimizer
{
    private const MAX_WIDTH = 200;
    private const MAX_HEIGHT = 150;

    private $imagine;

    /**
     * ImageOptimizer constructor.
     * @param $image
     */
    public function __construct()
    {
        $this->imagine = new Imagine();
    }

    public function resize(string $fileName): void
    {
        [$iwidth, $iheight] = getimagesize($fileName);
        $ratio = $iwidth / $iheight;
        $width = self::MAX_WIDTH;
        $height = self::MAX_HEIGHT;
        if ($width / $height > $ratio) {
            $width = $height * $ratio;
        } else {
            $height = $width / $ratio;
        }
        $photo = $this->imagine->open($fileName);
        $photo->resize(new Box($width, $height))->save($fileName);
    }

}