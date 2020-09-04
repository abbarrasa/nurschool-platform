<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Controller;


use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use FOS\UserBundle\Controller\ProfileController as FOSUserProfileController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfileController extends AbstractController
{
//    use AdminContextTrait;

    /** @var FOSUserProfileController */
    private $profileController;

    /**
     * ProfileController constructor.
     * @param FOSUserProfileController $profileController
     */
    public function __construct(FOSUserProfileController $profileController)
    {
        $this->profileController = $profileController;
    }

    /**
     * Profile user.
     *
     * @param Request $request
     * @return Response|null
     */
    public function editAction(Request $request)
    {
        $changePasswordResponse = $this->forward('fos_user.change_password.controller:changePasswordAction', [$request]);
        $editProfileResponse = $this->forward('fos_user.profile.controller:editAction', [$request]);

        return $this->render('@FOSUser/Profile/profile.html.twig', [
            'change_password' => $changePasswordResponse->getContent(),
            'edit_profile' => $editProfileResponse->getContent()
        ]);
    }

    /**
     * Show the user.
     */
    public function showAction()
    {
        return $this->redirectToRoute('fos_user_profile_edit');
    }

    /**
     * Delete the user.
     * 
     * @param Request $request
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function deleteAction(Request $request, EventDispatcherInterface $eventDispatcher)
    {
        throw new MethodNotAllowedHttpException('This feature will be implemented very soon.');


//        $request = $this->getRequest();
//        $id = $request->get($this->admin->getIdParameter());
//        $object = $this->admin->getObject($id);
//        if (!$object) {
//            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
//        }
//        $this->checkParentChildAssociation($request, $object);
//        $this->admin->checkAccess('delete', $object);
//        $preResponse = $this->preDelete($request, $object);
//        if (null !== $preResponse) {
//            return $preResponse;
//        }
//        if ('DELETE' === $this->getRestMethod()) {
//            // check the csrf token
//            $this->validateCsrfToken('sonata.delete');
//            $objectName = $this->admin->toString($object);
//            try {
//                $this->admin->delete($object);
//                if ($this->isXmlHttpRequest()) {
//                    return $this->renderJson(['result' => 'ok'], 200, []);
//                }
//                $this->addFlash(
//                    'sonata_flash_success',
//                    $this->trans(
//                        'flash_delete_success',
//                        ['%name%' => $this->escapeHtml($objectName)],
//                        'SonataAdminBundle'
//                    )
//                );
//            } catch (ModelManagerException $e) {
//                $this->handleModelManagerException($e);
//                if ($this->isXmlHttpRequest()) {
//                    return $this->renderJson(['result' => 'error'], 200, []);
//                }
//                $this->addFlash(
//                    'sonata_flash_error',
//                    $this->trans(
//                        'flash_delete_error',
//                        ['%name%' => $this->escapeHtml($objectName)],
//                        'SonataAdminBundle'
//                    )
//                );
//            }
//            return $this->redirectTo($object);
//        }
//        // NEXT_MAJOR: Remove this line and use commented line below it instead
//        $template = $this->admin->getTemplate('delete');
//        // $template = $this->templateRegistry->getTemplate('delete');
//        return $this->renderWithExtraParams($template, [
//            'object' => $object,
//            'action' => 'delete',
//            'csrf_token' => $this->getCsrfToken('sonata.delete'),
//        ], null);
    }
}