<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Hateoas\Framework\Http\Middleware\Hateoas;

use ExtendsSoftware\ExaPHP\Authorization\AuthorizerInterface;
use ExtendsSoftware\ExaPHP\Hateoas\Builder\BuilderInterface;
use ExtendsSoftware\ExaPHP\Hateoas\Builder\BuilderProviderInterface;
use ExtendsSoftware\ExaPHP\Hateoas\Builder\Exception\AttributeNotFound;
use ExtendsSoftware\ExaPHP\Hateoas\Builder\Exception\LinkNotEmbeddable;
use ExtendsSoftware\ExaPHP\Hateoas\Builder\Exception\LinkNotFound;
use ExtendsSoftware\ExaPHP\Hateoas\Expander\ExpanderInterface;
use ExtendsSoftware\ExaPHP\Hateoas\Framework\ProblemDetails\AttributeNotFoundProblemDetails;
use ExtendsSoftware\ExaPHP\Hateoas\Framework\ProblemDetails\LinkNotEmbeddableProblemDetails;
use ExtendsSoftware\ExaPHP\Hateoas\Framework\ProblemDetails\LinkNotFoundProblemDetails;
use ExtendsSoftware\ExaPHP\Hateoas\Serializer\SerializerInterface;
use ExtendsSoftware\ExaPHP\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsSoftware\ExaPHP\Http\Middleware\MiddlewareInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Response\Response;
use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;
use ExtendsSoftware\ExaPHP\Identity\Storage\Exception\IdentityNotSet;
use ExtendsSoftware\ExaPHP\Identity\Storage\StorageInterface;
use ExtendsSoftware\ExaPHP\Router\RouterInterface;

class HateoasMiddleware implements MiddlewareInterface
{
    /**
     * HateoasMiddleware constructor.
     *
     * @param AuthorizerInterface $authorizer
     * @param ExpanderInterface   $expander
     * @param SerializerInterface $serializer
     * @param StorageInterface    $storage
     * @param RouterInterface     $router
     */
    public function __construct(
        private readonly AuthorizerInterface $authorizer,
        private readonly ExpanderInterface   $expander,
        private readonly SerializerInterface $serializer,
        private readonly StorageInterface    $storage,
        private readonly RouterInterface     $router
    ) {
    }

    /**
     * @inheritDoc
     * @throws IdentityNotSet
     */
    public function process(RequestInterface $request, MiddlewareChainInterface $chain): ResponseInterface
    {
        $clonedRequest = clone $request;

        $uri = $request->getUri();
        $query = $uri->getQuery();

        $expand = $query['expand'] ?? null;
        $project = $query['project'] ?? null;

        unset($query['expand'], $query['project']);
        $request = $request->withUri($uri->withQuery($query));

        $response = $chain->proceed($request);
        $builder = $response->getBody();
        if ($builder instanceof BuilderProviderInterface) {
            $builder = $builder->getBuilder($this->router);
        }

        if ($builder instanceof BuilderInterface) {
            try {
                $serialized = $this
                    ->serializer
                    ->serialize(
                        $builder
                            ->setExpander($this->expander)
                            ->setAuthorizer($this->authorizer)
                            ->setIdentity($this->storage->getIdentity())
                            ->setToExpand($expand)
                            ->setToProject($project)
                            ->build()
                    );

                $response = $response
                    ->withHeader('Content-Type', 'application/hal+json')
                    ->withHeader('Content-Length', (string)strlen($serialized))
                    ->withBody($serialized);
            } catch (LinkNotFound $exception) {
                return (new Response())->withBody(
                    new LinkNotFoundProblemDetails($clonedRequest, $exception)
                );
            } catch (LinkNotEmbeddable $exception) {
                return (new Response())->withBody(
                    new LinkNotEmbeddableProblemDetails($clonedRequest, $exception)
                );
            } catch (AttributeNotFound $exception) {
                return (new Response())->withBody(
                    new AttributeNotFoundProblemDetails($clonedRequest, $exception)
                );
            }
        }

        return $response;
    }
}
