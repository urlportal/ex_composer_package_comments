<?php

declare(strict_types=1);

namespace Vendor\CommentsClient\Tests;

use Http\Mock\Client as MockClient;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Log\NullLogger;
use Vendor\CommentsClient\CommentsClient;
use Vendor\CommentsClient\CommentsClientInterface;
use Vendor\CommentsClient\Dto\Comment;
use Vendor\CommentsClient\Dto\ListOptions;
use Vendor\CommentsClient\Dto\NewCommentRequest;
use Vendor\CommentsClient\Dto\PaginatedComments;
use Vendor\CommentsClient\Dto\UpdateCommentRequest;

final class CommentsClientTest extends TestCase
{
    public function testInterfaceDeclaresThreeMethods(): void
    {
        $reflection = new \ReflectionClass(CommentsClientInterface::class);

        $this->assertTrue($reflection->isInterface());
        $this->assertTrue($reflection->hasMethod('list'));
        $this->assertTrue($reflection->hasMethod('create'));
        $this->assertTrue($reflection->hasMethod('update'));
    }

    public function testListSignatureMatchesContract(): void
    {
        $method = new \ReflectionMethod(CommentsClientInterface::class, 'list');
        $parameters = $method->getParameters();

        $this->assertCount(1, $parameters);
        $this->assertSame('options', $parameters[0]->getName());
        $this->assertSame(ListOptions::class, (string) $parameters[0]->getType());
        $this->assertSame(PaginatedComments::class, (string) $method->getReturnType());
    }

    public function testCreateSignatureMatchesContract(): void
    {
        $method = new \ReflectionMethod(CommentsClientInterface::class, 'create');
        $parameters = $method->getParameters();

        $this->assertCount(1, $parameters);
        $this->assertSame('request', $parameters[0]->getName());
        $this->assertSame(NewCommentRequest::class, (string) $parameters[0]->getType());
        $this->assertSame(Comment::class, (string) $method->getReturnType());
    }

    public function testUpdateSignatureMatchesContract(): void
    {
        $method = new \ReflectionMethod(CommentsClientInterface::class, 'update');
        $parameters = $method->getParameters();

        $this->assertCount(2, $parameters);
        $this->assertSame('id', $parameters[0]->getName());
        $this->assertSame('int', (string) $parameters[0]->getType());
        $this->assertSame('request', $parameters[1]->getName());
        $this->assertSame(UpdateCommentRequest::class, (string) $parameters[1]->getType());
        $this->assertSame(Comment::class, (string) $method->getReturnType());
    }

    public function testClientImplementsInterface(): void
    {
        $client = $this->makeClient();

        $this->assertInstanceOf(CommentsClientInterface::class, $client);
    }

    public function testConstructorStoresDependenciesWithoutModification(): void
    {
        $httpClient = new MockClient();
        $requestFactory = new Psr17Factory();
        $streamFactory = new Psr17Factory();
        $logger = new NullLogger();

        $client = new CommentsClient(
            'http://example.com',
            $httpClient,
            $requestFactory,
            $streamFactory,
            $logger,
        );

        $this->assertSame('http://example.com', $client->baseUrl);
        $this->assertSame($httpClient, $client->httpClient);
        $this->assertSame($requestFactory, $client->requestFactory);
        $this->assertSame($streamFactory, $client->streamFactory);
        $this->assertSame($logger, $client->logger);
    }

    public function testLoggerDefaultsToNull(): void
    {
        $client = $this->makeClient();

        $this->assertNull($client->logger);
    }

    public function testConstructorAcceptsBaseUrlWithTrailingSlash(): void
    {
        $client = new CommentsClient(
            'http://example.com/',
            new MockClient(),
            new Psr17Factory(),
            new Psr17Factory(),
        );

        $this->assertSame('http://example.com/', $client->baseUrl);
    }

    public function testListThrowsLogicExceptionUntilImplemented(): void
    {
        $client = $this->makeClient();

        $this->expectException(\LogicException::class);

        $client->list(new ListOptions());
    }

    public function testCreateThrowsLogicExceptionUntilImplemented(): void
    {
        $client = $this->makeClient();

        $this->expectException(\LogicException::class);

        $client->create(new NewCommentRequest('Анна', 'Привет'));
    }

    public function testUpdateThrowsLogicExceptionUntilImplemented(): void
    {
        $client = $this->makeClient();

        $this->expectException(\LogicException::class);

        $client->update(1, new UpdateCommentRequest('Анна'));
    }

    private function makeClient(): CommentsClient
    {
        /** @var ClientInterface $httpClient */
        $httpClient = new MockClient();
        /** @var RequestFactoryInterface&StreamFactoryInterface $factory */
        $factory = new Psr17Factory();

        return new CommentsClient(
            'http://example.com',
            $httpClient,
            $factory,
            $factory,
        );
    }
}
