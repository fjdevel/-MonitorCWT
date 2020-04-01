<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpClient\HttpClient;
use GuzzleHttp\Client;
    use Symfony\Component\HttpFoundation\Session\Session;
    use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Exception\RequestException;

class CWTController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(Request $param)
    {
        $dclass = $param->query->get('displayClass','d-none');
        $alert = $param->query->get('alert','');

        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('login'))
            ->setMethod('POST')
            ->add("Username",TextType::class)
            ->add("Password",PasswordType::class)
            ->add("Login",SubmitType::class,[
                'attr'=>['class' => 'btn btn-success']
            ])
            ->getForm();
        return $this->render('cwt/index.html.twig', [
            'form' =>$form->createView(),
            'displayClass'=>$dclass,
            'alert'=> $alert
        ]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function Login(Request $request){
        $pwd = $request->get('form')['Password'];
        $pwd = $pwd."e80b5017098950fc58aad83c8c14978e";
        $pwd = md5($pwd);
        $user = $request->get('form')['Username'];
        
        $client = new Client(['base_uri' => 'http://www.all-m2m.com:8081/','timeout'  => 10.0,'headers' => [ 'Content-Type' => 'application/json' ]]);
        try{
            $response = $client->post("member",[
                'body'=> json_encode(
                    [
                        "action_cmd"=>"login",
                        "version"=>"1.0",
                        "body"=>[
                            "pwd"=>$pwd,
                            "username"=>$user,
                            "login_from"=>"web"
                        ],
    
                    ]
                )
            ]);
            if($response)
                $res = json_decode($response->getBody()->getContents());
            if($res->{'error_code'}=="200"){
                $session = $request->getSession();
                $session->set('tid', $res->{'body'}->tid);
                return $this->redirectToRoute('monitor');
            }else{
                return $this->redirectToRoute('index',[
                    'displayClass'=>'',
                    'alert'=>'Authentication error!, please check your credentials'
                ]);
            }
        }catch(RequestException $e){
            return $this->redirectToRoute('index',[
                'displayClass'=>'',
                'alert'=>'The server not response!, please check your connection'
            ]);
        }
    }

    /**
     * @Route("/monitor", name="monitor")
     */
    public function Monitor(Request $request){
        //$data = $this->obtenerRecursos($request);
        return $this->render('cwt/cwtMonitor.html.twig', [
            //'res'=>$data,
            'displayClass'=>'d-none',
            'alert'=>''
        ]);
    }
    
    public function obtenerRecursos(Request $request){
        $session = $request->getSession();
        $client = new Client(['base_uri' => 'http://www.all-m2m.com:8081/','headers' => [ 'Content-Type' => 'application/json' ]]);
        try{
            $response = $client->post("query",[
                'body'=> json_encode(
                    [
                        "action_cmd"=>"query_device_currentdata2",
                        "seq_id"=>"1",
                        "body"=>[
                            "deviceid"=>"elx00001",
                            "tid"=>$session->get('tid'),
                        ],
                        "version"=>"1.0"
                    ]
                )
            ]);
            $res = json_decode($response->getBody()->getContents())->{'body'}->datadict;
            $data = ["data"=>$res->RG2->value,
                    "label"=>$res->RG2->recv_time];
            return $data;
        }catch(RequestException $e){
            return $this->render('cwt/cwtMonitor.html.twig', [
                //'res'=>'',
                'displayClass'=>'',
                'alert'=>'The server not response!, please check your connection'
            ]);
        }
    }
    /**
     * @Route("/getData", name="getData")
     */
    public function getData(Request $request){
        $data = $this->obtenerRecursos($request);
        $response = new Response();
        $response->setContent(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
