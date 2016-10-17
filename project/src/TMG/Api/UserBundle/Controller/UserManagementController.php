<?php

namespace TMG\Api\UserBundle\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use TMG\Api\GlobalBundle\Controller\GlobalController;
use TMG\Api\LegacyBundle\Entity\User;
use TMG\Api\UserBundle\Entity\Repository\UserRolesRepository;
use TMG\Api\UserBundle\Entity\UserRoles;

/**
 * User Management controller.
 *
 * Class UserManagementController
 *
 * @package TMG\Api\UserBundle\Controller
 */
class UserManagementController extends GlobalController
{
    /** @var UserRolesRepository */
    private $userRolesRepo;

    protected $roleIndexView          = 'ApiUserBundle:UserManagement:user_roles_index.html.twig';

    protected $roleNewView            = 'ApiUserBundle:UserManagement:user-roles-new.html.twig';

    protected $roleEditView            = 'ApiUserBundle:UserManagement:user-roles-edit.html.twig';

    protected $roleFormClass          = 'TMG\Api\UserBundle\Form\UserRolesType';

    protected $roleEntityClass        = 'TMG\Api\UserBundle\Entity\UserRoles';

    public function initialize()
    {
        $this->userRolesRepo = $this->em->getRepository('ApiUserBundle:UserRoles');
    }

    /**
     * Temporary route to trigger a sync for a specific user.
     * This route should *NOT* be migrated to the new format.
     *
     * @param $id
     *
     * @return JsonResponse
     *
     * @throws NotFoundHttpException
     *
     * @deprecated Please do not rely on this method
     */
    public function migrateUserAction($id)
    {
        // Let's find the legacy user object.
        /** @var EntityManager $legacyEntityManager */
        $legacyEntityManager = $this->get('doctrine')->getManager('legacy');

        /** @var EntityRepository $legacyUserRepo */
        $legacyUserRepo = $legacyEntityManager->getRepository('TMGApiLegacyBundle:User');

        /** @var User $legacyUser */
        $legacyUser = $legacyUserRepo->findOneBy(['id' => $id]);

        // If we found one, let's set up the migrator command and sync up.
        // Default the $exitCode to 1, indicating a problem
        $exitCode = 1;

        if ($legacyUser) {
            $kernel = $this->get('kernel');
            $application = new Application($kernel);
            $application->setAutoExit(false);

            $input = new ArrayInput([
                'command' => 'migrate:user',
                'user' => $legacyUser->getEmail(),
            ]);

            // We don't need the output
            $output = new NullOutput();

            // Set the exit code to that of the command.
            $exitCode = $application->run($input, $output);
        }

        // Return the exit code so the client has something to look at
        return new JsonResponse(['result' => $exitCode]);
    }

    /**
     * Role Manager Index Action
     *
     * @return Response
     */
    public function roleIndexAction()
    {
        $userRoles = $this->userRolesRepo->findAll();

        $data = array(
            'user_roles' => $userRoles,
        );

        return $this->render(
            $this->roleIndexView,
            $this->viewData() + $data
        );
    }

    /**
     * Role Manager New Action
     *
     * @param Request $request
     *
     * @return Response
     */
    public function roleNewAction(Request $request)
    {
        /** @var UserRoles $entity */
        $entity  = new $this->roleEntityClass;
        $form = $this->createForm(new $this->roleFormClass, $entity);
        $flash = $this->container->get('session')->getFlashBag();

        if ($request->isMethod('post')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $this->em->persist($entity);
                $this->em->flush();

                $flash->add('success', 'Role created successfully!');

                $redirect = $this->container->get('router')->generate('admin_user_roles_edit', array(
                    'id' => $entity->getId(),
                ));

                return new RedirectResponse($redirect);
            }
        }

        $data = array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );

        return $this->render(
            $this->roleNewView,
            $this->viewData() + $data
        );
    }

    /**
     * Role Manager Edit Action
     *
     * @param Request $request
     * @param $id
     *
     * @return Response
     */
    public function roleEditAction(Request $request, $id)
    {
        $entity = $this->userRolesRepo->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find role entity.');
        }

        $form = $this->createForm(new $this->roleFormClass, $entity);
        $deleteForm = $this->createDeleteForm($id);


        $data = array(
            'entity'      => $entity,
            'form'        => $form->createView(),
            'delete_form' => $deleteForm->createView(),
        );

        return $this->render(
            $this->roleEditView,
            $this->viewData() + $data
        );
    }

    /**
     * Role Manager Update Action
     *
     * @param Request $request
     * @param $id
     *
     * @return RedirectResponse|Response
     */
    public function roleUpdateAction(Request $request, $id)
    {
        $flash = $this->container->get('session')->getFlashBag();
        $entity = $this->userRolesRepo->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find role entity.');
        }

        $form = $this->createForm(new $this->roleFormClass, $entity);
        $deleteForm = $this->createDeleteForm($id);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->em->persist($entity);
            $this->em->flush();

            $flash->add('success', 'Role Updated successfully!');
            $redirect = $this->container->get('router')->generate('admin_user_roles_edit', array(
                'id' => $entity->getId(),
            ));

            return new RedirectResponse($redirect);
        }

        $data = array(
            'entity'      => $entity,
            'form'        => $form->createView(),
            'delete_form' => $deleteForm->createView(),
        );

        return $this->render(
            $this->roleEditView,
            $this->viewData() + $data
        );
    }

    /**
     * Role Manager Delete Action
     *
     * @param Request $request
     * @param $id
     *
     * @return RedirectResponse
     */
    public function roleDeleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);
        $flash = $this->container->get('session')->getFlashBag();

        if ($form->isValid()) {
            $entity = $this->userRolesRepo->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Role entity.');
            }

            $this->em->remove($entity);
            $this->em->flush();

            $flash->add('success', 'Role deleted successfully!');
        }

        $redirect = $this->container->get('router')->generate('admin_user_roles');

        return new RedirectResponse($redirect);
    }
}
