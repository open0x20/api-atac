<?php

namespace App\Controller;

use App\Dto\Request\AddDto;
use App\Dto\Request\IdDto;
use App\Exception\ValidationException;
use App\Helper\DtoHelper;
use App\Model\TrackModel;
use App\Serializer\Serializer;
use App\Validator\Validator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrackController extends AbstractController
{
    /**
     * @Route("/add", name="add", methods={"POST"})
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function addAction(Request $request)
    {
        // Deserialize the payload
        $addDto = Serializer::getInstance()->deserialize($request->getContent(), AddDto::class, 'json');

        // Validate the resulting dto
        $violations = Validator::getInstance()->validate($addDto);
        if (count($violations) > 0) {
            throw new ValidationException($violations);
        }

        // Processing
        $data = TrackModel::create($addDto);

        // Response
        return new Response(
            DtoHelper::createResponseDto(Response::HTTP_OK, $data, []),
            Response::HTTP_CREATED,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * @Route("/update", name="update", methods={"POST"})
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function updateAction(Request $request)
    {
        // Deserialize the payload
        $idDto = Serializer::getInstance()->deserialize($request->getContent(), IdDto::class, 'json');

        // Validate the resulting dto
        $violations = Validator::getInstance()->validate($idDto);
        if (count($violations) > 0) {
            throw new ValidationException($violations);
        }

        // Processing
        $data = TrackModel::update($idDto);

        // Response
        return new Response(
            DtoHelper::createResponseDto(Response::HTTP_OK, $data, []),
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * @Route("/delete", name="delete", methods={"POST"})
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function deleteAction(Request $request)
    {
        // Deserialize the payload
        $idDto = Serializer::getInstance()->deserialize($request->getContent(), IdDto::class, 'json');

        // Validate the resulting dto
        $violations = Validator::getInstance()->validate($idDto);
        if (count($violations) > 0) {
            throw new ValidationException($violations);
        }

        // Processing
        $data = TrackModel::delete($idDto);

        // Response
        return new Response(
            DtoHelper::createResponseDto(Response::HTTP_OK, $data, []),
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }
}
