<?php

declare(strict_types=1);

namespace Whirlwind\App\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Whirlwind\Domain\DataProvider\DataProviderInterface;
use Whirlwind\Infrastructure\Http\Response\Serializer\SerializerInterface;

abstract class Action
{
    protected ResponseFactoryInterface $responseFactory;

    protected SerializerInterface $serializer;

    protected ServerRequestInterface $request;

    protected ResponseInterface $response;

    protected array $args;

    public function __construct(ResponseFactoryInterface $responseFactory, SerializerInterface $serializer)
    {
        $this->responseFactory = $responseFactory;
        $this->serializer = $serializer;
    }

    public function __invoke(ServerRequestInterface $request, array $args = []): ResponseInterface
    {
        $this->request = $request;
        $this->response = $this->responseFactory->createResponse();
        $this->args = $args;
        $result = $this->action();

        if ($result instanceof DataProviderInterface) {
            $this->response = $this->response
                ->withAddedHeader('X-Pagination-Total-Count', $result->getPagination()->getTotal())
                ->withAddedHeader('X-Pagination-Page-Count', $result->getPagination()->getNumberOfPages())
                ->withAddedHeader('X-Pagination-Current-Page', $result->getPagination()->getPage())
                ->withAddedHeader('X-Pagination-Per-Page', $result->getPagination()->getPageSize());
        }

        $this->response->getBody()->write($this->serializer->serialize($result));

        return $this->response;
    }

    abstract protected function action();
}
