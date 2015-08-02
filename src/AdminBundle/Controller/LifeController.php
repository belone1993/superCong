<?php
/**
 * Created by PhpStorm.
 * User: LatteCake
 * Date: 15/7/26
 * Time: 下午3:16
 */

namespace AdminBundle\Controller;
use AppBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

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
        $posts = $post->findPostsPage( 2, $page );

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
        $post = new Post();

        if( !empty( $id ) )
        {
            $postRepository = $this->getDoctrine()->getRepository('StoreBundle:Post');

            $post = $postRepository->find($id);
        }

        return [
            'post'         => $post
        ];
    }

    /**
     * 保存草稿活直接发布文章
     * @Route("/write", name="admin_lifeWrite")
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
        var_dump($request->files);die;
        $imageName = '';
        if( $request->files )
        {
            $dateTime = new \DateTime();
            $dir = './uploads/images/'.$dateTime->format('Y/m');

            /** @var $file \Symfony\Component\HttpFoundation\File\UploadedFile */
            foreach ($request->files as $file)
            {
                $name = password_hash($file->getClientOriginalName(). microtime(), true).'.'.$file->guessExtension();
                echo $name;die;
                $fs = new Filesystem();
                if( !$fs->exists( $dir ) )
                {
                    try {
                        $fs->mkdir( $dir );
                    } catch (IOException $e) {
                        echo "An error occurred while creating your directory at ".$e->getPath();
                    }
                }
                $imageName = $name;
                $file->move( $dir,  $name );
                break;
            }
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

}