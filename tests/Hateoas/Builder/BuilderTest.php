<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Hateoas\Builder;

use ExtendsSoftware\ExaPHP\Authorization\AuthorizerInterface;
use ExtendsSoftware\ExaPHP\Authorization\Permission\PermissionInterface;
use ExtendsSoftware\ExaPHP\Hateoas\Attribute\AttributeInterface;
use ExtendsSoftware\ExaPHP\Hateoas\Builder\Exception\AttributeNotFound;
use ExtendsSoftware\ExaPHP\Hateoas\Builder\Exception\LinkNotEmbeddable;
use ExtendsSoftware\ExaPHP\Hateoas\Builder\Exception\LinkNotFound;
use ExtendsSoftware\ExaPHP\Hateoas\Expander\ExpanderInterface;
use ExtendsSoftware\ExaPHP\Hateoas\Link\LinkInterface;
use ExtendsSoftware\ExaPHP\Hateoas\ResourceInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Identity\IdentityInterface;
use PHPUnit\Framework\TestCase;
use Throwable;

class BuilderTest extends TestCase
{
    /**
     * Test that builder will build resource.
     *
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::setAuthorizer()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::setExpander()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::setIdentity()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::addLink()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::addAttribute()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::addResource()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::setToProject()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::setToExpand()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::build()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::getAuthorizedLinks()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::getProjectedAttributes()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::getAuthorizedAttributes()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::getBuiltResources()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::getExpandedResources()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::isAuthorized()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::reset()
     */
    public function testBuild(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $permission = $this->createMock(PermissionInterface::class);

        $identity = $this->createMock(IdentityInterface::class);

        $authorizer = $this->createMock(AuthorizerInterface::class);
        $authorizer
            ->expects($this->any())
            ->method('isPermitted')
            ->with($permission, $identity)
            ->willReturn(true);

        $builder = $this->createMock(BuilderInterface::class);

        $expander = $this->createMock(ExpanderInterface::class);
        $expander
            ->expects($this->any())
            ->method('expand')
            ->willReturn($builder);

        $link = $this->createMock(LinkInterface::class);
        $link
            ->expects($this->any())
            ->method('getPermission')
            ->willReturn($permission);

        $link
            ->expects($this->any())
            ->method('isEmbeddable')
            ->willReturn(true);

        $attribute = $this->createMock(AttributeInterface::class);

        /**
         * @var RequestInterface    $request
         * @var AuthorizerInterface $authorizer
         * @var ExpanderInterface   $expander
         * @var IdentityInterface   $identity
         * @var LinkInterface       $link
         * @var AttributeInterface  $attribute
         * @var ResourceInterface   $resource
         */
        $resource = (new Builder())
            ->setAuthorizer($authorizer)
            ->setExpander($expander)
            ->setIdentity($identity)
            ->addLink('self', $link)
            ->addLink('author', $link)
            ->addLink('items', $link, false)
            ->addLink('items', $link, false)
            ->addAttribute('id', $attribute)
            ->addAttribute('title', $attribute)
            ->addResource('single', $builder)
            ->addResource('multiple', $builder, false)
            ->addResource('multiple', $builder, false)
            ->setToProject([
                'id',
                'title',
                'multiple' => [
                    'id',
                    'title'
                ],
            ])
            ->setToExpand([
                'author',
                'items',
            ])
            ->build($request);

        $this->assertSame([
            'self' => $link,
            'author' => $link,
            'items' => [
                $link,
                $link,
            ],
        ], $resource->getLinks());
        $this->assertSame([
            'id' => $attribute,
            'title' => $attribute,
        ], $resource->getAttributes());

        $resources = $resource->getResources();
        $this->assertInstanceOf(ResourceInterface::class, $resources['single']);
        $this->assertInstanceOf(ResourceInterface::class, $resources['author']);
        $this->assertContainsOnlyInstancesOf(ResourceInterface::class, $resources['items']);

        $this->assertCount(2, $resources['multiple']);
        $this->assertContainsOnlyInstancesOf(ResourceInterface::class, $resources['multiple']);
    }

    /**
     * Test that exception will be thrown when attribute property not exists.
     *
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::setAuthorizer()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::setExpander()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::addAttribute()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::setToProject()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::build()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::getAuthorizedLinks()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::getAuthorizedAttributes()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::getProjectedAttributes()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::isAuthorized()
     */
    public function testAttributeNotFound(): void
    {
        $this->expectException(AttributeNotFound::class);

        $request = $this->createMock(RequestInterface::class);

        $authorizer = $this->createMock(AuthorizerInterface::class);

        $expander = $this->createMock(ExpanderInterface::class);

        $attribute = $this->createMock(AttributeInterface::class);

        /**
         * @var RequestInterface    $request
         * @var AuthorizerInterface $authorizer
         * @var ExpanderInterface   $expander
         * @var AttributeInterface  $attribute
         */
        (new Builder())
            ->setAuthorizer($authorizer)
            ->setExpander($expander)
            ->addAttribute('id', $attribute)
            ->setToProject([
                'title',
            ])
            ->build($request);
    }

    /**
     * Test that exception will be thrown when link rel not exists.
     *
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::setAuthorizer()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::setExpander()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::addAttribute()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::setToProject()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::build()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::getAuthorizedLinks()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::getAuthorizedAttributes()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::getProjectedAttributes()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::getExpandedResources()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::isAuthorized()
     */
    public function testLinkNotFound(): void
    {
        $this->expectException(LinkNotFound::class);

        $request = $this->createMock(RequestInterface::class);

        $authorizer = $this->createMock(AuthorizerInterface::class);

        $expander = $this->createMock(ExpanderInterface::class);

        $link = $this->createMock(LinkInterface::class);

        /**
         * @var RequestInterface    $request
         * @var AuthorizerInterface $authorizer
         * @var ExpanderInterface   $expander
         * @var LinkInterface       $link
         */
        (new Builder())
            ->setAuthorizer($authorizer)
            ->setExpander($expander)
            ->addLink('self', $link)
            ->setToExpand([
                'author',
            ])
            ->build($request);
    }

