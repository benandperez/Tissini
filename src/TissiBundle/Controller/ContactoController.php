<?php

namespace TissiBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use TissiBundle\Entity\Contacto;
use Symfony\Component\HttpFoundation\Request;


class ContactoController extends Controller
{
	public function createAction(Request $request)
	{

		$content = $request->getContent();
		$contactoData = json_decode($content, true);
		$contacto = null;
		$isUpdate = false;
		$id = null;
		if (isset($contactoData["id"])) {
			$id = $contactoData["id"];
			# code...
		}
		if($id != null){			
			$repository = $this->getDoctrine()->getRepository(Contacto::class);
			$contacto = $repository->findOneById($id);  
			$isUpdate = false;
		}else{
			$contacto = new Contacto();
		}
		$nombres = $contactoData["nombres"];		
		$apellidos = $contactoData["apellidos"];
		$correo = $contactoData["correo"];		
		$telefono = $contactoData["telefono"];
		$tipo_de_cliente = $contactoData["tipo_de_cliente"];
		$comentarios = $contactoData["comentarios"];
	   	
	   	$contacto->setNombre($nombres);
		$contacto->setApellido($apellidos);
		$contacto->setTelefono($telefono);
		$contacto->setCorreo($correo);
		$contacto->setTipoDeCliente($tipo_de_cliente);
		$contacto->setComentarios($comentarios);

	    $em = $this->getDoctrine()->getManager();
	    if(!$isUpdate){
	    	$em->persist($contacto);	
	    }	    
	    $em->flush();
	    $contactoData["id"] = $contacto->getId();
		
	    return new JsonResponse($contactoData);
	    //return new Response('Created product id mmm');
	}

	public function listAction(Request $request)
	{		
		$repository = $this->getDoctrine()->getRepository(Contacto::class);
		$contactos = $repository->findAll();
		$arrayContactos = array();

		foreach ($contactos as $key => $value) {
			$contacto = array();
			$contacto["id"] = $value->getId();
			$contacto["nombre"] = $value->getNombre();
			$contacto["apellido"] = $value->getApellido();
			$contacto["telefono"] = $value->getTelefono();
			$contacto["correo"] = $value->getCorreo();
			$contacto["tipo_de_cliente"] = $value->getTipoDeCliente();
			$contacto["comentarios"] = $value->getComentarios();
			$arrayContactos[] = $contacto;
		}
	    return new JsonResponse($arrayContactos);
	}

	public function borrarAction($id) {
 
 
        //Entity Manager
        $em = $this->getDoctrine()->getEntityManager();
        $posts = $em->getRepository("TissiBundle:Contacto");
 
        $post = $posts->find($id);
        $em->remove($post);
        $flush=$em->flush();
 
        if ($flush == null) {
            echo "Contacto se ha borrado correctamente";
        } else {
            echo "El post no se ha borrado";
        }
 
 
        die();
    }

	
}