<?php
/**
 * Created by PhpStorm.
 * User: anthony-dev
 * Date: 04/10/16
 * Time: 15:46
 */

namespace BackBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadImage
{
    /** @var ContainerInterface $container */
    private $container = null;

    private $baseurl = 'http://localhost/';
    private $basefolder = '../web';
    private $folderurl = '/uploads/img/';


    /**
     * @var int
     */
    public $jpeg_quality = 100;

    public function __construct(ContainerInterface $container, $jpeg_quality = 80)
    {
        $this->container = $container;

        $this->baseurl = $this->container->getParameter('image_base_url');
        $this->basefolder = $this->container->getParameter('image_base_folder');
        $this->folderurl = $this->container->getParameter('image_folder_url');

    }


    /**
     * @param UploadedFile $file
     * @param $slug
     * @param string $imageType
     * @return null|string
     */
    public function upload(UploadedFile $file, $slug, $imageType = 'media')
    {
        $targetMove = $this->folderurl . $imageType . '/' . $slug . '/';

        @mkdir($this->basefolder . $targetMove, 0700, true);
        $fileName = $slug . '.jpg';

        $imageResource = null;
        switch (strtolower($file->guessExtension())) {
            case 'jpeg':
            case 'jpg':
            case 'jpe':
            case 'jfif':
            case 'jif':
            case 'jp2':
            case 'j2k':
            case 'jpf':
            case 'jpx':
            case 'jpm':
            case 'mj2':
                $imageResource = @imagecreatefromjpeg($file->getRealPath());
                break;
            case 'png':
                $imageResource = @imagecreatefrompng($file->getRealPath());
                break;
            case 'gif':
                $imageResource = @imagecreatefromgif($file->getRealPath());
                break;
            default:
                $img_str = @file_get_contents($file->getRealPath());
                if ($img_str)
                    $imageResource = @imagecreatefromstring(@base64_decode($img_str));
                break;
        }

        if (!$imageResource)
            return null;

        if (!imagejpeg($imageResource, $this->basefolder . $targetMove . $fileName, $this->jpeg_quality))
            return null;

        $resFileName = $this->baseurl . $targetMove . $fileName;

        return $resFileName;
    }


    public function uploadProfile(UploadedFile $file, $slug, $imageType = 'media')
    {
        $targetMove = $this->folderurl . $imageType . '/' . $slug . '/';

        @mkdir($this->basefolder . $targetMove, 0700, true);

        $fileName = $slug . '.jpg';

        $extension = $file->guessExtension();

        //--------------------- Background -------------------------
        $imageResource = null;
        switch (strtolower($extension)) {
            case 'jpeg':
            case 'jpg':
            case 'jpe':
            case 'jfif':
            case 'jif':
            case 'jp2':
            case 'j2k':
            case 'jpf':
            case 'jpx':
            case 'jpm':
            case 'mj2':
                $imageResource = @imagecreatefromjpeg($file->getRealPath());
                break;
            case 'png':
                $imageResource = @imagecreatefrompng($file->getRealPath());
                break;
            case 'gif':
                $imageResource = @imagecreatefromgif($file->getRealPath());
                break;
            default:
                $img_str = @file_get_contents($file->getRealPath());
                if ($img_str)
                    $imageResource = @imagecreatefromstring(@base64_decode($img_str));
                break;
        }

        if (!$imageResource)
            return null;

        //----------------- CREATION IMAGE THUMBNAIL -----------------
        $imgDimension = getimagesize($file->getRealPath());
        
        $imgWidth = 96;
        $ratio = $imgWidth / $imgDimension[0];
        $imgHeight = $imgDimension[1] * $ratio;

        if ($imgHeight > $imgWidth) {
            $imgHeight = 96;
            $ratio = $imgHeight / $imgDimension[1];
            $imgWidth = $imgDimension[0] * $ratio;
        }

        $img_thumbnail_destination = imagecreatetruecolor($imgWidth, $imgHeight);

        imagecopyresampled($img_thumbnail_destination, $imageResource, 0, 0, 0, 0, $imgWidth, $imgHeight, $imgDimension[0], $imgDimension[1]);

        if (!imagejpeg($img_thumbnail_destination, $this->basefolder . $targetMove . $fileName, 100))
            return null;

        //----------------- FIN CREATION IMAGE THUMBNAIL -------------
        $resFileName = $this->baseurl . $targetMove . $fileName;

        return $resFileName;
    }
    
    public function uploadAvatar(UploadedFile $file, $slug, $imageType = 'media')
    {
        $targetMove = $this->folderurl . $imageType . '/' . $slug . '/';

        @mkdir($this->basefolder . $targetMove, 0700, true);

        $fileName = "avatar-".$slug . '.jpg';

        $extension = $file->guessExtension();

        //--------------------- Background -------------------------
        $imageResource = null;
        switch (strtolower($extension)) {
            case 'jpeg':
            case 'jpg':
            case 'jpe':
            case 'jfif':
            case 'jif':
            case 'jp2':
            case 'j2k':
            case 'jpf':
            case 'jpx':
            case 'jpm':
            case 'mj2':
                $imageResource = @imagecreatefromjpeg($file->getRealPath());
                break;
            case 'png':
                $imageResource = @imagecreatefrompng($file->getRealPath());
                break;
            case 'gif':
                $imageResource = @imagecreatefromgif($file->getRealPath());
                break;
            default:
                $img_str = @file_get_contents($file->getRealPath());
                if ($img_str)
                    $imageResource = @imagecreatefromstring(@base64_decode($img_str));
                break;
        }

        if (!$imageResource)
            return null;

        //----------------- CREATION IMAGE THUMBNAIL -----------------
        $imgDimension = getimagesize($file->getRealPath());
        
        $imgWidth = 50;
        $ratio = $imgWidth / $imgDimension[0];
        $imgHeight = $imgDimension[1] * $ratio;

        if ($imgHeight > $imgWidth) {
            $imgHeight = 50;
            $ratio = $imgHeight / $imgDimension[1];
            $imgWidth = $imgDimension[0] * $ratio;
        }

        $img_thumbnail_destination = imagecreatetruecolor($imgWidth, $imgHeight);

        imagecopyresampled($img_thumbnail_destination, $imageResource, 0, 0, 0, 0, $imgWidth, $imgHeight, $imgDimension[0], $imgDimension[1]);

        if (!imagejpeg($img_thumbnail_destination, $this->basefolder . $targetMove . $fileName, 100))
            return null;

        //----------------- FIN CREATION IMAGE THUMBNAIL -------------
        $resFileName = $this->baseurl . $targetMove . $fileName;

        return $resFileName;
    }
    
    


    public function uploadImage(UploadedFile $file, $slug, $imageType = 'media')
    {
        $targetMove = $this->folderurl . $imageType . '/' . $slug . '/';

        @mkdir($this->basefolder . $targetMove, 0700, true);

        $fileName = $slug . '.' . $file->guessExtension();
        $file->move($this->basefolder . $targetMove, $fileName);
        $resFileName = $this->baseurl . $targetMove . $fileName;

        return $resFileName;
    }



//    /**
//     * @param UploadedFile $file
//     * @return bool
//     */
//    public function verifySize(UploadedFile $file)
//    {
//        $image_info = getimagesize($file->getRealPath());
//        if ($image_info[0] != $this->width || $image_info[1] != $this->height)
//            return false;
//
//        return true;
//    }

}