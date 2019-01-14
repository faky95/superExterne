<?php
namespace Orange\MainBundle\EvenListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\DependencyInjection\Container;

class PassUpdateListener
{
    
    private $router;
    private $session;
    private $container;
    
    public function __construct(Router $router, Session $session, Container $container)
    {
        $this->router 			= $router;
        $this->session 			= $session;
        $this->container 		= $container;
    }
    
    public function onCheckExpired(GetResponseEvent $event) {
    	$securityChecker = $this->container->get('security.authorization_checker');
    	$securityToken = $this->container->get('security.token_storage');
        if($securityToken->getToken() && $securityChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            $route_name = $event->getRequest()->get('_route');
            if ($route_name != 'first_change_password' && !$securityToken->getToken()->getUser()->getFirstChangePassword()) {
                $response = new RedirectResponse($this->router->generate('first_change_password'));
                $event->setResponse($response);
            }
        }
    }
    
    
}
