<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Type;

class TypeController extends AbstractController
{
    /**
     * @Route("/newType", name="newType")
     */
    function newType(Request $request){
        $name = $request->request->get('nameType');
        $em = $this->getDoctrine()->getManager();
        $type = new Type();
        $type->setName($name);
        $em->persist($type);
        $em->flush();
        return $this->redirectToRoute('device');
    }
    /**
     * @Route("/getTypes", name="getTypes")
     */
    function getTypes(Request $request){
        $types = $this->getDoctrine()->getRepository(Type::class)->findAll();
        $jsonContent = $this->getSerializer()->serialize($types,'json');
        $response = new Response();
        $response->setContent($jsonContent);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function getSerializer(){
        $encoder = new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getId();
            },
        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);

        return $serializer = new Serializer([$normalizer], [$encoder]);
    }
}
