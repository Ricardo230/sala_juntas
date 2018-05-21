<?php
namespace AppBundle\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use AppBundle\Entity\User;
use AppBundle\Entity\Sala;
use AppBundle\Entity\ApartadoSala;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query\ResultSetMapping;


class AdminController extends BaseAdminController
{

  /**
  * @Route("/", name="easyadmin")
  * @param Request $request
  * @return RedirectResponse|Response
  */
  public function indexAction(Request $request)
  {
    /*$this->initialize($request);
    if (null === $request->query->get('entity')) {
      return $this->render('AppBundle:Default:dashboard.html.twig');
    }*/

    $this->checkPermissions($request);
    //$this->makeFilters($request);
    return parent::indexAction($request);
  }

  public function editAction(){
    if(in_array('ROLE_ADMIN', $this->getUser()->getRoles()) || in_array('ROLE_SUPER_ADMIN', $this->getUser()->getRoles())){
      //Is ADMIN !!! 
      return parent::editAction();
    }else{
      return parent::listAction();
      //echo "es un usuario normal";
    }
  }

  public function newAction(){
    if(in_array('ROLE_ADMIN', $this->getUser()->getRoles()) || in_array('ROLE_SUPER_ADMIN', $this->getUser()->getRoles())){
      //Is ADMIN !!! 
      return parent::newAction();
    }else{
      return parent::listAction();
      //echo "es un usuario normal";
    }
  }

  protected function persistApartadoSalaEntity($entity){
    $entity->setUsuario($this->getUser());
    parent::persistEntity($entity);
  }

  private function checkPermissions(Request $request){
    $easyAdmin = $request->attributes->get('easyadmin');
    if (isset($easyAdmin['entity']['require_permission'])) {
      $requiredPermission = $easyAdmin['entity']['require_permission'];
    } else {
      $view = $easyAdmin['view'];
      $entity = $easyAdmin['entity']['name'];
      $requiredPermission = "ROLE_USER";
      # Or any other default strategy
    }
    $this->denyAccessUnlessGranted(
      $requiredPermission,
      null,
      $requiredPermission.' permission required'
    );
  }

	/** 
    * @Route("/sala/ajaxSalas") 
  */
  public function ajaxSalaAction(Request $request) {
    $id_sala = intval($request->request->get('id_sala'));
    $repository = $this->getDoctrine()->getRepository('AppBundle:Sala');
    // createQueryBuilder() automatically selects FROM PruebaBundle:Sala
    // and aliases it to "e"
    $query = $repository->createQueryBuilder('e')
      ->select('e.id','e.nombre', 'e.horaInicio', 'e.horaFin')
      ->where('e.id = :id_sala')
      ->setParameter('id_sala', $request->request->get('id_sala'))
      ->getQuery();

    $datos_sala = $query->getResult();
    $jsonData = array();  
    $idx = 0; 
    foreach($datos_sala as $sala) { 
      $id_sala = $sala['id'];
      $hrInicio = $sala['horaInicio']->format('H:i');
      $hrFin = $sala['horaFin']->format('H:i');
      $temp = array(
        'id' => $sala['id'],
        'hora_inicio' => $sala['horaInicio']->format('H:i'),
        'hora_fin' => $sala['horaFin']->format('H:i'), 
      );
      $jsonData[$idx++] = $temp;  
    }
    return new JsonResponse($jsonData);
  }

  /** 
   	* @Route("/sala/ajaxReservaciones") 
	*/
	public function ajaxAction(Request $request) { 
		/* $_GET parameters
        $id = $request->query->get('id');*/
		//$_POST parameters
		$id_sala = intval($request->request->get('id_sala'));
    //***$repository = $this->getDoctrine()->getRepository('AppBundle:Sala');
    // createQueryBuilder() automatically selects FROM PruebaBundle:Sala
    // and aliases it to "e"
    /***$query = $repository->createQueryBuilder('e')
      ->select('e.id','e.nombre', 'e.horaInicio', 'e.horaFin')
      ->where('e.id = :id_sala')
      ->setParameter('id_sala', $request->request->get('id_sala'))
      ->getQuery();

    $datos_sala = $query->getResult();

    foreach($datos_sala as $sala) { 
      $id_sala = $sala['id'];
      $hrInicio = $sala['horaInicio']->format('H:i');
      $hrFin = $sala['horaFin']->format('H:i');
      /***$temp = array(
        'id' => $sala['id'],
        'hora_inicio' => $sala['horaInicio']->format('H:i'),
        'hora_fin' => $sala['horaFin']->format('H:i'), 
      );*/   
      //***$jsonData[$idx++] = $temp;  
    //***}
    //Obtiene las reservaciones de esa sala para la fecha enviada
    $fecha = $request->request->get('fecha');
    $repositoryR = $this->getDoctrine()->getRepository('AppBundle:ApartadoSala');
    $query2 = $repositoryR->createQueryBuilder('r')
      ->select('r.id','r.fecha', 'r.hora')
      ->where('r.sala = :id_sala')
      ->andWhere("r.fecha = :fecha")
      ->setParameter('fecha', $fecha)
      ->setParameter('id_sala', $id_sala)
      ->getQuery();
    /*$query2 = $repositoryR->createQueryBuilder('r')
      ->select('r.id','r.fecha', 'r.hora', 's.horaInicio', 's.horaFin')
      ->innerJoin('r.sala', 's')
      ->where('r.fecha = :fecha')
      ->andWhere("r.sala = :id_sala")
      ->setParameter('fecha', $fecha)
      ->setParameter('id_sala', $request->request->get('id_sala'))
      ->getQuery();*/

    $datos_reservacion = $query2->getResult();
    
    $jsonData = array();  
    
    $idx = 0; 

    foreach($datos_reservacion as $reservacion) {       
      $temp = array(
        'id' => $reservacion['id'],
        'fecha' => $reservacion['fecha']->format('Y-m-d'),
        'hora' => $reservacion['hora']->format('H'),
      );   
      $jsonData[$idx++] = $temp;  
    }
    return new JsonResponse($jsonData);
   		/*$salas = $this->getDoctrine() 
      	->getRepository('AppBundle:Sala') 
      	->findAll();
   		//if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {  
    		$jsonData = array();  
      		$idx = 0; 
      		echo "Pasa"; 
      		foreach($salas as $sala) {  
        		$temp = array(
            		'id' => $sala->getId(),
            		'name' => $sala->getNombre(),  
         		);   
         		$jsonData[$idx++] = $temp;  
      		} 
      		return new JsonResponse($jsonData); 
   		//}
   		/* else { 
      		return $this->render('student/ajax.html.twig'); 
   		}*/ 
	}  
}
