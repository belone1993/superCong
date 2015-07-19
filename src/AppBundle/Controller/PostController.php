<?php
/**
 * Created by PhpStorm.
 * User: LatteCake
 * Date: 15/7/15
 * Time: 上午12:04
 */

namespace AppBundle\Controller;
use AppBundle\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PostController
 *
 * @Route("/post")
 * @package AppBundle\Controller
 */
class PostController extends Controller
{
    /**
     * @Route("/postInfo/{id}", name="post_postInfo", requirements={"id"="\d+"})
     * @Template()
     *
     * @param int $id
     * @return array
     */
    public function postInfoAction( $id )
    {
        if (!$id) {
            throw $this->createNotFoundException('No product found for id '.$id);
        }

        /** @var  $post \AppBundle\Entity\Post */
        $post = $this->getDoctrine()->getRepository('AppBundle:Post');

        $postInfo = $post->findPostByOldId( $id, 1 );

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $parseDown = new \Parsedown();

        return [
            'parseDown' => $parseDown,
            'post'      => $postInfo,
            'action'    => 'learn'
        ];
    }

    /**
     *
     * @param Request $request
     *
     * @Template()
     * @return array
     */
    public function topPostAction( Request $request )
    {
        $category = $request->get('action');
        $id       = $request->get('id');
        /** @var  $post \AppBundle\Entity\Repository\PostRepository */
        $post = $this->getDoctrine()->getRepository('AppBundle:Post');

        $topPosts = $post->findPostsTop( $category, $id );

        return [
            'topPosts' => $topPosts
        ];
    }

    /**
     * @Template()
     * @return array
     */
    public function latestPostsAction()
    {
        /** @var  $post \AppBundle\Entity\Repository\PostRepository */
        $post = $this->getDoctrine()->getRepository('AppBundle:Post');

        $posts = $post->findPostsLate();
        return [
            'posts' => $posts
        ];
    }
}