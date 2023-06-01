<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Form\CheckboxType as FormCheckboxType;
use App\Form\SearchTodoType;
use App\Form\TodoType;
use App\Repository\TodoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/todo")
 */
class TodoController extends AbstractController
{
    /**
     * @Route("/", name="app_todo_index", methods={"GET","POST"})
     */
    public function index(TodoRepository $todoRepository, Request $request): Response
    {
        $form = $this->createForm(FormCheckboxType::class);
        $form->handleRequest($request);

        $formSearch = $this->createForm(SearchTodoType::class);
        $formSearch->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $isdone = $form->get('status')->getData();

            $criteriaCheckbox = [];
            if ($isdone) {
                $criteriaCheckbox = ["status" => $isdone];
            }

            return $this->render('todo/index.html.twig', [
                'todos' => $todoRepository->findBy($criteriaCheckbox, []),
                'form' => $form->createView(),
                'formSearch' => $formSearch->createView()
            ]);
        }

        if ($formSearch->isSubmitted() && $formSearch->isValid()) {
            $search = $formSearch->get('search')->getData();

            $critereSearch = [];
            if(isset($search)){
                $search = $todoRepository->search($search);
            }

            return $this->render('todo/index.html.twig', [
                'todos' => $search,
                'form' => $form->createView(),
                'formSearch' => $formSearch->createView()
            ]);
        }

        $order = $request->query->get('order') == 'ASC' ? 'DESC' : 'ASC';
        $orderBy = $request->query->get('orderby');
        if (isset($order) && isset($orderBy)) {
            $criteria = [$orderBy => $order];
            return $this->render('todo/index.html.twig', [
                'todos' => $todoRepository->findBy([], $criteria),
                'order' => $order,
                'form' => $form->createView(),
                'formSearch' => $formSearch->createView()
            ]);
        } else {
            return $this->render('todo/index.html.twig', [
                'todos' => $todoRepository->findAll(),
                'form' => $form->createView(),
                'formSearch' => $formSearch->createView()
            ]);
        }
    }


    /**
     * @Route("/new", name="app_todo_new", methods={"GET", "POST"})
     */
    public function new(Request $request, TodoRepository $todoRepository): Response
    {
        $todo = new Todo();
        $form = $this->createForm(TodoType::class, $todo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $todoRepository->add($todo, true);

            return $this->redirectToRoute('app_todo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('todo/new.html.twig', [
            'todo' => $todo,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_todo_show", methods={"GET"})
     */
    public function show(Todo $todo): Response
    {
        return $this->render('todo/show.html.twig', [
            'todo' => $todo,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_todo_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Todo $todo, TodoRepository $todoRepository): Response
    {
        $form = $this->createForm(TodoType::class, $todo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $todoRepository->add($todo, true);

            return $this->redirectToRoute('app_todo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('todo/edit.html.twig', [
            'todo' => $todo,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_todo_delete", methods={"POST"})
     */
    public function delete(Request $request, Todo $todo, TodoRepository $todoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $todo->getId(), $request->request->get('_token'))) {
            $todoRepository->remove($todo, true);
        }

        return $this->redirectToRoute('app_todo_index', [], Response::HTTP_SEE_OTHER);
    }
}
