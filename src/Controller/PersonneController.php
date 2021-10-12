<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use App\Entity\Personne;
use App\Form\Type\PersonneType;
class PersonneController extends AbstractController
{
    /** @var EntityManagerInterface */
    private $entityManager;
    /** @var \Doctrine\Common\Persistence\ObjectRepository */
    private $personneRepository;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->personneRepository = $entityManager->getRepository(Personne::class);
    }
    /**
     * @Route("/", name="personne")
     */
    public function index(): Response
    {
        $personnes = $this->personneRepository->getAllPersonn();//findAll();
        return $this->render('/index.html.twig', [ "personnes" => $personnes
        ]);
    }

    /**
     * @Route("/new", name="add" ,)
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function addPersonnForm(Request $request)
    {
        $personne = new Personne();
        $form = $this->createForm(PersonneType::class, $personne);
        $form->handleRequest($request);

        // Check is valid
        if ($form->isSubmitted() && $form->isValid()) {
            if ($personne->getFamilySituation() !== "Homme seul" && $personne->getFamilySituation() !== "Femme seul" &&
            $personne->getFamilySituation() !== "Couple sans enfant" && $personne->getFamilySituation() !== "Couple avec enfant"  ) {
                return $this->redirectToRoute('add');
            }

            $this->entityManager->persist($personne);
            $this->entityManager->flush();

            return $this->redirectToRoute('personne');
        }

        return $this->render('personne/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delPeople/", name="delPeople")
     * @param Request $request
     */
    public function delPersonne(Request $request) {
        if ($request->isXmlHttpRequest() || $request->request->get("id") == 1) {



            $personne = $this->personneRepository->findOneById($request->request->get("id"));
            if ($personne !== null) {
                
                $this->entityManager->remove($personne);
                $this->entityManager->flush();
        
                return new JsonResponse(array("success" => true)); 
            }
        }
        return new JsonResponse(array("success" => false)); 

    }

}
