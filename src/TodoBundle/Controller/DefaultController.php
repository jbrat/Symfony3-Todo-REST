<?php

namespace TodoBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Util\Codes;

use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc as ApiDoc;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use TodoBundle\Entity\Todo;
use TodoBundle\Form\TodoType;

/**
 * Class DefaultController
 * @package TodoBundle\Controller
 */
class DefaultController extends FOSRestController
{
    /**
     * @Rest\Get("/")
     * @ApiDoc(
     *  description="Get Todo records",
     *     statusCodes={
     *          200="Obtain records successfully",
     *     }
     * )
     *
     */
    public function getAction()
    {
        $todoRepository = $this->getDoctrine()->getRepository('TodoBundle:Todo')->findAll();

        return View::create($todoRepository, Codes::HTTP_OK);
    }

    /**
     * @Rest\Post("/")
     * @ApiDoc(
     *     description="Create Todo record",
     *     input={
     *          "class"="TodoBundle\Form\TodoType",
     *          "name"=""
     *     },
     *     statusCodes={
     *          201="Created successfully",
     *          400="Invalid form params"
     *     }
     * )
     *
     * @param Request $request
     *
     * @return View;
     *
     */
    public function postAction(Request $request)
    {
        $response = new Response();

        $todo = new Todo();
        $form = $this->createForm(TodoType::class, $todo, ['method' => 'POST']);
        $form->submit($request->request->all());

        if ($form->isValid()) {
            $todo = $form->getData();

            $entity = $this->getDoctrine()->getManager();
            $entity->persist($todo);
            $entity->flush();

            return View::create(null, Codes::HTTP_CREATED);
        }

        return View::create($form, Codes::HTTP_BAD_REQUEST);
    }

    /**
     * @Rest\Put("/{id}")
     * @ApiDoc(
     *     description="Update whole Todo record",
     *     input={
     *          "class"="TodoBundle\Form\TodoType",
     *          "name"=""
     *     },
     *     statusCodes={
     *          204="Fields edited successfully",
     *          400="Invalid form params",
     *          404="Record not found"
     *     }
     * )
     *
     */
    public function putAction(Request $request, $id)
    {
        $todoRepository = $this->getDoctrine()->getRepository('TodoBundle:Todo');

        $todo = $todoRepository->find($id);

        if($todo)
        {
            $form = $this->createForm(TodoType::class, $todo, ['method' => 'PUT']);
            $form->submit($request->request->all());

            if ($form->isValid()) {
                $todo = $form->getData();

                $entity = $this->getDoctrine()->getManager();
                $entity->persist($todo);
                $entity->flush();

                return View::create($form, Codes::HTTP_NO_CONTENT);
            }

            return View::create($form, Codes::HTTP_BAD_REQUEST);
        }

        return View::create(null, Codes::HTTP_NOT_FOUND);
    }

    /**
     * @Rest\Patch("/{id}")
     * @ApiDoc(
     *     description="Update fields Todo record",
     *     input={
     *          "class"="TodoBundle\Form\TodoType",
     *          "name"=""
     *     },
     *     statusCodes={
     *          204="Fields edited successfully",
     *          400="Invalid form params",
     *          404="Record not found"
     *     }
     * )
     *
     */
    public function patchAction(Request $request, $id)
    {
        $todoRepository = $this->getDoctrine()->getRepository('TodoBundle:Todo');

        $todo = $todoRepository->find($id);

        if($todo)
        {
            $form = $this->createForm(TodoType::class, $todo, ['method' => 'PATCH']);
            $form->submit($request->request->all(), false);

            if ($form->isValid()) {
                $todo = $form->getData();

                $entity = $this->getDoctrine()->getManager();
                $entity->persist($todo);
                $entity->flush();

                return View::create($form, Codes::HTTP_NO_CONTENT);
            }

            return View::create($form, Codes::HTTP_BAD_REQUEST);
        }

        return View::create(null, Codes::HTTP_NOT_FOUND);
    }

    /**
     * @Rest\Delete("/{id}")
     * @ApiDoc(
     *     description="Remove Todo record",
     *     statusCodes={
     *          204="Removed successfully",
     *          404="Record not found"
     *     }
     * )
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $todoRepository = $this->getDoctrine()->getRepository('TodoBundle:Todo');

        $todo = $todoRepository->find($id);

        if(!$todo)
        {
            return View::create(null, Codes::HTTP_NOT_FOUND);
        }

        $entity = $this->getDoctrine()->getManager();
        $entity->remove($todo);
        $entity->flush();

        return View::create(null, Codes::HTTP_OK);
    }
}
