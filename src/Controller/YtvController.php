<?php

namespace App\Controller;

use App\Dto\Request\AddDto;
use App\Exception\ValidationException;
use App\Helper\DtoHelper;
use App\Model\ApiModel;
use App\Serializer\Serializer;
use App\Validator\Validator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class YtvController extends AbstractController
{
    /**
     * @Route("/add", name="add")
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
        $data = ApiModel::add($addDto);

        // Response
        return new Response(
            DtoHelper::createResponseDto(Response::HTTP_OK, $data, []),
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }
}
