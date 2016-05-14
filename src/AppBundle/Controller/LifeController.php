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
use Symfony\Component\HttpFoundation\RedirectResponse;

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
        return new RedirectResponse($this->generateUrl('post_detail', ['id' => $id]));
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

        $pagination = $this->get('knp_paginator');

        $pagination = $pagination->paginate($posts, $page, 10);

        $action = 'life';

        return compact('page', 'action', 'pagination');
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

    /**
     * 生活
     * @Route("/feel", name="life_feel")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function feelAction()
    {
        return $this->render('AppBundle:Life:picture.html.twig', [
            'action' => 'life'
        ]);
    }

    /**
     * 生活
     * @Route("/gallery", name="life_gallery")
     * @Template()
     *
     * @return array
     */
    public function galleryAction()
    {
        return [
            'action' => 'life'
        ];
    }

    /**
     * 生活
     * @Route("/music", name="life_music")
     * @Template()
     *
     * @return array
     */
    public function musicAction()
    {
        return [
            'action' => 'life'
        ];
    }

    /**
     * 生活
     * @Route("/video", name="life_video")
     * @Template()
     *
     * @return array
     */
    public function videoAction()
    {
        return [
            'action' => 'life'
        ];
    }
}