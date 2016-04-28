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
use AppBundle\Entity\BookingButterfly;

/**
 * Obługa wolnych pokoi
 *
 * @Route("/")
 *
 */
class VacancyController extends Controller
{

    const ENTITY = 'AppBundle:Room';

    const ROUTE_PREFIX = 'vacancy';

    const ENTITIES_TITLE = 'Wolne Pokoje';

    const ENTITY_TITLE = 'Wolny Pokój';
    
    use AppTraits\ControllerRouteTrait;

    public function __construct()
    {
        $this->setRouteNames();
    }

    /**
     * Lists reserved Rooms.
     * 
     * @Route("/", defaults={"day" = 0 }, name="vacancy_index")
     * @Route("/on/{day}", name="vacancy_on_index")
     * @Method({"GET"})
     */
    public function indexAction(Request $request, $day)
    {
        $em = $this->getDoctrine()->getManager();
        
        $filter = new \AppBundle\Entity\VacancyFilter();
        if ($day)
            $filter->day = new \DateTime($day);
        
        $filterForm = $this->createForm('AppBundle\Form\Type\VacancyFilterType', $filter);
        $filterForm->handleRequest($request);
        
        if ($filterForm->isValid())
            $day = $filter->day;
        else 
            if (! $day)
                $day = $filter->day;
        
        $rooms = $em->getRepository('AppBundle:Room')->getFreeRooms($day);
                
            
        return $this->render('vacancy/index.html.twig', array(
            'form' => $filterForm->createView(),
            'rooms' => $rooms,
            'routeNames' => self::getRouteNames(),
            'title' => self::ENTITIES_TITLE,
            'day' => $day
        ));
    }

    /**
     * Rezerwowanie.
     *
     * @Route("rezr/{id}/book_on/{day}", name="book_on", requirements={
     * "id": "\d+"
     * })
     * @Method({"GET", "POST"})
     * Security("has_role('ROLE_USER')")
     */
    public function bookAction(Request $request, Room $room, $id, $day)
    {
        $bb = new BookingButterfly($id, $day, $this->getUser()->getName() . 's reservation');
        
        $editForm = $this->createForm('AppBundle\Form\Type\BookingButterflyType', $bb);
        $editForm->handleRequest($request);
        
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            
            $daydt = new \DateTime($day);
            $resMen = $this->get('app.reservation_manager');
            $booked = $resMen->makeReservation($room, $daydt, $this->getUser(), $bb->getName());
            
            if ($booked)
                $this->addFlash('success', "Rezerwacja została utworzona. Numer rezerwacji: ".$booked->getId()." Aby zachować rezerwację zrób przeelew na nasze konto w terminie do ".$booked->getExpires()->format('Y-m-d H:i:s').". \n Opłacić lub usunąć rezerwację możesz z poziomu podstrony Moje rezerwacje." );
            else
                $this->addFlash('warning', "Niestety, nie udało się zrobić rezerwacji.\nSpróbuj wybrać inny zasób lub datę.");
            
            return $this->redirectToRoute('vacancy_on_index', ['day'=>$day]);
        }
        
        return $this->render('reservation/make.html.twig', array(
            'room' => $room,
            'form' => $editForm->createView(),
            'routeNames' => self::getRouteNames(),
            'title' => 'Rezerwowanie pokoju',
            'reservation_day' => $day
        ));
    }
}
