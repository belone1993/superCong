<?php
/**
 * Created by PhpStorm.
 * User: LatteCake
 * Date: 15/7/15
 * Time: 上午12:04
 */

namespace AppBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class PostController
 *
 * @Route("/post")
 * @package AppBundle\Controller
 */
class PostController extends Controller
{
    /**
     *
     * @Template()
     * @return array
     */
    public function topPostAction()
    {
        /** @var  $post \AppBundle\Entity\Repository\PostRepository */
        $post = $this->getDoctrine()->getRepository('AppBundle:Post');

        $topPosts = $post->findPostsTop();

        return [
            'topPosts' => $topPosts
        ];
    }
}