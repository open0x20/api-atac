<?php

namespace App\Controller;

use App\Helper\DtoHelper;
use App\Model\InfoModel;
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
        $limit = $request->query->has('limit') ? $request->query->get('limit') : 1000;
        $offset = $request->query->has('offset') ? $request->query->get('offset') : 0;

        // Processing
        $data = InfoModel::getTracks($limit, $offset);

        // Response
        return DtoHelper::createResponseDto(Response::HTTP_OK, $data, []);
    }

    /**
     * @Route("/info/check_ytv", name="info_check_ytv", methods={"GET"})
     * @param Request $request
     * @return \App\Dto\Response\Response
     */
    public function getYtvInfo(Request $request)
    {
        // Fetch query parameters
        $url = $request->query->has('url') ? $request->query->get('url') : null;
        $url = urldecode($url);

        // Processing
        $data = InfoModel::getYtvInfo($url);

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
}
