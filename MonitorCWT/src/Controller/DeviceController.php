<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DeviceController extends AbstractController
{
    /**
     * @Route("/admin", name="device")
     */
    public function index()
    {
        return $this->render('device/administration.html.twig', [
        ]);
    }
}
