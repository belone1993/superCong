<?php
/**
 * Created by PhpStorm.
 * User: LatteCake
 * Date: 15/7/21
 * Time: 上午11:02
 */

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class SecurityController
 * @package AppBundle\Controller
 *
 * @Route("/security")
 */
class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login_route")
     * @Template()
     *
     * @param Request $request
     * @return array
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return array(
            // last username entered by the user
            'last_username' => $lastUsername,
            'error'         => $error,
        );
    }

    /**
     * @Route("/loginCheck", name="login_check")
     *
     * @param Request $request
     */
    public function loginCheckAction( Request $request )
    {
        var_dump($request);die;
        // this controller will not be executed,
        // as the route is handled by the Security system
    }

    /**
     * @Route("/logout", name="logout")
     *
     * @param Request $request
     */
    public function logoutAction( Request $request )
    {

    }

    /**
     * @Route("/create", name="register_check")
     *
     * @param Request $request
     */
    public function createAction( Request $request )
    {
        $username = $request->get('username');
        $email    = $request->get('email');
        $password = $request->get('password');

//        $userRepository = $this->getDoctrine()->getRepository('AppBundle:User');

        $userInfo = $this->getDoctrine()->getEntityManager()
            ->createQueryBuilder()
            ->select("u")
            ->from('AppBundle:User', 'u')
            ->where(' u.username = :username ')
            ->orWhere( 'u.email = :email' )
            ->setParameters( new ArrayCollection(
                    [
                        new Parameter( 'username', $username ),
                        new Parameter( 'email', $email )
                    ]
                )
            )->getQuery()->getResult();

        if( $userInfo )
        {
            throw new HttpException("用户名或邮箱已存在");
        }

        $em = $this->getDoctrine()->getManager();

        $user = new User();
        $user->setUsername( $username )
            ->setEmail( $email );
//        $encoder = $this->container->get('security.password_encoder');

        $encoded = password_hash($password, PASSWORD_BCRYPT, array("cost" => 12));

//        $encoded = $encoder->encodePassword($user, $password);

        $user->setPassword($encoded);

        $em->persist($user);
        $em->flush();
    }
}