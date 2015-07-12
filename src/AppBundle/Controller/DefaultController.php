<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class DefaultController
 *
 * @Route("/")
 * @package AppBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction()
    {
        return [];
    }

    /**
     * @Route("/guestBook", name="guestBook")
     * @Template()
     */
    public function guestBookAction()
    {
        return [];
    }

    /**
     * @Route("/learn", name="learn")
     * @Template()
     */
    public function learnAction()
    {

        $post = new Post();

        $post->setTitle('测试标题')
            ->setContent('测试内容测试内容测试内容测试内容测试内容测试内容测试内容测试内容测试内容测试内容测试内容')
            ->setDescription('简介简介简介简介简介简介简介简介简介简介');

        $em = $this->getDoctrine()->getManager();

        $em->persist($post);
        $em->flush();

        $post = $this->getDoctrine()->getRepository('AppBundle:Post');

        $posts = $post->findAll();

        return [
            'posts' => $posts
        ];
    }

    /**
     * @Route("/life", name="life")
     * @Template()
     */
    public function lifeAction()
    {
        return [];
    }

    /**
     * @Route("/about", name="about")
     * @Template()
     */
    public function aboutAction()
    {
        return [];
    }
}
