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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

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
        $imageName = '';
        if( $request->files )
        {
//            $dir = './uploads/images/'.date('Y/m/');
//
//            /** @var $file \Symfony\Component\HttpFoundation\File\UploadedFile */
//            foreach ($request->files as $file)
//            {
//                $name = md5( $file->getClientOriginalName(). microtime() ).'.'.$file->guessExtension();
//                $fs = new Filesystem();
//                if( !$fs->exists( $dir ) )
//                {
//                    try {
//                        $fs->mkdir( $dir );
//                    } catch (IOExceptionInterface $e) {
//                        echo "An error occurred while creating your directory at ".$e->getPath();
//                    }
//                }
//                $imageName = $name;
//                $file->move( $dir,  $name );
//                break;
//            }
        }
//        $em = $this->getDoctrine()->getManager();
//
//        $posts = new Posts();
//        $posts->setPostAuthor($this->getUser()->getId());
//        $posts->setPostTime(time());
//        $posts->setPostImage($imageName);
//        $posts->setPostTitle( $request->get('learnTitle') );
//        $posts->setPostContent( $request->get('learnContent') );
//        $posts->setPostDesc( $request->get('learnDesc'));
//        $posts->setPostKeyword( $request->get('learnKeyword') );
//        $posts->setPostReadNum(1);
//        $posts->setPostAction(0);
//        $posts->setPostCategoryId( $request->get('learnCategory') );
//
//        $em->persist($posts);
//        $em->flush();

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