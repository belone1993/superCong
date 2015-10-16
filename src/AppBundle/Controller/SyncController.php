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
use StoreBundle\Entity\ConnectService;
use StoreBundle\Entity\Member;
use StoreBundle\Entity\MemberInfo;
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

                        $threadKey = $meta['thread_key'];

                        $keys = explode('_', $meta['thread_key']);
                        if( count( $keys ) > 1 )
                        {
                            $threadKey = $keys[1];
                        }else if( $threadKey == 'guestBook' )
                        {
                            $threadKey = 0;
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

    /**
     * 同步用户数据
     * @Route("/member")
     *
     * @return JsonResponse
     */
    public function memberAction()
    {
        // http://api.duoshuo.com/users/profile.json?user_id=378333

        $memberEntity = $this->getDoctrine()->getRepository('StoreBundle:Member');

        $members = $memberEntity->findAll();

        $response = [];

        if( !$members )
        {
            $response = [
                'success' => 'false',
                'message' => 'member is null'
            ];

            return new JsonResponse( $response );
        }

        try{

            $duoApi = $this->getParameter('duoshuo_api').'users/profile.json';

            $client = new Client();

            $em = $this->getDoctrine()->getManager();

            /** @var  $member Member */
            foreach( $members as $member )
            {

                if(!$member->getAuthorId() || $member->getMemberInfo() )
                    continue;

                $res = $client->get($duoApi."?user_id={$member->getAuthorId()}");

                if($res->getStatusCode()) {
                    $response = json_decode( $res->getBody()->getContents(), true );
                    $response = $response[ 'response' ];

                    $memberInfo = new MemberInfo();

                    $memberInfo->setName( $response['name'] )
                        ->setAvatarUrl( $response['avatar_url'] )
                        ->setUrl( $response['url'] )
                        ->setThreads( $response['threads'] )
                        ->setComments( $response['comments'] )
                        ->setPostVotes( $response['post_votes'] )
                        ->setMember( $member );

                    $em->persist($memberInfo);
                    $em->flush();

                    if( $response['connected_services'] )
                    {
                        foreach( $response['connected_services'] as $key => $value )
                        {
                            $connectInfo = new ConnectService();
                            $connectInfo->setName( $value['name'] )
                                ->setAvatarUrl( $value['avatar_url'] )
                                ->setUrl( !empty($value['url']) ? $value['url'] : '' )
                                ->setDescription( !empty($value['description']) ? $value['description'] : '' )
                                ->setServiceName( $key )
                                ->setEmail( !empty($value['email']) ? $value['email'] : '' )
                                ->setMemberInfo( $memberInfo );
                            if( !empty( $response['social_uid'][$key] ) )
                            {
                                $connectInfo->setSocialId( $response['social_uid'][$key] );
                            }
                            $em->persist( $connectInfo );
                        }
                        $em->flush();
                    }
                }
            }

            $response = [
                'success' => true
            ];

            return new JsonResponse( $response, Response::HTTP_OK );

        }catch (ClientException $e)
        {
            $response[] = $e->getMessage();
        }

        return new JsonResponse($response, Response::HTTP_OK);
    }

}