    /**
     * Test that exception will be thrown when link rel not embeddable.
     *
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::setAuthorizer()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::setExpander()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::addAttribute()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::setToProject()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::build()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::getAuthorizedLinks()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::getAuthorizedAttributes()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::getProjectedAttributes()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::getExpandedResources()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::isAuthorized()
     */
    public function testLinkNotEmbeddable(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $authorizer = $this->createMock(AuthorizerInterface::class);

        $expander = $this->createMock(ExpanderInterface::class);

        $link = $this->createMock(LinkInterface::class);
        $link
            ->expects($this->exactly(3))
            ->method('isEmbeddable')
            ->willReturnOnConsecutiveCalls(false, true, false);

        /**
         * @var RequestInterface    $request
         * @var AuthorizerInterface $authorizer
         * @var ExpanderInterface   $expander
         * @var LinkInterface       $link
         */
        $builder = (new Builder())
            ->setAuthorizer($authorizer)
            ->setExpander($expander)
            ->addLink('singular', $link)
            ->addLink('multiple', $link, false)
            ->addLink('multiple', $link, false);

        try {
            $builder
                ->setToExpand([
                    'singular',
                ])
                ->build($request);
        } catch (Throwable $throwable) {
            $this->assertInstanceOf(LinkNotEmbeddable::class, $throwable);
        }

        try {
            $builder
                ->setToExpand([
                    'multiple',
                ])
                ->build($request);
        } catch (Throwable $throwable) {
            $this->assertInstanceOf(LinkNotEmbeddable::class, $throwable);
        }
    }

    /**
     * Test that an existing and embeddable link will not be embedded when the request is not authorized.
     *
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::setAuthorizer()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::setExpander()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::setIdentity()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::addLink()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::setToExpand()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::build()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::getAuthorizedLinks()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::getProjectedAttributes()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::getAuthorizedAttributes()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::getBuiltResources()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::getExpandedResources()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::isAuthorized()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::reset()
     * @return void
     */
    public function testLinkSoftFail(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $permission = $this->createMock(PermissionInterface::class);

        $identity = $this->createMock(IdentityInterface::class);

        $authorizer = $this->createMock(AuthorizerInterface::class);
        $authorizer
            ->expects($this->exactly(4))
            ->method('isPermitted')
            ->with($permission, $identity)
            ->willReturnOnConsecutiveCalls(true, false, true, false);

        $builder = $this->createMock(BuilderInterface::class);

        $expander = $this->createMock(ExpanderInterface::class);
        $expander
            ->expects($this->any())
            ->method('expand')
            ->willReturn($builder);

        $link = $this->createMock(LinkInterface::class);
        $link
            ->expects($this->exactly(4))
            ->method('getPermission')
            ->willReturn($permission);

        $link
            ->expects($this->exactly(2))
            ->method('isEmbeddable')
            ->willReturn(true);

        /**
         * @var RequestInterface    $request
         * @var AuthorizerInterface $authorizer
         * @var ExpanderInterface   $expander
         * @var IdentityInterface   $identity
         * @var LinkInterface       $link
         * @var ResourceInterface   $resource
         */
        $resource = (new Builder())
            ->setAuthorizer($authorizer)
            ->setExpander($expander)
            ->setIdentity($identity)
            ->addLink('allowed', $link)
            ->addLink('not_allowed', $link)
            ->setToExpand([
                'allowed',
                'not_allowed',
            ])
            ->build($request);

        $this->assertSame([
            'allowed' => $link,
        ], $resource->getLinks());
    }

    /**
     * Test that an existing attribute will not be projected when the request is not authorized.
     *
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::setAuthorizer()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::setExpander()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::setIdentity()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::addAttribute()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::setToProject()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::build()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::getAuthorizedLinks()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::getProjectedAttributes()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::getAuthorizedAttributes()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::getBuiltResources()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::getExpandedResources()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::isAuthorized()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Builder::reset()
     */
    public function testAttributeSoftFail()
    {
        $request = $this->createMock(RequestInterface::class);

        $permission = $this->createMock(PermissionInterface::class);

        $identity = $this->createMock(IdentityInterface::class);

        $authorizer = $this->createMock(AuthorizerInterface::class);
        $authorizer
            ->expects($this->exactly(2))
            ->method('isPermitted')
            ->with($permission, $identity)
            ->willReturnOnConsecutiveCalls(true, false);

        $expander = $this->createMock(ExpanderInterface::class);

        $attribute = $this->createMock(AttributeInterface::class);
        $attribute
            ->expects($this->exactly(2))
            ->method('getPermission')
            ->willReturn($permission);

        /**
         * @var RequestInterface    $request
         * @var AuthorizerInterface $authorizer
         * @var ExpanderInterface   $expander
         * @var IdentityInterface   $identity
         * @var LinkInterface       $link
         * @var AttributeInterface  $attribute
         * @var ResourceInterface   $resource
         */
        $resource = (new Builder())
            ->setAuthorizer($authorizer)
            ->setExpander($expander)
            ->setIdentity($identity)
            ->addAttribute('allowed', $attribute)
            ->addAttribute('not_allowed', $attribute)
            ->setToProject([
                'allowed',
                'not_allowed',
            ])
            ->build($request);

        $this->assertSame([
            'allowed' => $attribute,
        ], $resource->getAttributes());
    }
}
