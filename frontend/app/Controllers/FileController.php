<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class FileController extends Controller{ 
    
public function serve($filename)
{
    $path = ROOTPATH.'backend/uploads/organizations/'.$filename;
    if (!is_file($path)) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }
    return $this->response->download($path, null)->setFileName($filename);
}
}