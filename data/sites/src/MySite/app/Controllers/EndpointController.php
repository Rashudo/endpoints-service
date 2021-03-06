<?php

declare(strict_types=1);

namespace MySite\app\Controllers;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Laminas\Diactoros\Response;
use MySite\app\Responses\CreateResponse;
use MySite\app\Responses\DefaultResponse;
use MySite\app\Responses\ErrorResponse;
use MySite\app\Responses\SuccessResponse;
use MySite\app\Services\EndpointService\EndpointHandler;
use MySite\app\Support\Contracts\EntitiesConstants;
use MySite\app\Support\Entities\Endpoint;
use MySite\app\Support\Facades\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class IndexController
 * @package MySite\app\Controllers
 */
class EndpointController extends BaseController
{

    public function index(): array|ResponseInterface
    {
        $endpoints = (new EndpointHandler())->getAll();

        if (!$endpoints) {
            return (new DefaultResponse())
                ->setCode(204)
                ->setMessage('No Content')
                ->getResponse();
        }

        return $endpoints;
    }

    /**
     * @param ServerRequestInterface $request
     * @return Response
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function store(ServerRequestInterface $request): ResponseInterface
    {
        $validator = Validator::make(EntitiesConstants::ENDPOINT);

        $validationResult = $validator->validate($request);

        if ($validationResult->isFail()) {
            return (new ErrorResponse())->getResponse(
                $validationResult->getMessage()
            );
        }

        $endpoint = Endpoint::createFromArray(
            $validationResult->validated()
        );

        if (!(new EndpointHandler())->saveEndpoint($endpoint)) {
            return (new ErrorResponse())->getResponse();
        }

        return (new CreateResponse())->getResponse();
    }
}
