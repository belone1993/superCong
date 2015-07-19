<?php
/**
 * Created by PhpStorm.
 * User: LatteCake
 * Date: 15/7/18
 * Time: 下午3:21
 */

namespace AppBundle\Controller;
use AppBundle\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class LearnController
 * @package AppBundle\Controller
 *
 * @Route("/learn")
 */
class LearnController extends Controller
{
    /**
     * @Route("/learnInfo/{id}", name="learn_learnInfo", requirements={"id"="\d+"})
     * @Template()
     * @ParamConverter("posts", class="AppBundle:Post")
     *
     * @param Request $request
     * @param Post $postInfo
     * @return array
     */
    public function learnInfoAction( Request $request, Post $postInfo )
    {
        if (!$postInfo) {
            throw $this->createNotFoundException('No product found for id '.$request->get('id'));
        }

        $postInfo->setReadNum($postInfo->getReadNum() + 1);

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $parseDown = new \Parsedown();

        return [
            'parseDown' => $parseDown,
            'post'      => $postInfo,
            'action'    => 'learn'
        ];
    }
}