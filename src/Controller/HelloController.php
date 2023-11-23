<?php 
namespace App\Controller;

use App\Entity\Comment;
use App\Entity\MicroPost;
use App\Entity\User;
use App\Entity\UserProfile;
use App\Repository\CommentRepository;
use App\Repository\MicroPostRepository;
use App\Repository\UserProfileRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HelloController extends AbstractController

{
    private array $messages = ["Hello","Hi","Bye!"];

    #[Route('/hello',name: 'app_index')]
    public function index(MicroPostRepository $posts): Response
    {
        // $post = new MicroPost();
        // $post->setTitle('Hello1');
        // $post->setText('Hello1');
        // $post->setCreation(new DateTime());

        $post = $posts->find(6);
        $comment = $post->getComments()[0];
        $post->removeComment($comment);

        $posts->add($post,true);
        // $comment = new Comment();
        // $comment->setText('Hello');

        // $post->addComment($comment);
        // $posts->add($post, true);
        
        // $user = new User();
        // $user->setEmail('email@email.com');
        // $user->setPassword('12345678'); 

        // $profile = new UserProfile();
        // $profile->setUser($user);
        // $profiles->add($profile,true); 
        // $profile = $profiles->find(1);
        // $profiles->remove($profile,true);

        return $this->render(
            'hello/show_one.html.twig',
            [
                'message'=>$this->messages[0]
            ]
        );
    }

    
}
    
?> 