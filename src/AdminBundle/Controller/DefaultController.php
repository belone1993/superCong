<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController
 *
 * @Route("/admin")
 * @package AdminBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="admin_index")
     * @Template()
     *
     * @param Request $request
     * @return array
     */
    public function indexAction( Request $request )
    {
        return array('name' => 'hello');
    }

    /**
     * @Route("/dashboard")
     * @Template()
     *
     * @return array
     */
    public function dashboardAction()
    {
        return [];
    }

    /**
     * 登录页面
     * @Route("/login", name="_admin_login")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction( Request $request )
    {
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContextInterface::AUTHENTICATION_ERROR
            );
        } elseif (null !== $session && $session->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
//            $error = $session->get(SecurityContextInterface::AUTHENTICATION_ERROR);
            $session->remove(SecurityContextInterface::AUTHENTICATION_ERROR);
            $error = "用户名或密码错误！";
        } else {
            $error = '';
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContextInterface::LAST_USERNAME);

        return $this->render('DemoAdminBundle:Default:login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    /**
     * 其实我也不晓得这东西有木有用(如果不定义这个路由就会报错,可能与我在 security.yml 里配制有关 )
     * 总之就是登录成功了 $request->getSession() 也可以获取到值
     * $this->getUser() 也有数据，我特么什么也没做。难道这个方法就写着吓唬自己的？好像也没跳到这个方法来 应该是直接就被什么安全机制给拦截了
     * 然后就给自动登录了 我好像什么也没做(-_-!) 具体的估计得看我之前写的 \DemoUserBundle\Entity:User 类 与它继承的那些接口 但那与这有毛关系啊....
     * 或许Security... 算了~ 那地方太绕了有时间慢慢研究吧... =_=
     *
     * @Route("/loginCheck", name="_login_check")
     */
    public function loginCheckAction()
    {
        return new RedirectResponse( $this->generateUrl('admin_index') );
    }

    /**
     * 退出登录
     * @Route("/logout", name="_admin_logout")
     * @param Request $request
     * @return RedirectResponse
     */
    public function logoutAction(Request $request)
    {
        $session = $request->getSession();
        $session->clear();
        return new RedirectResponse( $this->generateUrl('_admin_login') );
    }
}
