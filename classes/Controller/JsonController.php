<?php

/**
 * JsonController class.
 */

namespace Alltube\Controller;

use Alltube\Exception\AlltubeLibraryException;
use Graby\HttpClient\Plugin\ServerSideRequestForgeryProtection\Exception\InvalidURLException;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\StatusCode;

/**
 * Controller that returns JSON.
 */
class JsonController extends BaseController
{
    /**
     * Return the JSON object generated by youtube-dl.
     *
     * @param Request $request PSR-7 request
     * @param Response $response PSR-7 response
     *
     * @return Response HTTP response
     * @throws AlltubeLibraryException
     */
    public function json(Request $request, Response $response): Response
    {
        try {
            $url = $this->getVideoPageUrl($request);

            $this->video = $this->downloader->getVideo(
                $url,
                $this->getFormat($request),
                $this->getPassword($request)
            );

            return $response->withJson($this->video->getJson());
        } catch (InvalidURLException $e) {
            return $response->withJson(['error' => $e->getMessage()])
                ->withStatus(StatusCode::HTTP_BAD_REQUEST);
        }
    }
}
