<?php
/**
 * Created by PhpStorm.
 * User: LatteCake
 * Date: 15/7/18
 * Time: 下午3:34
 */

namespace AppBundle\Controller;

use StoreBundle\Entity\Post;
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

        /** @var  $post \StoreBundle\Entity\Post */
        $post = $this->getDoctrine()->getRepository('StoreBundle:Post');

        if( $id < 20000 )
        {
            /** @var  $postInfo Post */
            $postInfo = $post->findPostByOldId( $id, 2 );
        }else
        {
            /** @var  $postInfo Post */
            $postInfo = $post->findOneBy([
                'id'     => $id,
                'action' => 2
            ]);
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
        /** @var  $post \StoreBundle\Entity\Repository\PostRepository */
        $post = $this->getDoctrine()->getRepository('StoreBundle:Post');

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