<?php

namespace App\Service;

class VideoService
{
    public function verifyPost($iframe): bool
    {
       if (!empty($iframe) &&preg_match('#^<iframe.*</iframe>$#', $iframe) && preg_match('#^<iframe.*src="https://.*".*</iframe>$#', $iframe)) {
           return true;
       }
       return false;
    }
}