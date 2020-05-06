<?php

namespace App\Controller;

use App\Dto\Request\AddDto;
use App\Dto\Request\IdDto;
use App\Dto\Request\UpdateDto;
use App\Exception\TrackException;
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
     * @return \App\Dto\Response\Response
     * @throws ValidationException|TrackException
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
        return DtoHelper::createResponseDto(Response::HTTP_OK, $data, []);
    }

    /**
     * @Route("/update", name="update", methods={"POST"})
     * @param Request $request
     * @return \App\Dto\Response\Response
     * @throws ValidationException|TrackException
     */
    public function updateAction(Request $request)
    {
        // Deserialize the payload
        $updateDto = Serializer::getInstance()->deserialize($request->getContent(), UpdateDto::class, 'json');

        // Validate the resulting dto
        $violations = Validator::getInstance()->validate($updateDto);
        if (count($violations) > 0) {
            throw new ValidationException($violations);
        }

        // Processing
        $data = TrackModel::update($updateDto);

        // Response
        return DtoHelper::createResponseDto(Response::HTTP_OK, $data, []);
    }

    /**
     * @Route("/delete", name="delete", methods={"POST"})
     * @param Request $request
     * @return \App\Dto\Response\Response
     * @throws ValidationException|TrackException
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
        return DtoHelper::createResponseDto(Response::HTTP_OK, $data, []);
    }

    /**
     * @Route("/test", name="test", methods={"POST"})
     * @param Request $request
     * @return void
     */
    public function testAction(Request $request)
    {

    }
}
