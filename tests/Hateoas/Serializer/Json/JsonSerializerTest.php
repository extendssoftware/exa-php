<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Hateoas\Serializer\Json;

use ExtendsSoftware\ExaPHP\Hateoas\Attribute\AttributeInterface;
use ExtendsSoftware\ExaPHP\Hateoas\Link\LinkInterface;
use ExtendsSoftware\ExaPHP\Hateoas\ResourceInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Request\Uri\UriInterface;
use PHPUnit\Framework\TestCase;

class JsonSerializerTest extends TestCase
{
    /**
     * Test that serializer will serialize resource.
     *
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Serializer\Json\JsonSerializer::serialize()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Serializer\Json\JsonSerializer::toArray()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Serializer\Json\JsonSerializer::serializeLinks()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Serializer\Json\JsonSerializer::serializeAttributes()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Serializer\Json\JsonSerializer::serializeResources()
     */
    public function testSerialize(): void
    {
        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->any())
            ->method('toRelative')
            ->willReturn('/api/blog/1');

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->any())
            ->method('getUri')
            ->willReturn($uri);

        $link = $this->createMock(LinkInterface::class);
        $link
            ->expects($this->any())
            ->method('getRequest')
            ->willReturn($request);

        $attribute = $this->createMock(AttributeInterface::class);
        $attribute
            ->expects($this->any())
            ->method('getValue')
            ->willReturnOnConsecutiveCalls(1, 2, 3, 4);

        $resource = $this->createMock(ResourceInterface::class);
        $resource
            ->expects($this->any())
            ->method('getLinks')
            ->willReturnOnConsecutiveCalls(
                ['self' => $link],
                ['self' => $link],
                ['self' => $link],
                ['self' => [$link, $link]]
            );

        $resource
            ->expects($this->any())
            ->method('getResources')
            ->willReturnOnConsecutiveCalls(['author' => [$resource, $resource, $resource]], [], [], []);

        $resource
            ->expects($this->any())
            ->method('getAttributes')
            ->willReturn(['id' => $attribute]);

        /**
         * @var ResourceInterface $resource
         */
        $serializer = new JsonSerializer();

        $this->assertSame(
            json_encode([
                '_links' => [
                    'self' => [
                        'href' => '/api/blog/1',
                    ],
                ],
                '_embedded' => [
                    'author' => [
                        [
                            '_links' => [
                                'self' => [
                                    'href' => '/api/blog/1',
                                ],
                            ],
                            'id' => 1,
                        ],
                        [
                            '_links' => [
                                'self' => [
                                    'href' => '/api/blog/1',
                                ],
                            ],
                            'id' => 2,
                        ],
                        [
                            '_links' => [
                                'self' => [
                                    [
                                        'href' => '/api/blog/1',
                                    ],
                                    [
                                        'href' => '/api/blog/1',
                                    ],
                                ],
                            ],
                            'id' => 3,
                        ]
                    ],
                ],
                'id' => 4,
            ]),
            $serializer->serialize($resource)
        );
    }

    /**
     * Test that empty _links and _embedded array will not be rendered where empty attributes must be rendered.
     *
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Serializer\Json\JsonSerializer::serialize()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Serializer\Json\JsonSerializer::toArray()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Serializer\Json\JsonSerializer::serializeLinks()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Serializer\Json\JsonSerializer::serializeAttributes()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Serializer\Json\JsonSerializer::serializeResources()
     */
    public function testEmptyProperties(): void
    {
        $attribute = $this->createMock(AttributeInterface::class);
        $attribute
            ->expects($this->any())
            ->method('getValue')
            ->willReturnOnConsecutiveCalls(1, 'Title', null, '');

        $resource = $this->createMock(ResourceInterface::class);
        $resource
            ->expects($this->any())
            ->method('getAttributes')
            ->willReturn([
                'id' => $attribute,
                'title' => $attribute,
                'description' => $attribute,
                'comment' => $attribute,
            ]);

        /**
         * @var ResourceInterface $resource
         */
        $serializer = new JsonSerializer();
        $this->assertSame(
            json_encode([
                'id' => 1,
                'title' => 'Title',
                'description' => null,
                'comment' => '',
            ]),
            $serializer->serialize($resource)
        );
    }
}
