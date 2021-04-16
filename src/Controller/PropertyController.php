<?php

namespace App\Controller;

use App\Entity\Property;
use App\Entity\Contact;
use App\Notification\ContactNotification;
use App\Form\ContactType;
use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use App\Repository\PropertyRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class PropertyController extends AbstractController
{

    public function __construct(PropertyRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/biens", name="property.index")
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $search = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class, $search);
        $form = $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        }
        $properties = $paginator->paginate(
            $this->repository->findAllVisibleQuery($search),
            $request->query->getInt('page', 1),
            12

        );
        return $this->render('property/index.html.twig', [
            'current_menu' => 'properties',
            'properties' => $properties,
            'form' => $form->createView()
        ]);
    }
    /**
     * 
     * @Route("/biens/{slug}-{id}", name="property.show", requirements={"slug": "[a-z0-9\-]*"})
     * @return Response
     */
    public function show(string $slug, $id, Request $request, ContactNotification $notification, Property $property): Response
    {
        $property = $this->repository->find($id);

        $contact = new Contact();
        $contact->setProperty($property);
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $notification->notify($contact);
            $this->addFlash('success', 'Votre email a bien été envoyé');
            /*
            return $this->redirectToRoute('property.show', [
                'id' => $property->getId(),
                'slug' => $property->getSlug()
            ]);*/
        }

        return $this->render('property/show.html.twig', [
            'property' => $property,
            'current_menu' => 'properties',
            'form' => $form->createView()
        ]);
    }
}
