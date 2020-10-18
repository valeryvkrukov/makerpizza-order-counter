<?php


namespace App\Security;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Firebase\JWT\JWT;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class JwtAuthenticator extends AbstractAuthenticator
{
    private EntityManagerInterface $entityManager;
    private ContainerBagInterface $params;

    /**
     * JwtAuthenticator constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ContainerBagInterface $params
     */
    public function __construct(EntityManagerInterface $entityManager, ContainerBagInterface $params)
    {
        $this->entityManager = $entityManager;
        $this->params = $params;
    }

    /**
     * @param Request $request
     * @return bool|null
     */
    public function supports(Request $request): ?bool
    {
        return $request->headers->has('Authorization');
    }

    /**
     * @param Request $request
     * @return SelfValidatingPassport
     */
    public function authenticate(Request $request): SelfValidatingPassport
    {
        $apiToken = $request->headers->get('Authorization');
        if ($apiToken === null) {
            throw new CustomUserMessageAuthenticationException('No API token provided');
        }
        try {
            $credentials = str_replace('Bearer ', '', $apiToken);
            $jwt = (array) JWT::decode(
                $credentials,
                $this->params->get('jwt_secret'),
                ['HS256']
            );
            $user = $this->entityManager->getRepository(User::class)
                ->findOneBy([
                    'email' => $jwt['user'],
                ]);

            return new SelfValidatingPassport($user);
        } catch (\Exception $exception) {
            throw new UsernameNotFoundException();
        }
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @param string $firewallName
     * @return Response|null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    /**
     * @param Request $request
     * @param AuthenticationException $exception
     * @return Response|null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse([
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        ], Response::HTTP_UNAUTHORIZED);
    }
}
