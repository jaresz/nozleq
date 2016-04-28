<?php
namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Room;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use AppBundle\Traits as AppTraits;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Reservation;

/**
 * Room controller.
 * "admin" w ścieżce jest istotne - bazują na nim uprawnienia
 * @Route("admin/room")
 * @Security("has_role('ROLE_ADMIN')")
 * Dodatkowo cały kontroler jest tylko dla administratorów.
 */
class RoomController extends Controller
{

    const ENTITY = 'AppBundle:Room';

    const ROUTE_PREFIX = 'room';

    const ENTITIES_TITLE = 'Pokoje';

    const ENTITY_TITLE = 'Pokój';
    
    use AppTraits\ControllerRouteTrait;

    public function __construct()
    {
        $this->setRouteNames();
    }

    /**
     * Lists all Room entities.
     *
     * @Route("/", name="room_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $rooms = $em->getRepository('AppBundle:Room')->findAll();
        
        return $this->render('room/index.html.twig', array(
            'rooms' => $rooms,
            'routeNames' => self::getRouteNames(),
            'title' => self::ENTITIES_TITLE
        ));
    }

    /**
     * Lists reserved Rooms.
     *
     * @Route("/reservations_on", defaults={"day" = 0 }, name="reserved_room_index")
     * @Route("/reservations_on/{day}", name="reserved_room_on_index")
     * @Method("GET")
     */
    public function reservedIndexAction(Request $request, $day)
    {
        $em = $this->getDoctrine()->getManager();
        
        if (! $day)
            $day = new \DateTime();
        
        $rooms = $em->getRepository('AppBundle:Room')->getRoomsReservedOn($day);
        
        return $this->render('reservation/index.html.twig', array(
            'reservations' => $rooms,
            'routeNames' => self::getRouteNames(),
            'title' => self::ENTITIES_TITLE
        ));
    }


    /**
     * Creates a new Room entity.
     *
     * @Route("/new", name="room_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function newAction(Request $request)
    {
        $room = new Room();
        $form = $this->createForm('AppBundle\Form\Type\RoomType', $room);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $room->setCreatedByUser($this->getUser());
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($room);
            $em->flush();
            
            return $this->redirectToRoute('room_show', array(
                'id' => $room->getId()
            ));
        }
        
        return $this->render('BaseCRUD/create.html.twig', array(
            'room' => $room,
            'form' => $form->createView(),
            'routeNames' => self::getRouteNames(),
            'title' => self::ENTITY_TITLE
        ));
    }

    /**
     * Finds and displays a Room entity.
     *
     * @Route("/{id}", name="room_show", requirements={
     * "id": "\d+"
     * })
     * @Method("GET")
     */
    public function showAction(Room $room)
    {
        $deleteForm = $this->createDeleteForm($room);
        
        return $this->render('room/show.html.twig', array(
            'room' => $room,
            'delete_form' => $deleteForm->createView(),
            'routeNames' => self::getRouteNames(),
            'title' => self::ENTITIES_TITLE
        ));
    }

    /**
     * Displays a form to edit an existing Room entity.
     *
     * @Route("/{id}/edit", name="room_edit", requirements={
     * "id": "\d+"
     * })
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function editAction(Request $request, Room $room)
    {
        $editForm = $this->createForm('AppBundle\Form\Type\RoomType', $room);
        $editForm->handleRequest($request);
        
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($room);
            $em->flush();
            $this->addFlash('success', 'Zmiany zostały zapisane.');
            return $this->redirectToRoute('room_index');
        }
        
        return $this->render('BaseCRUD/edit.html.twig', array(
            'room' => $room,
            'form' => $editForm->createView(),
            'routeNames' => self::getRouteNames(),
            'title' => self::ENTITY_TITLE
        ));
    }

    /**
     * Deletes a Room entity.
     *
     * @Route("/{id}/delete", name="room_delete", requirements={
     * "id": "\d+"
     * })
     * @Method("DELETE")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request, Room $room)
    {
        $form = $this->createDeleteForm($room);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            if (md5($room->getRapas()) == $request->request->get('form')['harapas']) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($room);
                $em->flush();
                $this->addFlash('success', 'Obiekt został usunięty.');
            }
        }
        
        return $this->redirectToRoute('room_index');
    }

    /**
     * Creates a form to delete a Room entity.
     *
     * @param Room $room
     *            The Room entity
     *            
     * @return \Symfony\Component\Form\Form The form
     *        
     */
    private function createDeleteForm(Room $room)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('room_delete', array(
            'id' => $room->getId()
        )))
            ->setMethod('DELETE')
            ->add('harapas', HiddenType::class, array(
            'data' => md5($room->getRapas())
        ))
            ->getForm();
    }
}
