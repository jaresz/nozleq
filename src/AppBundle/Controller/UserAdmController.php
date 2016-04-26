<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use AppBundle\Form\Type\UserType;
use AppBundle\Form\Type\UserEditType;
use AppBundle\Form\Type\UserChangePassType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use AppBundle\Traits as AppTraits;

/**
 * @Route("/admin/user")
 * @Security("has_role('ROLE_ADMIN')")
 */
class UserAdmController extends Controller
{

    const ROUTE_PREFIX = 'admin_user';

    const ENTITIES_TITLE = 'Użytkownicy';

    const ENTITY_TITLE = 'Użytkownik';
    
    use AppTraits\ControllerRouteTrait;

    public function __construct()
    {
        $this->setRouteNames();
    }

    /**
     * @Route("/", name="admin_user_index")
     */
    public function indexAction(Request $request)
    {
        $userManager = $this->get('fos_user.user_manager');
        $users = $userManager->findUsers();
        
        return $this->render('UserAdm/index.html.twig', array(
            'pagination' => $users,
            'routeNames' => self::getRouteNames(),
            'title' => self::ENTITIES_TITLE
        ));
    }

    /**
     * @Route("/new", name="admin_user_new")
     */
    public function newAction(Request $request)
    {
        /* Tworzenie nowego użytkownika */
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->createUser();
        
        $form = $this->createForm(UserType::class, $user, [
            'action' => $this->generateUrl('admin_user_new')
        ]);
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            
            $user->setEnabled(true);
            $userManager->updateUser($user);
            
            $this->addFlash('success', 'Konto użytkownika zostało utworzone.');
            
            return $this->redirect($this->generateUrl('admin_user_index'));
        }
        
        return $this->render('BaseCRUD/create.html.twig', array(
            'form' => $form->createView(),
            'title' => self::ENTITY_TITLE . ' - Nowy',
            'routeNames' => self::getRouteNames()
        ));
    }

    /**
     * Finds and displays a Room entity.
     *
     * @Route(":{id}", name="admin_user_show", requirements={
     * "id": "\d+"
     * })
     * @Method("GET")
     */
    public function showAction(Request $request, $id)
    {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserBy(array(
            'id' => $id
        ));
        
        $deleteForm = $this->createDeleteForm($user); // formularz kasowania
        
        return $this->render('UserAdm/show.html.twig', array(
            'user' => $user,
            'delete_form' => $deleteForm->createView(),
            'routeNames' => self::getRouteNames(),
            'title' => self::ENTITIES_TITLE
        ));
    }

    /**
     * @Route(":{id}/edit", name="admin_user_edit", requirements={
     * "id": "\d+"
     * })
     */
    public function editAction(Request $request, $id)
    {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserBy(['id' => $id]);
        if (! is_object($user) || ! $user instanceof UserInterface)
            throw new AccessDeniedException('Brak dostępu.');
        
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');
        
        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_INITIALIZE, $event);
        
        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }
        
        $form = $this->createForm(UserEditType::class, $user);        
        $form->handleRequest($request);
        
        if ($form->isValid()) {            
            return $this->editSave($request, $dispatcher, $userManager, $user, $form);
        }
        
        return $this->render('UserAdm/edit.html.twig', array(
            'form' => $form->createView(),
            'title' => self::ENTITY_TITLE . ' - Edycja',
            'routeNames' => self::getRouteNames()
        ));
    }
    
    protected function editSave(Request $request, \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher, $userManager, $user, $form)
    {
        $event = new FormEvent($form, $request);
        $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_SUCCESS, $event);
        $userManager->updateUser($user);
        
        if (null === $response = $event->getResponse()) {
            $url = $this->generateUrl('admin_user_index');
            $response = new RedirectResponse($url);
        }
        
        $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_COMPLETED, new FilterUserResponseEvent($user, $request, $response));
        return $response;
    }

    /**
     * @Route(":{id}/changepass", name="admin_user_changepass", requirements={
     * "id": "\d+"
     * })
     */
    public function changePassAction(Request $request, $id)
    {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserBy(['id' => $id]);
        if (! is_object($user) || ! $user instanceof UserInterface)
            throw new AccessDeniedException('Brak dostępu.');
        
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');
        
        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_INITIALIZE, $event);
        
        if (null !== $event->getResponse())
            return $event->getResponse();
        
        $form = $this->createForm(UserChangePassType::class, $user);
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            return $this->changePass($request, $id, $dispatcher, $userManager, $form, $user);
        }
        
        return $this->render('BaseCRUD/edit.html.twig', array(
            'form' => $form->createView(),
            'title' => self::ENTITY_TITLE . ' - Edycja',
            'routeNames' => self::getRouteNames()
        ));
    }

    protected function changePass(Request $request, $id, \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher, $userManager, $form, $user)
    {
        $event = new FormEvent($form, $request);
        $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_SUCCESS, $event);
        
        $userManager->updateUser($user);
        
        if (null === $response = $event->getResponse()) {
            $url = $this->generateUrl('admin_user_index');
            $response = new RedirectResponse($url);
        }
        
        $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_COMPLETED, new FilterUserResponseEvent($user, $request, $response));
        
        return $response;
    }

    /**
     * @Route(":{id}/delete", name="admin_user_delete", requirements={
     * "id": "\d+"
     * })
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request, $id)
    {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserBy([
            'id' => $id
        ]);
        
        if (! $user) {
            throw $this->createNotFoundException('Nie znaleziono obiektu do unicestwienia.');
        }
        
        if ($user) {
            if (md5($user->getRapas()) == $request->request->get('form')['harapas']) {
                $userManager->deleteUser($user);
                $this->addFlash('success', 'Użytkownik został usunięty.');
            }
        }
        
        return $this->redirect($this->generateUrl('admin_user_index'));
    }

    private function createDeleteForm($user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl(self::$routeNames['delete'], array(
            'id' => $user->getId()
        )))
            ->setMethod('DELETE')
            ->add('harapas', HiddenType::class, array(
            'data' => md5($user->getRapas())
        ))
            ->getForm();
    }
}
