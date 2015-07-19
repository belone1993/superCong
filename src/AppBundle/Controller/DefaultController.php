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
        return [
            'action' => 'homepage'
        ];
    }

    /**
     * @Route("/guestBook", name="guestBook")
     * @Template()
     */
    public function guestBookAction()
    {
        return [
            'action' => 'guestBook'
        ];
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

        $posts = $post->findPostsPage( 1, $page );

        $postTotal = $post->countPosts( 1 );

        return [
            'posts'     => $posts,
            'postTotal' => ceil( $postTotal / 10 ),
            'page'      => $page,
            'action'    => 'learn'
        ];
    }

    /**
     * @Route("/life/{page}", name="life", defaults={"page":1}, requirements={"page"="\d+"})
     * @Template()
     *
     * @param int $page
     * @return array
     */
    public function lifeAction( $page = 1 )
    {
        /** @var  $post \AppBundle\Entity\Repository\PostRepository */
        $post = $this->getDoctrine()->getRepository('AppBundle:Post');

        $posts = $post->findPostsPage( 2, $page );

        $postTotal = $post->countPosts( 2 );

        return [
            'posts'     => $posts,
            'postTotal' => ceil( $postTotal / 10 ),
            'page'      => $page,
            'action'    => 'life'
        ];
    }

    /**
     * @Route("/about", name="about")
     * @Template()
     */
    public function aboutAction()
    {
        return [
            'action' => 'about'
        ];
    }
}
