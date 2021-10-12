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
use App\Entity\People;
use App\Form\Type\PeopleType;
class PeopleController extends AbstractController
{
    /** @var EntityManagerInterface */
    private $entityManager;
    /** @var \Doctrine\Common\Persistence\ObjectRepository */
    private $peopleRepository;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->peopleRepository = $entityManager->getRepository(People::class);
    }
    /**
     * @Route("/", name="People")
     */
    public function index(): Response
    {
        $peoples = $this->peopleRepository->getAllPersonn();//findAll();
        return $this->render('/index.html.twig', [ "peoples" => $peoples
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
        $people = new People();
        $form = $this->createForm(PeopleType::class, $people);
        $form->handleRequest($request);

        // Check is valid
        if ($form->isSubmitted() && $form->isValid()) {
            if ($people->getFamilySituation() !== "Homme seul" && $people->getFamilySituation() !== "Femme seul" &&
            $people->getFamilySituation() !== "Couple sans enfant" && $people->getFamilySituation() !== "Couple avec enfant"  ) {
                return $this->redirectToRoute('add');
            }

            $this->entityManager->persist($people);
            $this->entityManager->flush();

            return $this->redirectToRoute('People');
        }

        return $this->render('people/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delPeople/", name="delPeople")
     * @param Request $request
     */
    public function delPeople(Request $request) {
        if ($request->isXmlHttpRequest() || $request->request->get("id") == 1) {
            $people = $this->peopleRepository->findOneById($request->request->get("id"));
            if ($people !== null) {
                
                $this->entityManager->remove($people);
                $this->entityManager->flush();
        
                return new JsonResponse(array("success" => true)); 
            }
        }
        return new JsonResponse(array("success" => false)); 

    }

}
