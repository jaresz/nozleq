<?php
namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Reservation;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use AppBundle\Traits as AppTraits;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Reservation controller.
 *
 * @Route("/clerk/reservation")
 * @Security("has_role('ROLE_CLERK')")
 */
class ClerkReservationController extends Controller
{

    const ENTITY = 'AppBundle:Reservation';

    const ROUTE_PREFIX = 'clerk_reservation';

    const ENTITIES_TITLE = 'Rezerwacje';

    const ENTITY_TITLE = 'Rezerwacja';
    
    use AppTraits\ControllerRouteTrait;

    public function __construct()
    {
        $this->setRouteNames();
    }

    /**
     * Lists all Reservation entities.
     *
     * @Route("/", name="clerk_reservation_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        dump($this->getUser());
        
        $reservations = $em->getRepository('AppBundle:Reservation')->findAll();
        
        return $this->render('reservation/index.html.twig', array(
            'reservations' => $reservations,
            'routeNames' => self::getRouteNames(),
            'title' => self::ENTITIES_TITLE
        ));
    }

    
    /**
     * Finds and displays a Reservation entity.
     *
     * @Route(":{id}", name="clerk_reservation_show", requirements={
     * "id": "\d+"
     * })
     * @Method("GET")
     */
    public function showAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $reservation = $em->getRepository('AppBundle:Reservation')->findOneBy(['id'=>$id]);
 
        
        if ($reservation)
        {
            $deleteForm = $this->createDeleteForm($reservation);
            
            return $this->render('reservation/show.html.twig', array(
                'reservation' => $reservation,
                'delete_form' => $deleteForm->createView(),
                'routeNames' => self::getRouteNames(),
                'title' => self::ENTITY_TITLE,
                'room'=> $reservation->getResource()
            ));
        }
    }

    /**
     * Displays a form to edit an existing Reservation entity.
     *
     * @Route(":{id}/edit", name="clerk_reservation_edit", requirements={
     * "id": "\d+"
     * })
     * @Method({"GET", "POST"})
     * 
     */
    public function editAction(Request $request, Reservation $reservation)
    {

        $editForm = $this->createForm('AppBundle\Form\Type\ReservationType', $reservation);
        $editForm->handleRequest($request);
        
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($reservation);
            $em->flush();
            $this->addFlash('success', 'Zmiany zostały zapisane.');
            return $this->redirectToRoute('clerk_reservation_index');
        }
        
        return $this->render('BaseCRUD/edit.html.twig', array(
            'reservation' => $reservation,
            'form' => $editForm->createView(),
            'routeNames' => self::getRouteNames(),
            'title' => self::ENTITY_TITLE
        ));
    }


    /**
     * Deletes a Reservation entity.
     *
     * @Route(":{id}", name="clerk_reservation_delete", requirements={
     * "id": "\d+"
     * })
     * @Method("DELETE")
     * 
     */
    public function deleteAction(Request $request, Reservation $reservation)
    {  
        
        $form = $this->createDeleteForm($reservation);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            if ($reservation->getCreatedByUser() == $this->getUser()) // tylko dla właściciela
            if (md5($reservation->getRapas()) == $request->request->get('form')['harapas']) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($reservation);
                $em->flush();
                $this->addFlash('success', 'Obiekt został usunięty.');
            }
        }
        
        return $this->redirectToRoute('clerk_reservation_index');
    }

    /**
     * Creates a form to delete a Reservation entity.
     *
     * @param Reservation $reservation
     *            The Reservation entity
     *            
     * @return \Symfony\Component\Form\Form The form
     *        
     */
    private function createDeleteForm(Reservation $reservation)
    {
     
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('clerk_reservation_delete', array(
            'id' => $reservation->getId()
        )))
            ->setMethod('DELETE')
            ->add('harapas', HiddenType::class, array(
            'data' => md5($reservation->getRapas())
        ))
            ->getForm();
    }
}
