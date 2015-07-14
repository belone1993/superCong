<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DefaultController
 *
 * @Route("/")
 * @package AppBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction()
    {
        return [];
    }

    /**
     * @Route("/guestBook", name="guestBook")
     * @Template()
     */
    public function guestBookAction()
    {
        return [];
    }

    /**
     * @param Request $request
     * @param integer $page
     *
     * @Route("/learn/{page}", name="learn", defaults={"page":1}, requirements={"page"="\d+"})
     * @Template()
     * @return array
     */
    public function learnAction( Request $request, $page )
    {
        /** @var  $post \AppBundle\Entity\Repository\PostRepository */
        $post = $this->getDoctrine()->getRepository('AppBundle:Post');

        $posts = $post->findPostsPage( $page );

        $postTotal = $post->countPosts();

        return [
            'posts'     => $posts,
            'postTotal' => ceil( $postTotal / 10 ),
            'page'      => $page
        ];
    }

    /**
     * @Route("/life", name="life")
     * @Template()
     */
    public function lifeAction()
    {
        return [];
    }

    /**
     * @Route("/about", name="about")
     * @Template()
     */
    public function aboutAction()
    {
        return [];
    }
}
