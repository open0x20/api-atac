<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class YtvController extends AbstractController
{
    /**
     * @Route("/add", name="add")
     */
    public function addAction()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/YtvController.php',
        ]);
    }
}
