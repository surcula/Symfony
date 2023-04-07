<?php

namespace App\security;

use App\Repository\UsersRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

class LoginAuthenticator extends AbstractAuthenticator
{

    private UsersRepository $usersRepository;
    private UrlGeneratorInterface $urlGenerator;

    /**
     * @param UsersRepository $usersRepository
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(UsersRepository $usersRepository, UrlGeneratorInterface $urlGenerator)
    {
        $this->usersRepository = $usersRepository;
        $this->urlGenerator = $urlGenerator;
    }


    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') === 'login' && $request->isMethod('POST');
    }

    public function authenticate(Request $request)
    {
        $user = $this->usersRepository->findOneByLowerUsername($request->request->get('username'));
        if(!$user){
            throw new CustomUserMessageAuthenticationException('invalid credentials');
        }
        $request->getSession()->set('filter',[
            'username'=>$user->getUsername(),
            'idRole'=>$user->getIdRole()->getId(),
            'idUser'=>$user->getId()
        ]);
        return new Passport(new UserBadge($user->getUsername()),new PasswordCredentials($request->request->get('password')),
        [new CsrfTokenBadge('login_form',$request->request->get('csrf_token'))]);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $request->getSession()->getFlashBag()->add('error', 'invalid login/password');

        return new RedirectResponse($this->urlGenerator->generate('index'));
    }
}