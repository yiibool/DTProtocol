<?php
require_once __DIR__ . '/../vendor/autoload.php';

use DTProtocol\Packet;
use DTProtocol\Exception\ProtocolException;
use PHPUnit\Framework\TestCase;

/**
 * Class PacketTest
 * @package DTProtocol\Tests
 */
class PacketTest extends TestCase
{

    /**
     * @var int[] 
     */
    protected $_message = [
        'a' => 1,
        'b' => 2,
    ];

    /**
     * @return array
     */
    public function additionProvider()
    {
        $packet = new Packet();
        $packet->set('package_max_length', 2465792);
        $packet->set('package_length_offset', 0);
        $packet->set('package_body_offset', 8);
        $requestId = time();
        return [
            [
                $packet, $requestId
            ],
        ];
    }

    /**
     * @dataProvider additionProvider
     * @param Packet $packet
     * @param $requestId
     * @return string
     * @throws ProtocolException
     */
    public function testEncode(Packet $packet, $requestId)
    {
        $this->assertTrue(true);
        $encodeData = $packet->encode($this->_message, $requestId);
        $decodeData = $packet->decode($encodeData);

        $this->assertEquals($this->_message, $decodeData['body']);
        $this->assertEquals($requestId, $decodeData['header']['reqid']);
    }

}
