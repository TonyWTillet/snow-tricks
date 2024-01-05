<?php

namespace App\Service;

use Exception;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PictureService
{
    private $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    /**
     * @throws Exception
     */
    public function add(UploadedFile $picture, ?string $folder = '', ?int $width = 250, ?int $height = 250): string
    {
        $file = md5(uniqid(rand(), true)) . '.webp';
        $pictureInfos = getimagesize($picture);
        if ($pictureInfos === false) {
            throw new Exception('Le fichier n\'est pas une image');
        }

        switch ($pictureInfos['mime']) {
            case 'image/png':
                $pictureSource = imagecreatefrompng($picture);
            break;
            case 'image/jpeg':
                $pictureSource = imagecreatefromjpeg($picture);
            break;
            case 'image/webp':
                $pictureSource = imagecreatefromwebp($picture);
            break;
            default:
                throw new Exception('Le fichier n\'est pas une image');
        }

        $imageWidth = $pictureInfos[0];
        $imageHeight = $pictureInfos[1];

        switch ($imageWidth <=> $imageHeight) {
            case -1: // portrait largeur < hauteur
                $squareSize = $imageWidth;
                $src_x = 0;
                $src_y = ($imageHeight - $squareSize) / 2;
            break;
            case 0: // carrer largeur = hauteur
                $squareSize = $imageWidth;
                $src_x = 0;
                $src_y = 0;
            break;
            case 1: // paysage largeur > hauteur
                $squareSize = $imageHeight;
                $src_y = 0;
                $src_x = ($imageWidth - $squareSize) / 2;
            break;
        }

        $resizedPicture = imagecreatetruecolor($width, $height);
        imagecopyresampled($resizedPicture, $pictureSource, 0, 0, $src_x, $src_y, $width, $height, $squareSize, $squareSize);

        $path = $this->parameterBag->get('images_directory') . $folder;
        if (!is_dir($path . '/mini/')) {
            mkdir($path . '/mini/', 0755, true);
        }

        imagewebp($resizedPicture, $path . '/mini/' . $width . 'x' . $height . 'y' . '-' .$file);

        $picture->move($path . '/', $file);

        return $file;
    }

    public function delete(string $file, ?string $folder = ''): bool
    {
        if ($file !== 'default.webp') {
            $success = false;
            $path = $this->parameterBag->get('images_directory') . $folder;
            $mini = $path . '/mini/' . $width . 'x' . $height . 'y' . '-' . $file;
        }

        if (file_exists($mini)) {
            $success = unlink($mini);
        }

        $original = $path . '/' . $file;
        if (file_exists($original)) {
            $success = unlink($original);
        }
        return $success;
    }
}