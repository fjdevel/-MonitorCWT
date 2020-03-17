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

class CWTController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
       
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
            'resp'=>""
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
        $res = json_decode($response->getBody()->getContents());
        if($res->{'error_code'}=="200"){
            $session = $request->getSession();;
            $session->start();
            $session->set('tid', $res->{'body'}->tid);
            return $this->redirectToRoute('monitor');
        }else{
            return $this->redirectToRoute('index');
        }
        
    }

    /**
     * @Route("/monitor", name="monitor")
     */
    public function Monitor(){
        return $this->render('cwt/cwtMonitor.html.twig', []);
    }
}
