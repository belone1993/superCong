<?php

namespace AppBundle\Controller;

use StoreBundle\Entity\Category;
use StoreBundle\Entity\Post;
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
     * show reward page
     *
     * @Route("/reward", name="default_reward")
     * @Template()
     *
     * @param Request $request
     * @return array
     */
    public function rewardAction( Request $request )
    {

        $postId = $request->get('postId');

        $postInfo = null;

        if( $postId )
        {
            $em = $this->getDoctrine()->getManager();

            $postEntity = $em->getRepository('StoreBundle:Post');

            /** @var Post $postInfo */
            $postInfo = $postEntity->find($postId);
        }

        return [
            'action'   => 'reward',
            'postInfo' => $postInfo
        ];
    }

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
     * @Route("/home", name="index_page")
     * @Template()
     *
     * @return array
     */
    public function homeAction()
    {
        return [];
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
     * @Route("/learn/{page}/{category}", name="learn", defaults={"page":1, "category":""}, requirements={"page"="\d+"})
     * @Template()
     * @return array
     */
    public function learnAction( Request $request, $page )
    {
        /** @var  $post \StoreBundle\Entity\Repository\PostRepository */
        $post = $this->getDoctrine()->getRepository('StoreBundle:Post');

        $categoryId = null;
        if( $request->get('category') )
        {
            $category     = $this->getDoctrine()->getRepository('StoreBundle:Category');
            /** @var  $categoryInfo Category */
            $categoryInfo = $category->findOneBy(['categoryName' => $request->get('category')]);
            $categoryId = $categoryInfo->getId();
        }

        $posts = $post->findPostsPage( 1, $page, $categoryId );

        $postTotal = $post->countPosts( 1, $categoryId );

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
//        /** @var  $post \StoreBundle\Entity\Repository\PostRepository */
//        $post = $this->getDoctrine()->getRepository('StoreBundle:Post');
//
//        $posts = $post->findPostsPage( 2, $page );
//
//        $postTotal = $post->countPosts( 2 );
//
//        return [
//            'posts'     => $posts,
//            'postTotal' => ceil( $postTotal / 10 ),
//            'page'      => $page,
//            'action'    => 'life'
//        ];
        $this->redirectToRoute('life_grow', ['page' => $page]);
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
