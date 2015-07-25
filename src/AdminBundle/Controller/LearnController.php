<?php
/**
 * Created by PhpStorm.
 * User: LatteCake
 * Date: 15/7/25
 * Time: 下午4:21
 */

namespace AdminBundle\Controller;


use StoreBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class LearnController
 * @package AdminBundle\Controller
 *
 * @Route("/admin/learn")
 */
class LearnController extends Controller
{

    /**
     * @Route("/archives/{page}", name="admin_learnArchives", defaults={"page":1, "category":""}, requirements={"page"="\d+"})
     * @Template()
     *
     * @param int $page
     * @return array
     */
    public function archivesAction( $page )
    {
        /** @var  $post Post */
        $post = $this->getDoctrine()->getRepository('StoreBundle:Post');
        $posts = $post->findPostsPage( 1, $page );

        $postTotal = $post->countPosts( 1 );

        return [
            'posts'     => $posts,
            'pageTotal' => ceil( $postTotal / 10 ),
            'page'      => $page,
            'postTotal' => $postTotal,
            'startPost' => (($page - 1) * 10) + 1,
            'endPost'   => (($page - 1) * 10) + count( $posts )
        ];
    }

    /**
     * @Route("/write", name="admin_learnWrite")
     * @Template()
     *
     * @return array
     */
    public function writeAction( )
    {
        return [];
    }
}