<?php
/**
 * Created by PhpStorm.
 * User: LatteCake
 * Date: 15/7/18
 * Time: 下午3:34
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class LifeController
 * @package AppBundle\Controller
 *
 * @Route("/life")
 */
class LifeController extends Controller
{
    /**
     * @Route("/lifeInfo/{id}", name="life_lifeInfo", requirements={"id"="\d+"})
     * @Template()
     *
     * @param int $id
     * @return array
     */
    public function lifeInfoAction( $id )
    {
        if (!$id) {
            throw $this->createNotFoundException('No product found for id '.$id);
        }

        /** @var  $post \AppBundle\Entity\Post */
        $post = $this->getDoctrine()->getRepository('AppBundle:Post');

        if( $id < 20000 )
        {
            /** @var  $postInfo Post */
            $postInfo = $post->findPostByOldId( $id, 2 );
        }else
        {
            /** @var  $postInfo Post */
            $postInfo = $post->find( $id );
        }

        $postInfo->setReadNum( $postInfo->getReadNum() + 1 );

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $parseDown = new \Parsedown();

        return [
            'parseDown' => $parseDown,
            'post'      => $postInfo,
            'action'    => 'life'
        ];
    }

    /**
     * @Route("/grow/{page}", name="life_grow", defaults={"page":1}, requirements={"page"="\d+"})
     * @Template()
     *
     * @param int $page
     * @return array
     */
    public function growAction( $page )
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
     * @Route("/picture", name="life_picture")
     * @Template()
     *
     * @return array
     */
    public function pictureAction()
    {
        return [
            'action' => 'life'
        ];
    }
}