<?php
/**
 * Created by PhpStorm.
 * User: LatteCake
 * Date: 15/7/25
 * Time: 下午4:21
 */

namespace AdminBundle\Controller;


use StoreBundle\Entity\Category;
use StoreBundle\Entity\Image;
use StoreBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\Security\Core\Util\SecureRandom;

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
     * 保存草稿活直接发布文章
     * @Route("/write", name="admin_learnWrite")
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

        if( !$request->files )
        {
            $response['success'] = false;
            $response['message'] = '图片不能为空！';
            return new JsonResponse($response);
        }
        if( !$request->get('postTitle') || !$request->get('content') )
        {
            $response['success'] = false;
            $response['message'] = '其他参数不能为空！';
            return new JsonResponse($response);
        }
        if( !$request->get('category') )
        {
            $response['success'] = false;
            $response['message'] = '分类不能为空！';
            return new JsonResponse($response);
        }
        $em = $this->getDoctrine()->getManager();
        if( $request->get('postId') )
        {
            $post = $this->getDoctrine()->getRepository('StoreBundle:Post');
            $postInfo = $post->find( $request->get('postId') );
        }else
        {
            $postInfo = new Post();
            $postInfo->setAction(2)
                ->setAuthor($this->getUser())
                ->setIsMarkdown( 1 );

            $em->persist($postInfo);
            $em->flush();
        }

        if( $request->files->get('postImage') )
        {
            $dateTime = new \DateTime();
            $dir = 'uploads/images/'.$dateTime->format('Y/m');

            /** @var $file \Symfony\Component\HttpFoundation\File\UploadedFile */
            $file = $request->files->get('postImage');
            $generator = new SecureRandom();
            $random = $generator->nextBytes(10);
            $hashedRandom = md5($random); // see tip below
            $name = $hashedRandom.'.'.$file->guessExtension();
            $fs = new Filesystem();
            if( !$fs->exists( $dir ) )
            {
                try {
                    $fs->mkdir( $dir );
                } catch (IOException $e) {
                    echo "An error occurred while creating your directory at ".$e->getPath();
                }
            }
            $fileData = $file->move( $dir,  $name );

            $image = new Image();
            $image->setExtension( $fileData->getExtension() )
                ->setImageName( $fileData->getFilename() )
                ->setImagePath( $fileData->getPath() )
                ->setRealPath( $fileData->getRealPath() )
                ->setImageSize( $fileData->getSize() )
                ->setPostInfo( $postInfo );

            $em->persist( $image );
            $em->flush();
        }

        $categoryEntity = $this->getDoctrine()->getRepository('StoreBundle:Category');

        /** @var  $categoryInfo Category */
        $categoryInfo = $categoryEntity->find($request->get('category'));

        $postInfo->setTitle( $request->get('postTitle') )
            ->setIsMarkdown( $request->get('markdown') )
            ->setDescription( $request->get('description') )
            ->setContent( $request->get('content') )
            ->setAuthorId( 1 )
            ->setCategory( $categoryInfo )
            ->setAuthor( $this->getUser() )
            ->setStatus( $request->get('postStatus') );

        $em->persist( $postInfo );
        $em->flush();

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
     * 编辑文章页面
     * @Route("/editArticle/{id}", name="admin_learnEditArticle", defaults={"id":0}, requirements={"id"="\d+"})
     * @Template()
     *
     * @param integer $id
     * @return array
     */
    public function editArticleAction( $id )
    {
        $categoryRepository = $this->getDoctrine()->getRepository('StoreBundle:Category');

        $categoryList = $categoryRepository->findAll();

        $post = new Post();

        if( !empty( $id ) )
        {
            $postRepository = $this->getDoctrine()->getRepository('StoreBundle:Post');

            $post = $postRepository->find($id);
        }

        return [
            'categoryList' => $categoryList,
            'post'         => $post
        ];
    }
}