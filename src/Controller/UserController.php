<?php


namespace App\Controller;

use App\Entity\User;
use FOS\UserBundle\Util\UserManipulator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller used to manage users.
 *
 * @author Andrea Putzu <mister.tzu@gmail.com>
 */
class UserController extends AbstractController
{
    /**
     * Finds and displays a Restaurant entity.
     *
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Route("/admin/users", name="admin_users", methods={"GET"})
     */
    public function showAction() {
        // This security check can also be performed
        // using an annotation: @Security("is_granted('show', post)")
        //$this->denyAccessUnlessGranted('show', $post, 'Posts can only be shown to their authors.');
        
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        //var_dump($users); die();
        return $this->render('admin/user/index.html.twig', ['users' => $users]);
    }

    /**
     * Add admin role from user.
     *
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Route("/admin/user/add_role/{id}", requirements={"id": "\d+"}, name="admin_user_add_role", methods={"GET"})
     */
    public function addRoleAction(User $user) {
        
        $user->addRole("ROLE_ADMIN");
        $userManager = $this->container->get('fos_user.user_manager');
        $userManager->updateUser($user);
        return $this->redirectToRoute('admin_users');
    }

    /**
     * Remove admin role from user.
     *
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Route("/admin/user/remove_role/{id}", requirements={"id": "\d+"}, name="admin_user_remove_role", methods={"GET"})
     */
    public function removeRoleAction(User $user) {
        
        $user->removeRole("ROLE_ADMIN");
        $userManager = $this->container->get('fos_user.user_manager');
        $userManager->updateUser($user);
        return $this->redirectToRoute('admin_users');
    }

    
    /**
     * Disable user.
     *
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Route("/admin/user/disable/{id}", requirements={"id": "\d+"}, name="admin_user_disable", methods={"GET"})
     */
    public function disableAction(User $user) {
        
        $user->setEnabled(false);
        $userManager = $this->container->get('fos_user.user_manager');
        $userManager->updateUser($user);
        return $this->redirectToRoute('admin_users');
    }
    
    /**
     * Enable user.
     *
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Route("/admin/user/enable/{id}", requirements={"id": "\d+"}, name="admin_user_enable", methods={"GET"})
     */
    public function enableAction(User $user) {
        
        $user->setEnabled(true);
        $userManager = $this->container->get('fos_user.user_manager');
        $userManager->updateUser($user);
        return $this->redirectToRoute('admin_users');
    }
}
