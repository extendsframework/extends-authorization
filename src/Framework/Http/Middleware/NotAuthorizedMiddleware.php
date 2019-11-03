<?php
declare(strict_types=1);

namespace ExtendsFramework\Authorization\Framework\Http\Middleware;

use ExtendsFramework\Authorization\AuthorizationException;
use ExtendsFramework\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsFramework\Http\Middleware\MiddlewareInterface;
use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Response\Response;
use ExtendsFramework\Http\Response\ResponseInterface;
use ExtendsFramework\Logger\LoggerInterface;
use ExtendsFramework\Logger\Priority\Notice\NoticePriority;

class NotAuthorizedMiddleware implements MiddlewareInterface
{
    /**
     * Logger.
     *
     * @var LoggerInterface
     */
    private $logger;

    /**
     * NotAuthorizedMiddleware constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function process(RequestInterface $request, MiddlewareChainInterface $chain): ResponseInterface
    {
        try {
            return $chain->proceed($request);
        } catch (AuthorizationException $exception) {
            $this->logger->log(sprintf(
                'Request authorization failed, got message "%s".',
                $exception->getMessage()
            ), new NoticePriority());

            return (new Response())->withStatusCode(403);
        }
    }
}
