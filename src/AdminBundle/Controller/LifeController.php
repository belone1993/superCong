<?php
/**
 * Created by PhpStorm.
 * User: LatteCake
 * Date: 15/7/26
 * Time: 下午3:16
 */

namespace AdminBundle\Controller;
use StoreBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class LifeController
 * @package AdminBundle\Controller
 *
 * @Route("/admin/life")
 */
class LifeController extends Controller
{
    /**
     * 慢生活文章列表
     *
     * @Route("/grow/{page}", name="admin_lifeGrow", defaults={"page":1}, requirements={"page"="\d+"})
     * @Template()
     *
     * @param integer $page
     * @return array
     */
    public function growAction( $page )
    {
        /** @var  $post Post */
        $post = $this->getDoctrine()->getRepository('StoreBundle:Post');
        $posts = $post->findPostsPage( 2, $page, null, [0, 1, 3] );

        $postTotal = $post->countPosts( 2 );

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
     * 点点滴滴
     *
     * @Route("/picture")
     * @Template()
     */
    public function pictureAction()
    {
        return [];
    }

    /**
     * 编辑文章页面
     * @Route("/editArticle/{id}", name="admin_lifeEditArticle", defaults={"id": 0}, requirements={"id"="\d+"})
     * @Template()
     *
     * @param integer $id
     * @return array
     */
    public function editArticleAction( $id )
    {

        if( !empty( $id ) )
        {
            $postRepository = $this->getDoctrine()->getRepository('StoreBundle:Post');

            $post = $postRepository->find($id);
        }else
        {
            /** @var  $postRepository Post */
            $postRepository = $this->getDoctrine()->getRepository('StoreBundle:Post');
            $post = $postRepository->findOneBy([
                'status' => 0,
                'action' => 2
            ], [
                'id' => 'DESC'
            ]);
            if( empty( $post ) )
            {
                $post = new Post();
                $post->setAction(2)
                    ->setAuthor( $this->getUser() )
                    ->setCategoryId( 0 )
                    ->setContent( ' ' )
                    ->setDescription( ' ' )
                    ->setImage( ' ' )
                    ->setIsMarkdown( 1 )
                    ->setTitle( ' ' );

                $em = $this->getDoctrine()->getManager();
                $em->persist($post);
                $em->flush();
            }

        }

        return [
            'post' => $post
        ];
    }

}