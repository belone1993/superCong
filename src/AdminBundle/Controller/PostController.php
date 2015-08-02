<?php
/**
 * Created by PhpStorm.
 * User: LatteCake
 * Date: 15/7/26
 * Time: 下午2:46
 */

namespace AdminBundle\Controller;
use StoreBundle\Entity\Image;
use StoreBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\HttpFoundation\File\UploadedFile;
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

        if( !empty( $request->get('postId') ) )
        {
            $post = $this->getDoctrine()->getRepository('StoreBundle:Post');
            $postInfo = $post->find( $request->get('postId') );
        }else
        {
            $postInfo = new Post();
            $postInfo->setAction(2)
                ->setAuthorId( 1 )
                ->setCategoryId( 0 )
                ->setContent( ' ' )
                ->setDescription( ' ' )
                ->setImage( ' ' )
                ->setIsMarkdown( 1 )
                ->setTitle( ' ' );

            $em = $this->getDoctrine()->getManager();
            $em->persist($postInfo);
            $em->flush();
        }

        if( $request->files->get('postImage') )
        {
            $dateTime = new \DateTime();
            $dir = './uploads/images/'.$dateTime->format('Y/m');

            /** @var $file \Symfony\Component\HttpFoundation\File\UploadedFile */
            foreach ($request->files->get('postImage') as $file)
            {
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

                $em = $this->getDoctrine()->getManager();
                $em->persist( $image );
                $em->flush();
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