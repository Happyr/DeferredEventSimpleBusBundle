<?php

namespace Happyr\Mq2phpBundle\Tests\Unit\Service;

use Happyr\Mq2phpBundle\Service\MessageSerializerDecorator;
use PHPUnit\Framework\TestCase;

class MessageSerializerDecoratorTest extends TestCase
{
    public function testWrapAndSerialize()
    {
        $inner = $this->getMockBuilder('SimpleBus\Serialization\Envelope\Serializer\MessageInEnvelopeSerializer')
            ->getMock();
        $inner->method('wrapAndSerialize')
            ->willReturnArgument(0);

        $eventDispatcher = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
            ->getMock();

        $service = new MessageSerializerDecorator($inner, $eventDispatcher, ['foo' => 'bar', 'baz' => 'biz']);
        $result = $service->wrapAndSerialize('data');

        $array = json_decode($result, true);
        $this->assertEquals('foo', $array['headers'][0]['key']);
        $this->assertEquals('bar', $array['headers'][0]['value']);
        $this->assertEquals('baz', $array['headers'][1]['key']);
        $this->assertEquals('biz', $array['headers'][1]['value']);
        $this->assertEquals('data', $array['body']);
    }
}
