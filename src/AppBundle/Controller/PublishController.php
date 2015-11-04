<?php
/**
 * Created by PhpStorm.
 * User: LatteCake
 * Date: 15/10/29
 * Time: 上午11:52
 * File: PublishController.php
 */

namespace AppBundle\Controller;


use GuzzleHttp\Client;
use StoreBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PublishController
 *
 * @Route("/publish")
 *
 * @package AppBundle\Controller
 */
class PublishController extends Controller
{

    /**
     *
     * @Route("/putPost")
     *
     * @param Request $request
     * @return void
     */
    public function putPostAction( Request $request )
    {
        var_dump($request);die;
    }

    /**
     * @Route("/pushBaiDu")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function puBaiDuAction( Request $request )
    {
        $postEntity = $this->getDoctrine()->getRepository('StoreBundle:Post');

        $posts = $postEntity->findAll();

        $urls = [];

        /** @var  $post Post */
        foreach( $posts as $post ) {
            //$urls[] = $this->generateUrl('post_detail', ['id' => $post->getId()]);
            $urls[] = 'http://lattecake.com/post/'.$post->getId();
        }

        $api = 'http://data.zz.baidu.com/urls?site=www.lattecake.com&token=nRKurEJqZZFDGQwe';

//        $client = new Client();
//
//        $client->post($api, );
//
//        $urls = array(
//            'http://www.example.com/1.html',
//            'http://www.example.com/2.html',
//        );

        $ch = curl_init();
        $options =  array(
            CURLOPT_URL => $api,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => implode("\n", $urls),
            CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
        );
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);

        return new JsonResponse($result, Response::HTTP_OK);
    }
}