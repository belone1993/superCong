<?php
/**
 * Created by PhpStorm.
 * User: LatteCake
 * Date: 15/10/15
 * Time: 上午11:24
 * File: SyncController.php
 */

namespace AppBundle\Controller;


use Doctrine\ORM\EntityManager;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use StoreBundle\Entity\Comment;
use StoreBundle\Entity\Member;
use StoreBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SyncController
 *
 * @Route("/sync")
 *
 * @package AppBundle\Controller
 */
class SyncController extends Controller
{

    /**
     * 同步评论数据
     * @Route("/comments")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function commentsAction( Request $request )
    {

        $action     = $request->get('action');
        $signature  = $request->get('signature');

        /** @var  $commentEntity Comment */
        $commentEntity = $this->getDoctrine()->getRepository('StoreBundle:Comment');

        /** @var  $commentInfo Comment */
        $commentInfo = $commentEntity->findCommentByLast();

        $sinceId = 0;

        if( $commentInfo )
        {
            $sinceId = $commentInfo->getLogId();
        }

        unset($commentInfo);

        $duoApi = $this->getParameter('duoshuo_api')."log/list.json?short_name=lattecake&secret=c9d92470f13da30f2da8f48a4f4e167a&since_id={$sinceId}&limit=100&order=asc";

        $client = new Client();

        try
        {
            $res = $client->get($duoApi);

            if($res->getStatusCode())
            {
                $response = json_decode($res->getBody()->getContents(), true);
                $response = $response['response'];

                if( !$response || @$response['code'] != 0 )
                {
                    return new JsonResponse( [
                        'success'   => false,
                        'message'   => 'response is null',
                        'errorCode' => ''
                    ], JsonResponse::HTTP_FOUND );
                }

                /** @var  $em EntityManager */
                $em = $this->getDoctrine()->getManager();

                $memberEntity = $this->getDoctrine()->getRepository('StoreBundle:Member');

                foreach( $response as $value )
                {

                    $meta = $value['meta'];

                    $commentInfo = $commentEntity->findOneBy(['logId' => $value['log_id']]);

                    if( in_array($value['action'], ['delete', 'spam', 'delete-forever']) )
                    {
                        if( $commentInfo )
                        {
                            $commentInfo->setStatus( $value['action'] );
                            $em->persist($commentInfo);
                            $em->flush();
                        }
                        continue;
                    }

                    if( !$commentInfo )
                    {
                        $commentInfo = new Comment();

                        $dateTime = new \DateTime();

                        if( !empty( $meta['created_at'] ) )
                        {
                            $dateTime->setTimestamp(strtotime($meta['created_at']));
                        }

                        $commentInfo->setLogId($value['log_id'])
                            ->setApiUserId( $value['user_id'] )
                            ->setMessage( $meta['message'] )
                            ->setStatus( $meta['status'] )
                            ->setParentId( $meta['parent_id'] )
                            ->setAgent( $meta['agent'] )
                            ->setType( $meta['type'] )
                            ->setCreatedAt( $dateTime )
                            ->setCommentIP( $meta['ip'] )
                            ->setThreadId($meta['thread_id'])
                            ->setApiPostId( $meta['post_id'] )
                            ->setThreadKey( $meta['thread_key'] );

//                        $threadKey = intval($meta['thread_key']);
                        $threadKey = 0;

                        $keys = explode('_', $meta['thread_key']);
                        if( count( $keys ) > 1 )
                        {
                            $threadKey = $keys[1];
                        }

                        if( $threadKey > 0 )
                        {
                            $postEntity = $this->getDoctrine()->getRepository('StoreBundle:Post');

                            $key = 'id';
                            if( $threadKey < 20000 )
                            {
                                $key = 'oldId';
                            }
                            /** @var  $postInfo Post */
                            $postInfo = $postEntity->findOneBy([$key => $threadKey]);

                            if( $postInfo ) {
                                $commentInfo->setPostInfo( $postInfo );

                                /** 增加一条评论数 */
                                $postInfo->setReviews( $postInfo->getReviews()+1 );
                                $em->persist($postInfo);
                                $em->flush($postInfo);
                            }
                        }
                    }else
                    {
                        $commentInfo->setStatus( $value['action'] );
                    }

                    $em->persist($commentInfo);
                    $em->flush();

                    $memberInfo = $memberEntity->findOneBy(['authorId' => $value['user_id']]);

                    if( !$memberInfo )
                    {
                        $memberInfo = new Member();

                        $memberInfo->setAuthorName( $meta['author_name'] )
                            ->setAuthorId($meta['author_id'])
                            ->setAuthorEmail( $meta['author_email'] )
                            ->setAuthorUrl( $meta['author_key'] );

                        $em->persist( $memberInfo );
                    }
                    $em->flush();
                }
            }
        }catch ( ClientException $e )
        {
            echo $e->getMessage();
        }
        $response = array
        (
            'success'   => true,
            'message'   => 'SUCCESS',
            'errorCode' => '',
            'data'      => [
                'action'     => $action,
                'signature'  => $signature
            ]
        );

        return new JsonResponse($response, Response::HTTP_OK);
    }

}