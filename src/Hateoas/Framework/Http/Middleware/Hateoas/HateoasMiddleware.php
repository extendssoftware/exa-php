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
use ExtendsSoftware\ExaPHP\Router\RouterInterface;
use ExtendsSoftware\ExaPHP\Security\SecurityServiceInterface;

class HateoasMiddleware implements MiddlewareInterface
{
    /**
     * Authorizer.
     *
     * @var AuthorizerInterface
     */
    private AuthorizerInterface $authorizer;

    /**
     * Resource expander.
     *
     * @var ExpanderInterface
     */
    private ExpanderInterface $expander;

    /**
     * Serializer.
     *
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * Security service.
     *
     * @var SecurityServiceInterface
     */
    private SecurityServiceInterface $securityService;

    /**
     * Router.
     *
     * @var RouterInterface
     */
    private RouterInterface $router;

    /**
     * HateoasMiddleware constructor.
     *
     * @param AuthorizerInterface      $authorizer
     * @param ExpanderInterface        $expander
     * @param SerializerInterface      $serializer
     * @param SecurityServiceInterface $securityService
     * @param RouterInterface          $router
     */
    public function __construct(
        AuthorizerInterface      $authorizer,
        ExpanderInterface        $expander,
        SerializerInterface      $serializer,
        SecurityServiceInterface $securityService,
        RouterInterface          $router
    ) {
        $this->authorizer = $authorizer;
        $this->expander = $expander;
        $this->serializer = $serializer;
        $this->securityService = $securityService;
        $this->router = $router;
    }

    /**
     * @inheritDoc
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
                            ->setIdentity($this->securityService->getIdentity())
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
