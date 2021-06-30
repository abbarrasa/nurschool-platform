<?php


namespace Nurschool\Shared\Infrastructure\Symfony\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login", methods={"GET", "POST"})
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * @Route("/logout", name="logout", methods={"GET"})
     */
    public function logout(): void
    {
        // controller can be blank: it will never be executed!
        throw new \Exception("Don't forget to activate logout in security.yaml");
    }

    /**
     * Link to this controller to start the "connect" process
     *
     * @Route("/connect/{client}", name="connect_start")
     */
    public function connectAction(ClientRegistry $clientRegistry, string $client)
    {
        // will redirect to client!
        return $clientRegistry
            ->getClient($client) // key used in config/packages/knpu_oauth2_client.yaml
        ;
    }

    /**
     * After going to service, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config/packages/knpu_oauth2_client.yaml
     *
     * @Route("/connect/{client}/check", name="connect_check")
     */
    public function connectCheckAction()
//    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry)
    {
        // ** if you want to *authenticate* the user, then
        // leave this method blank and create a Guard authenticator
        // (read below)

//        /** @var GoogleClient $client */
//        $client = $clientRegistry->getClient('facebook_main');
//
//        try {
//            // the exact class depends on which provider you're using
//            /** @var \League\OAuth2\Client\Provider\FacebookUser $user */
//            $user = $client->fetchUser();
//
//            // do something with all this new power!
//            // e.g. $name = $user->getFirstName();
//            var_dump($user); die;
//            // ...
//        } catch (IdentityProviderException $e) {
//            // something went wrong!
//            // probably you should return the reason to the user
//            var_dump($e->getMessage()); die;
//        }
    }

}