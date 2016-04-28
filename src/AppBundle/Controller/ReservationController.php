<?php
namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Reservation;
use AppBundle\Entity\Payment;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use AppBundle\Traits as AppTraits;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Reservation controller.
 *
 * @Route("/reservation")
 * @Security("has_role('ROLE_USER')")
 */
class ReservationController extends Controller
{

    const ENTITY = 'AppBundle:Reservation';

    const ROUTE_PREFIX = 'reservation';

    const ENTITIES_TITLE = 'Moje Rezerwacje';

    const ENTITY_TITLE = 'Moja Rezerwacja';
    
    use AppTraits\ControllerRouteTrait;

    public function __construct()
    {
        $this->setRouteNames();
    }

    /**
     * Lists all Reservation entities.
     *
     * @Route("/", name="reservation_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $reservations = $em->getRepository('AppBundle:Reservation')->findByCreatedByUser($this->getUser());
        
        return $this->render('reservation/index.html.twig', array(
            'reservations' => $reservations,
            'routeNames' => self::getRouteNames(),
            'title' => self::ENTITIES_TITLE
        ));
    }

    /**
     * Finds and displays a Reservation entity.
     *
     * @Route(":{id}", name="reservation_show", requirements={
     * "id": "\d+"
     * })
     * @Method("GET")
     */
    public function showAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $reservation = $em->getRepository('AppBundle:Reservation')->findOneBy([
            'id' => $id,
            'createdByUser' => $this->getUser()
        ]);
        
        if ($reservation) {
            $deleteForm = $this->createDeleteForm($reservation);
            
            return $this->render('reservation/show.html.twig', array(
                'reservation' => $reservation,
                'delete_form' => $deleteForm->createView(),
                'routeNames' => self::getRouteNames(),
                'title' => self::ENTITY_TITLE,
                'room' => $reservation->getResource()
            ));
        }
    }

    /**
     * Finds and displays a Reservation entity.
     *
     * @Route(":{id}/pay", name="reservation_pay", requirements={
     * "id": "\d+"
     * })
     * @Method({"GET", "POST"})
     */
    public function paymentMockupAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $reservation = $em->getRepository('AppBundle:Reservation')->findOneBy([
            'id' => $id,
            'createdByUser' => $this->getUser()
        ]);
        
        if ($reservation) {
            $payment = new Payment();
            $payment->setReservation($reservation);
            
            $payForm = $this->createMockupPaymentForm($payment);
            $payForm->handleRequest($request);
            
            if ($payForm->isSubmitted()) {
                // w zaślepce nie spr już poprawności np. $payForm->isValid()
                $kwo = intval($payForm->getData()['amount']);
                if ($kwo) {
                    $payment->setName("Wirtualna płatność z zaślepki");
                    $payment->setAmount($kwo);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($payment);
                    $em->flush();
                }
                return $this->redirectToRoute('reservation_show', ['id'=>$id]);
            }
            
            return $this->render('reservation/pay.html.twig', array(
                'reservation' => $reservation,
                'payment_form' => $payForm->createView(),
                'routeNames' => self::getRouteNames(),
                'title' => self::ENTITY_TITLE,
                'room' => $reservation->getResource()
            ));
        }
    }

    /**
     * Displays a form to edit an existing Reservation entity.
     *
     * @Route(":{id}/edit", name="reservation_edit", requirements={
     * "id": "\d+"
     * })
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Reservation $reservation)
    {
        // tylko dla właściciela:
        if ($reservation->getCreatedByUser() != $this->getUser())
            throw new AccessDeniedException('Brak dostępu.');
        
        $editForm = $this->createForm('AppBundle\Form\Type\ReservationType', $reservation);
        $editForm->handleRequest($request);
        
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($reservation);
            $em->flush();
            $this->addFlash('success', 'Zmiany zostały zapisane.');
            return $this->redirectToRoute('reservation_index');
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
     * @Route(":{id}", name="reservation_delete", requirements={
     * "id": "\d+"
     * })
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Reservation $reservation)
    {
        // tylko dla właściciela:
        if ($reservation->getCreatedByUser() != $this->getUser())
            throw new AccessDeniedException('Brak dostępu.');
        
        $form = $this->createDeleteForm($reservation);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            if ($reservation->getCreatedByUser() == $this->getUser()) // tylko dla właściciela
                if (md5($reservation->getRapas()) == $request->request->get('form')['harapas']) {
                    $em = $this->getDoctrine()->getManager();
                    $em->remove($reservation);
                    $em->flush();
                    $this->addFlash('success', 'Rezerwacja została usunięta.');
                }
        }
        
        return $this->redirectToRoute('reservation_index');
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
        // tylko dla właściciela:
        if ($reservation->getCreatedByUser() != $this->getUser())
            throw new AccessDeniedException('Brak dostępu.');
        
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('reservation_delete', array(
            'id' => $reservation->getId()
        )))
            ->setMethod('DELETE')
            ->add('harapas', HiddenType::class, array(
            'data' => md5($reservation->getRapas())
        ))
            ->getForm();
    }

    private function createMockupPaymentForm(Payment $payment)
    {
        return $this->createFormBuilder()
            ->add('amount', IntegerType::class, array(
            'data' => 100
        ))
            ->add('name', HiddenType::class, array(
            'data' => $payment->getId()
        ))
            ->getForm();
    }
}
