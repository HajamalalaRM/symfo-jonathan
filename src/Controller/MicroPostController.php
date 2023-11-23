<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\MicroPost;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\MicroPostRepository;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MicroPostController extends AbstractController
{
    #[Route('/', name: 'app_micro_post')]
    public function index(MicroPostRepository $posts): Response
    {

        return $this->render('micro_post/index.html.twig', [
            'posts' => $posts->findAllWithComments(),
        ]);
    }

    #[Route('/micro-post/{post}',name: 'app_micro_post_show')]
    public function showOne(MicroPost $post): Response{
        return $this->render(
            'micro_post/show.html.twig',
            [
                'post' => $post,
            ]
        );
    }

    #[Route('/micro-post/ajout', name:'app_micro_post_add')]
    public function add(Request $request, MicroPostRepository $posts):Response
    {
        $microPost = new MicroPost();
        $form = $this->createFormBuilder($microPost) 
            ->add('title')
            ->add('text')            
            ->getForm();

        $form->handleRequest($request);        
        if ($form->isSubmitted() && $form->isValid()) { 
            $id = $form->getData();
            $id->setCreation(new DateTime());
            $posts->add($id, true);

            $this->addFlash('success','Your micro-post have been added!');

            return $this->redirectToRoute('app_micro_post');
        }

        return $this->renderForm(
            'micro_post/add.html.twig',
            [
                'form' => $form
            ]
        );

    }

    #[Route('/micro-post/edit/{post}', name:'app_micro_post_edit')]
    public function edit(MicroPost $id,Request $request, MicroPostRepository $posts):Response
    {
        $microPost = new MicroPost();
        $form = $this->createFormBuilder($id) 
            ->add('title')
            ->add('text')            
            ->getForm();

        $form->handleRequest($request);        
        if ($form->isSubmitted() && $form->isValid()) { 
            $id = $form->getData();
            $posts->add($id, true);

            $this->addFlash('success','Your micro-post have been updated!');

            return $this->redirectToRoute('app_micro_post');
        }

        return $this->renderForm(
            'micro_post/edit.html.twig',
            [
            'form' => $form,
            'post' => $id
            ]
        );

    }

    #[Route('/micro-post/comment/{post}', name:'app_micro_post_comment')]
    public function addComment(MicroPost $post,Request $request, CommentRepository $comments):Response
    {
        $form = $this->createForm(CommentType::class,new Comment());
        $form->handleRequest($request);  

        if ($form->isSubmitted() && $form->isValid()) { 
            $comment = $form->getData();
            $comment->setPost($post);
            $comments->add($comment, true);

            $this->addFlash('success','Your comment have been updated!');

            return $this->redirectToRoute(
                'app_micro_post_show',
                ['post' => $post->getId()]
            );
        }

        return $this->renderForm(
            'micro_post/comment.html.twig',
            [
                'form' => $form,
                'post' => $post
            ]
        );

    }

   
}
