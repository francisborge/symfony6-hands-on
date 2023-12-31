<?php

namespace App\Controller;

use App\Entity\Comment;
use DateTime;
use App\Entity\MicroPost;
use App\Form\CommentType;
use App\Form\MicroPostType;
use App\Repository\CommentRepository;
use App\Repository\MicroPostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MicroPostController extends AbstractController
{
    #[Route('/micro-post', name: 'app_micro_post')]
    public function index(MicroPostRepository $posts): Response
    {
        #dd($posts->findAll());
        return $this->render('micro_post/index.html.twig', [
            'posts' => $posts->findAllWithComments(),
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
            'post' => $post
        ]);        
    }

    #[Route('/micro-post/add', name: 'app_micro_post_add', priority: 2)]
    public function add(Request $request, MicroPostRepository $posts): Response
    {
        $form = $this->createForm(MicroPostType::class, new MicroPost());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $post->setCreated(new DateTime());
            $posts->add($post, true);
            #dd($post);
            // Add a flash
            $this->addFlash('success', 'Your micro post has been added.');

            return $this->redirectToRoute('app_micro_post'); //you can use an specific path with redirect(/micro-post/1/comments)
            // Redirect
        }

        return $this->renderForm('micro_post/add.html.twig', 
            [
                'form' => $form
            ]
        );
    }

    #[Route('/micro-post/{post}/edit', name: 'app_micro_post_edit')]
    public function edit(MicroPost $post, Request $request, MicroPostRepository $posts): Response
    {
        $form = $this->createForm(MicroPostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $posts->add($post, true);
            #dd($post);
            // Add a flash
            $this->addFlash('success', 'Your micro post has been updated.');

            return $this->redirectToRoute('app_micro_post'); //you can use an specific path with redirect(/micro-post/1/comments)
            // Redirect
        }

        return $this->renderForm('micro_post/edit.html.twig', 
            [
                'form' => $form,
                'post' => $post
            ]
        );
    }

    #[Route('/micro-post/{post}/comment', name: 'app_micro_post_comment')]
    public function addComment(MicroPost $post, Request $request, CommentRepository $comments): Response
    {
        $form = $this->createForm(CommentType::class, new Comment());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $comment->setPost($post);
            $comments->add($comment, true);

            #dd($post);
            // Add a flash
            $this->addFlash('success', 'Your comment has been updated.');

            return $this->redirectToRoute(
                'app_micro_post_show',
                ['post' => $post->getId()]
            ); //you can use an specific path with redirect(/micro-post/1/comments)
            // Redirect
        }

        return $this->renderForm('micro_post/comment.html.twig', 
            [
                'form' => $form,
                'post' => $post
            ]
        );        
    }
}