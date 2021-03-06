<?php

declare(strict_types=1);

namespace MySite\app\Responses;


use Psr\Http\Message\ResponseInterface;

/**
 * Class ErrorResponse
 * @package MySite\app\Responses
 */
class ErrorResponse extends BaseResponse
{

    /**
     * @inheritDoc
     */
    public function getResponse(?string $message = null): ResponseInterface
    {
        $this->setCode(400);

        if ($message) {
            $this->setMessage($message);
        }

        $this->response->getBody()->write(
            json_encode(
                [
                    'message' => $this->message,
                    'code' => $this->code
                ]
            )
        );

        return $this->response->withStatus($this->code);
    }
}
