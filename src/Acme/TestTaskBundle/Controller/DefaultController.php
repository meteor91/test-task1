<?php

namespace Acme\TestTaskBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Acme\TestTaskBundle\Form\PostType;
use Acme\TestTaskBundle\Entity\Post;

class DefaultController extends Controller
{
    /**
     * @Route("/fb-check", name="fb_check")
     * @Template()
     */   
    public function checkAction(Request $request) {
        $session = $this->get('session');
        $session->start();
        $fb_provider = $this->get('fb_provider');
        $code = $session->get('fb_token');
        if($code==null) {
            $code = $fb_provider->getToken();
            $session->set('fb_token', $code);
        }
        $data = $fb_provider->getUserData($code);
        return $this->render('AcmeTestTaskBundle:Default:index.html.twig', 
                array('data' =>$data , 'session' => $session));
    }
    
    /**
     * @Route("/login", name="fb_login")
     * @Template()
     */   
    public function loginAction() {
        
        $session = $this->get('session');
        $session->start();
        $url = $this->get('fb_provider')->getLoginUrl();
        return $this->render('AcmeTestTaskBundle:Default:login.html.twig', 
                array('link' => $url, 'session' => $session));
    }  
    
    /**
     * ajax acton
     * @Route("/add-post", name="add_post")
     * @Template()
     */   
    public function addpostAction(Request $request) {   
        $post = new Post();
        $form = $this->createForm(new PostType(), $post, array(
                            'action' => $this->generateUrl('add_post'),
                            'method' => 'POST'
                        ));
        $form->handleRequest($request);
        if($form->isValid()) {
            $user = $this->getUser();
            $post->setUser($user);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();  
            
            return  $this->redirect($this->generateUrl('index'));
        }
        return $this->render('AcmeTestTaskBundle:Default:add_post.html.twig', 
                array('form' =>$form->createView()));
    } 

    /**
     * @Route("/", name="index")
     * @Template()
     */   
    public function indexAction() { 
        $user = $this->getUser();
        $facebook_token = $user->getFacebookAccessToken();
        $userId = $this->getUser()->getId();
        $fb_provider = $this->get('fb_provider');

       
        
        $friends = $fb_provider->getFriendsData($facebook_token);       
        $posts = $this->getDoctrine()->getManager()->getRepository('AcmeTestTaskBundle:Post')->findAll();

        
        return $this->render('AcmeTestTaskBundle:Default:index1.html.twig', 
                array('posts' =>$posts, 
                      'userId' => $userId, 
                      'friends' => $friends,
                      'user' => $user));
    }
    
    /**
     * @Route("/editForm/{id}", name="edit_form")
     * @Template()
     */       
    public function getEditFormAction($id, Request $request) {
        $post = $this->getDoctrine()->getManager()
                        ->getRepository('AcmeTestTaskBundle:Post')
                        ->findOneBy(array('id'=>$id, 'user' => $this->getUser()->getId()));
        if($post == null) {
            return new Response("ERROR");
        }
        $form = $this->createForm(new PostType(), $post, array(
                'action' => $this->generateUrl('edit_form', array('id' => $id)),
                'method' => 'POST'));  
        
        $form->handleRequest($request);
        if($form->isValid()) {           
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
      
            $response = new JsonResponse();
            $response->setData(array(
                'text' => $post->getText(),
                'id' => $post->getId(),
                'lastChangedAt' => $post->getLastChangedAt()->format('H:i m-d-Y'),
                'user_id' => $post->getUser()->getId()
            )); 
            return $response;
        }
        return $this->render('AcmeTestTaskBundle:Ajax:post_form.html.twig', 
                        array('form' => $form->createView()));
    }
    
    
    /**
     * @Route("profile-edit", name="profile_edit")
     */
    public function profileEditAction(Request $request) {
        $user = $this->getUser();
        $form = $this->createFormBuilder($user)
            ->add('firstName', 'text')
            ->add('lastName', 'text')
            ->add('save', 'submit', array('label' => 'edit profile'))
            ->getForm();
        
        $form->handleRequest($request);
        
        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirect($this->generateUrl('index'));
        }

        return $this->render('AcmeTestTaskBundle:Default:profile_edit.html.twig', array(
            'form' => $form->createView(),
        ));        
    }
          
}
