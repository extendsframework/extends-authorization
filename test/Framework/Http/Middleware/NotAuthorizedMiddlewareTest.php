<?php
declare(strict_types=1);

namespace ExtendsFramework\Authorization\Framework\Http\Middleware;

use ExtendsFramework\Authorization\Framework\ProblemDetails\ForbiddenProblemDetails;
use ExtendsFramework\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Logger\LoggerInterface;
use ExtendsFramework\Logger\Priority\PriorityInterface;
use PHPUnit\Framework\TestCase;

class NotAuthorizedMiddlewareTest extends TestCase
{
    /**
     * Process.
     *
     * Test that authorization exception will be caught and a correct response will be returned.
     *
     * @covers \ExtendsFramework\Authorization\Framework\Http\Middleware\ForbiddenMiddleware::__construct()
     * @covers \ExtendsFramework\Authorization\Framework\Http\Middleware\ForbiddenMiddleware::process()
     */
    public function testProcess(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger
            ->expects($this->once())
            ->method('log')
            ->with(
                'Request authorization failed, got message "Not authorized.".',
                $this->isInstanceOf(PriorityInterface::class)
            );

        $request = $this->createMock(RequestInterface::class);

        $chain = $this->createMock(MiddlewareChainInterface::class);
        $chain
            ->expects($this->once())
            ->method('proceed')
            ->with($request)
            ->willThrowException(new AuthorizationExceptionStub('Not authorized.'));

        /**
         * @var RequestInterface $request
         * @var MiddlewareChainInterface $chain
         * @var LoggerInterface $logger
         */
        $middleware = new ForbiddenMiddleware($logger);
        $response = $middleware->process($request, $chain);

        $this->assertInstanceOf(ForbiddenProblemDetails::class, $response->getBody());
    }
}
