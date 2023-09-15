<?php

namespace App\Controller;

use DateTime;
use App\Entity\MicroPost;
use App\Repository\MicroPostRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MicroPostController extends AbstractController
{
    #[Route('/micro-post', name: 'app_micro_post')]
    public function index(MicroPostRepository $posts): Response
    {
        #dd($posts->findAll());
        return $this->render('micro_post/index.html.twig', [
            'posts' => $posts->findAll(),
        ]);
    }
    /*#[Route('/micro-post/{id}', name: 'app_micro_post_show')]
    public function showOne($id, MicroPostRepository $posts): Response
    {
        dd($posts->find($id));
    } This option produce more work*/ 

    #[Route('/micro-post/{post}', name: 'app_micro_post_show')]
    public function showOne(MicroPost $post): Response
    {
        #dd($post);
        return $this->render('micro_post/show.html.twig', [
            'post' => $post,
        ]);        
    }    
}