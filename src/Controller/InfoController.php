<?php

namespace App\Controller;

use App\Dto\Request\DifferenceDto;
use App\Exception\ValidationException;
use App\Helper\DtoHelper;
use App\Model\InfoModel;
use App\Serializer\Serializer;
use App\Validator\Validator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InfoController extends AbstractController
{
    /**
     * @Route("/info/artists", name="info_artists", methods={"GET"})
     * @param Request $request
     * @return \App\Dto\Response\Response
     */
    public function artistsAction(Request $request)
    {
        // Processing
        $data = InfoModel::getArtists();

        // Response
        return DtoHelper::createResponseDto(Response::HTTP_OK, $data, []);
    }

    /**
     * @Route("/info/tracks", name="info_tracks", methods={"GET"})
     * @param Request $request
     * @return \App\Dto\Response\Response
     */
    public function tracksAction(Request $request)
    {
        // Fetch query parameters if provided, otherwise load default values
        $limit = $request->query->has('limit') ? $request->query->get('limit') : 2000;
        $offset = $request->query->has('offset') ? $request->query->get('offset') : 0;

        // Processing
        $data = InfoModel::getTracks($limit, $offset);

        // Response
        return DtoHelper::createResponseDto(Response::HTTP_OK, $data, []);
    }

    /**
     * @Route("/info/check_url", name="info_check_url", methods={"GET"})
     * @param Request $request
     * @return \App\Dto\Response\Response
     */
    public function getUrlInfo(Request $request)
    {
        // Fetch query parameters
        $url = $request->query->has('url') ? $request->query->get('url') : null;
        $url = urldecode($url);

        // Processing
        $data = InfoModel::getUrlInfo($url);

        // Response
        return DtoHelper::createResponseDto(Response::HTTP_OK, $data, []);
    }

    /**
     * @Route("/info/check_cover", name="info_check_cover", methods={"GET"})
     * @param Request $request
     * @return \App\Dto\Response\Response
     */
    public function checkCoverAction(Request $request)
    {
        // Fetch query parameters
        $url = $request->query->has('url') ? $request->query->get('url') : null;
        $url = urldecode($url);

        // Processing
        $data = InfoModel::checkCover($url);

        // Response
        return DtoHelper::createResponseDto(Response::HTTP_OK, $data, []);
    }

    /**
     * @Route("/info/stats", name="info_stats", methods={"GET"})
     * @param Request $request
     * @return \App\Dto\Response\Response
     */
    public function getApplicationStatus(Request $request)
    {
        // Processing
        $data = InfoModel::getApplicationStatus();

        // Response
        return DtoHelper::createResponseDto(Response::HTTP_OK, $data, []);
    }

    /**
     * @Route("/info/difference", name="info_difference", methods={"POST"})
     * @param Request $request
     * @return \App\Dto\Response\Response
     */
    public function differenceAction(Request $request)
    {
        // Deserialize the payload
        $differenceDto = Serializer::getInstance()->deserialize($request->getContent(), DifferenceDto::class, 'json');

        // Validate the resulting dto
        $violations = Validator::getInstance()->validate($differenceDto);
        if (count($violations) > 0) {
            throw new ValidationException($violations);
        }

        // Processing
        $data = InfoModel::getDifference($differenceDto);

        // Response
        return DtoHelper::createResponseDto(Response::HTTP_OK, $data, []);
    }
}
