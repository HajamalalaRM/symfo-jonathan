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
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
    #[IsGranted(MicroPost::VIEW, 'post')]
    public function showOne(MicroPost $post): Response{
        return $this->render(
            'micro_post/show.html.twig',
            [
                'post' => $post,
            ]
        );
    }

    #[Route('/ajout/micro-post', name:'app_micro_post_add')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function add(Request $request, MicroPostRepository $posts):Response
    {
        // $this->denyAccessUnlessGranted(
        //     'IS_AUTHENTICATED_FULLY'
        // );
        $microPost = new MicroPost();
        $form = $this->createFormBuilder($microPost) 
            ->add('title')
            ->add('text')            
            ->getForm();

        $form->handleRequest($request);        
        if ($form->isSubmitted() && $form->isValid()) { 
            $post = $form->getData();
            $post->setCreation(new DateTime());
            $post->setAuthor($this->getUser());
            $posts->add($post, true);

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
    #[IsGranted(MicroPost::EDIT, 'post')]
    public function edit(MicroPost $post,Request $request, MicroPostRepository $posts):Response
    {
        // $microPost = new MicroPost();
        $form = $this->createFormBuilder($post) 
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
            'post' => $post
            ]
        );

    }

    #[Route('/micro-post/comment/{post}', name:'app_micro_post_comment')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function addComment(MicroPost $post,Request $request, CommentRepository $comments):Response
    {
        $form = $this->createForm(CommentType::class,new Comment());
        $form->handleRequest($request);  

        if ($form->isSubmitted() && $form->isValid()) { 
            $comment = $form->getData();
            $comment->setPost($post);
            $comment->setCreation(new DateTime());
            $comment->setAuthor($this->getUser());
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
