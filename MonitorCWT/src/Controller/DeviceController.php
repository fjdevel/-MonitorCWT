<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Entity\Device;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

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
    /**
     * @Route("/fndDevices", name="findDevices")
     */
    public function findDevices(Request $request){
        $session = $request->getSession();
        $tid = $session->get("tid");
        $client = new Client(['base_uri' => 'http://www.all-m2m.com:8081/','timeout'  => 10.0,'headers' => [ 'Content-Type' => 'application/json' ]]);
        $response = $client->post("query",[
            'body'=> json_encode(
                [
                    "action_cmd"=>"member_query_gearlist",
                    "version"=>"1.0",
                    "body"=>[
                        "vid"=>"",
                        "tid"=>$tid,
                    ],
                    "seq_id"=>"0",
                ]
            )
        ]);
        if($response)
            $res = json_decode($response->getBody()->getContents());
            if($res->{'error_code'}=="200"){
                $res = $res->{'body'}->gearinfo;
                $devices = array();
                foreach($res as $dev){
                    array_push($devices,$dev->deviceid);
                }
                $response = new Response();
                $response->setContent(json_encode($devices));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }else{
                
            }
        
    }

    /**
     * @Route("/newDevice", name="newDevice")
     */
    public function newDevice(Request $request){
        $session = $request->getSession();
        $tid = $session->get("tid");
        $deviceid = $request->request->get('deviceSelect');
        $em = $this->getDoctrine()->getManager();
        $UserRepo = $this->getDoctrine()->getRepository(User::class);
        $user = $UserRepo->findOneBy(['tid'=>$tid]);
        $device = new Device();
        $device->setDeviceId($deviceid);
        $device->setUser($user);
        $em->persist($device);
        $em->flush();
        return $this->redirectToRoute('device');
    }

    /**
     * @Route("/getDevices", name="myDevices")
     */
    public function getDevices(Request $request){
       
        
        $session = $request->getSession();
        $tid = $session->get("tid");
        $UserRepo = $this->getDoctrine()->getRepository(User::class);
        $user = $UserRepo->findOneBy(['tid'=>$tid]);
        $devices = $user->getDevices();
        $jsonContent = $this->getSerializer()->serialize($devices,'json');
        $response = new Response();
        $response->setContent($jsonContent);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    /**
     * @Route("/admin/views", name="adminviews")
     */
    public function adminViews(Request $request){
        switch($request->request->get('view')){
            case 1:
                return $this->render('device/interfaces.html.twig',[]);
                break;
            case 2:
                return $this->render('device/types.html.twig',[]);
            break;
        }
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
