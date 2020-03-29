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
use App\Entity\Device;
use App\Entity\InterfaceDevice;
use App\Entity\User;


class InterfaceController extends AbstractController
{
    /**
     * @Route("/newInterface", name="newInterface")
     */
    public function newInterface(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $deviceid = $request->request->get('deviceSelect');
        $typeid = $request->request->get('typeSelect');
        $channel = $request->request->get('channel');
        $name = $request->request->get('nameinterface');
        $interface = new InterfaceDevice();
        $interface->setName($name);
        $interface->setChannel($channel);
        $interface->setType($this->getDoctrine()->getRepository(Type::class)->find($typeid));
        $interface->setDevice($this->getDoctrine()->getRepository(Device::class)->find($deviceid));
        $em->persist($interface);
        $em->flush();
        return $this->redirectToRoute('device');
    }

    /**
     * @Route("/getAllInterface", name="getAllInterface")
     */
    public function getInterfaces(Request $request)
    {
        $session = $request->getSession();
        $tid = $session->get("tid");
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['tid'=>$tid]);
        $devices = $user->getDevices();
        $interfaces = array();
        foreach ($devices as $d) {
            foreach ($d->getInterfaces() as $in) {
                array_push($interfaces,$in);
            }
        }
        $jsonContent = $this->getSerializer()->serialize($interfaces,'json');
        $response = new Response();
        $response->setContent($jsonContent);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/getMyInterfaces", name="getMyInterfaces")
     */
    public function getMyInterface(Request $request)
    {
        $deviceid = $request->request->get('device');
        $device = $this->getDoctrine()->getRepository(Device::class)->find(["id"=>$deviceid]);
        $interfaces= array();
        foreach ($device->getInterfaces() as $in) {
                array_push($interfaces,$in);
        }
        $jsonContent = $this->getSerializer()->serialize($interfaces,'json');
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
