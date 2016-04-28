<?php
namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\Type\RoomType;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\Annotations\RequestParam;

use AppBundle\Entity\Room;
use Symfony\Component\HttpFoundation\Response;
/**
 * @RouteResource("room")
 */
class ApiRoomController extends FOSRestController implements ClassResourceInterface
{

    const ENTITY = 'AppBundle:Room';

    public function cgetAction()
    {
        // w security.yml mamy ustawioną konieczność logowania się, ale tutaj przeprowadzamy kontrolę uprawnień
        if (! $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }
        
        $em = $this->getDoctrine()->getManager();
        $rooms = $em->getRepository('AppBundle:Room')->cgetA();
        
        $view = $this->view($rooms, 200)->setTemplate("api/notforman.html.twig");
        
        return $this->handleView($view);
    }
    
    public function getAction($id)
    {
        // w security.yml mamy ustawioną konieczność logowania się, ale tutaj przeprowadzamy kontrolę uprawnień
        if (! $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }
    
        $em = $this->getDoctrine()->getManager();
        $room = $em->getRepository('AppBundle:Room')->getA($id);
        $view = $this->view($room, 200)->setTemplate("api/notforman.html.twig");
    
        return $this->handleView($view);
    }

    public function newAction(Request $request)
    {
        if (! $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }
        return $this->createForm(RoomType::class);
    }
    /**
     * Dodawanie nowego pokoju
     * @param Request $request
     */
    public function postAction(Request $request)
    {
        if (! $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }
        $room = new Room();
        $form = $this->createForm('AppBundle\Form\Type\RoomType', $room);
        $form->handleRequest($request);
        // walidacja, jak z tradycyjnego formularza
        if ($form->isSubmitted() && $form->isValid()) {
            $room->setCreatedByUser($this->getUser());
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($room);
            $em->flush();
            
            $view = $this->view(['status'=>'OK', 'id'=>$room->getId()], 200);
            return $this->handleView($view);
        }
        
        $view = $this->view($form->getErrors(), 409);
        return $this->handleView($view);
    }
    
    public function deleteAction(Request $request, Room $room)
    {
        if (! $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($room);
        $em->flush();

         $view = $this->view(['status'=>'OK'], 200);
         return $this->handleView($view);
    }
}
