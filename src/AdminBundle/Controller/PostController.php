<?php
/**
 * Created by PhpStorm.
 * User: LatteCake
 * Date: 15/7/26
 * Time: 下午2:46
 */

namespace AdminBundle\Controller;
use Monolog\Logger;
use StoreBundle\Entity\Category;
use StoreBundle\Entity\Image;
use StoreBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Util\SecureRandom;
use Symfony\Component\Filesystem\Exception\IOException;

/**
 * Class PostController
 * @package AdminBundle\Controller
 *
 * @Route("/admin/post")
 */
class PostController extends Controller
{

    /**
     * 草稿箱
     *
     * @Route("/drafts/{page}", name="admin_postDrafts", requirements={"page"="\d+"}, defaults={"page": 1})
     *
     * @param int $page
     * @return array
     */
    public function draftsAction( $page = 1 )
    {
        return [];
    }

    /**
     * 文章列表
     *
     * @Route("/list/{action}/{page}", name="admin_postList", requirements={"action"="\d+", "page"="\d+"}, defaults={"action": 1, "page": 1})
     * @Template()
     *
     * @param $action
     * @param $page integer
     * @return array
     */
    public function listAction( $action, $page = 1 )
    {
        /** @var Logger $logger */
        $logger = $this->get('logger');

        $actionName = '学无止境';
        if( $action == 2 )
            $actionName = '慢生活';

        $logger->info("params.", ['action' => $action, 'actionName' => $actionName]);

        /** @var Post $postEntity */
        $postEntity = $this->getDoctrine()->getRepository('StoreBundle:Post');

        $posts = $postEntity->findPostsPage( $action, $page );
        $postTotal = $postEntity->countPosts( $action );

        $logger->info("getData.", ["postCount" => $postTotal]);

        return [
            'action'     => $action,
            'actionName' => $actionName,
            'posts'      => $posts,
            'pageTotal'  => ceil( $postTotal / 10 ),
            'page'       => $page,
            'postTotal'  => $postTotal,
            'startPost'  => (($page - 1) * 10) + 1,
            'endPost'    => (($page - 1) * 10) + count( $posts )
        ];
    }

    /**
     * 新建文章
     *
     * @Route("/new/{action}", name="admin_postNew", requirements={"action"="\d+"}, defaults={"action": 1})
     *
     * @param $action integer
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction( $action )
    {
        $post = new Post();

        $post->setAction( $action )
            ->setAuthor( $this->getUser() );

//        $em = $this->getDoctrine()->getManager();

//        $em->persist($post);
//        $em->flush();

        $categoryEntity = $this->getDoctrine()->getRepository('StoreBundle:Category');

        $response = [
            'post' => $post
        ];

        if( $action == 1 )
        {
            $response['categoryList'] = $categoryEntity->findAll();
        }

        return $this->render('AdminBundle:Post:new.html.twig', $response);
    }

    /**
     * 删除文章
     * @Route("/removeArticle", name="admin_postRemoveArticle", )
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function removeArticle( Request $request )
    {
        $response = [
            'success' => true,
            'status'  => '00000',
            'message' => '操作成功',
            'data'    => []
        ];

        $id = $request->get('id');

        $postEntity = $this->getDoctrine()->getRepository('StoreBundle:Post');

        /** @var  $postInfo Post */
        $postInfo = $postEntity->find( $id );
        if( !$postInfo )
        {
            $response['success'] = false;
            $response['message'] = '文章不存在';
            return new JsonResponse( $response );
        }

        $postInfo->setStatus( 2 );
        $em = $this->getDoctrine()->getManager();
        $em->persist($postInfo);
        $em->flush();

        return new JsonResponse( $response );
    }

    /**
     * 保存草稿活直接发布文章
     * @Route("/write", name="admin_postWrite")
     * @Method({"POST"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function writeAction( Request $request )
    {
        $response = [
            'success'   => true,
            'errorCode' => '',
            'message'   => '操作成功',
            'data'      => ''
        ];

        $em = $this->getDoctrine()->getManager();
        if( $request->get('postId') )
        {
            $post = $this->getDoctrine()->getRepository('StoreBundle:Post');
            $postInfo = $post->find( $request->get('postId') );
        }else
        {
            $postInfo = new Post();
            $postInfo->setAction(2)
                ->setAuthorId( 1 )
                ->setCategoryId( 0 )
                ->setIsMarkdown( 1 );

//            $em->persist($postInfo);
//            $em->flush();
        }

        if( (int)$request->get('postStatus') == 1 and empty($request->get('imageIds') ) and empty($postInfo->getImages()) )
        {
            $response['success'] = false;
            $response['message'] = "请设置文章头图";
            return new JsonResponse( $response, Response::HTTP_OK );
        }

        $postInfo->setTitle( $request->get('postTitle') )
            ->setIsMarkdown( 1 )
            ->setDescription( $request->get('description') )
            ->setContent( $request->get('content') )
            ->setAuthorId( 1 )
            ->setAuthor( $this->getUser() )
            ->setStatus( intval($request->get('postStatus')) );

        if( $request->get('category') )
        {
            $categoryEntity = $this->getDoctrine()->getRepository('StoreBundle:Category');

            /** @var  $categoryInfo Category */
            $categoryInfo = $categoryEntity->find($request->get('category'));

            $postInfo->setCategory( $categoryInfo );
        }

        if( $postInfo->getStatus() == 1 AND empty($postInfo->getModified()) )
        {
            $postInfo->setModified( new \DateTime() );
        }

        $em->persist( $postInfo );
        $em->flush();

        if( $request->get('imageIds') )
        {
            $imageIds = explode(",", $request->get('imageIds'));

            /** @var Image $imageEntity */
            $imageEntity = $this->getDoctrine()->getRepository('StoreBundle:Image');

            $imageEntity->updateUnImageByPostId($postInfo);

            $imageEntity->updateImagePostByIds( $postInfo, $imageIds );
        }

        $redirectUrl = '';

        /** 预览文章 */
        switch( $request->get('postStatus') )
        {
            case 0:
                $redirectUrl = $this->generateUrl('admin_postPreview', ['id' => $postInfo->getId()]);
                break;
            case 3: // 草稿
//                $redirectUrl = $this->generateUrl('admin_postPreview');
                break;
        }
        $response['data'] = [
            'redirect' => $redirectUrl
        ];
        return new JsonResponse($response);
    }

    /**
     * 预览文章
     * @Route("/preview/{id}", name="admin_postPreview", requirements={"id"="\d+"})
     * @ParamConverter("post", class="StoreBundle:Post")
     *
     * @param Post $postInfo
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function previewAction( Post $postInfo )
    {
        $parseDown = new \Parsedown();

        return $this->render('AppBundle:Post:postInfo.html.twig', [
            'parseDown' => $parseDown,
            'post'      => $postInfo,
            'action'    => 'learn'
        ]);
    }
}