<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Hateoas\Builder;

use ExtendsSoftware\ExaPHP\Hateoas\Attribute\Attribute;
use ExtendsSoftware\ExaPHP\Hateoas\Link\Link;
use ExtendsSoftware\ExaPHP\Router\RouterException;
use ExtendsSoftware\ExaPHP\Router\RouterInterface;

class CollectionBuilder extends Builder
{
    /**
     * CollectionBuilder constructor.
     *
     * @param RouterInterface $router
     * @param string          $route
     * @param mixed[]         $parameters
     * @param string          $rel
     * @param mixed[]         $resources
     * @param int             $limit
     * @param int             $page
     * @param int             $total
     * @param mixed[]|null    $keys
     *
     * @throws RouterException
     */
    public function __construct(
        RouterInterface $router,
        string $route,
        array $parameters,
        string $rel,
        array $resources,
        int $limit,
        int $page,
        int $total,
        array $keys = null
    ) {
        $keys['limit'] = $keys['limit'] ?? 'limit';
        $keys['page'] = $keys['page'] ?? 'page';
        $keys['total'] = $keys['total'] ?? 'total';

        $this
            ->addLink(
                'self',
                new Link(
                    $router->assemble(
                        $route,
                        array_merge(
                            $parameters,
                            [
                                $keys['limit'] => $limit,
                                $keys['page'] => $page,
                            ]
                        )
                    )
                )
            )
            ->addAttribute($keys['limit'], new Attribute($limit))
            ->addAttribute($keys['page'], new Attribute($page))
            ->addAttribute($keys['total'], new Attribute($total));

        if ($page > 1) {
            $this->addLink(
                'prev',
                new Link(
                    $router->assemble(
                        $route,
                        array_merge(
                            $parameters,
                            [
                                $keys['limit'] => $limit,
                                $keys['page'] => $page - 1,
                            ]
                        )
                    )
                )
            );
        }

        if (($page * $limit) < $total) {
            $this->addLink(
                'next',
                new Link(
                    $router->assemble(
                        $route,
                        array_merge(
                            $parameters,
                            [
                                $keys['limit'] => $limit,
                                $keys['page'] => $page + 1,
                            ]
                        )
                    )
                )
            );
        }

        foreach ($resources as $resource) {
            $this->addResource($rel, $resource, false);
        }
    }
}
