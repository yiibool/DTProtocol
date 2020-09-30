<?php
namespace DTProtocol;

use DTProtocol\Exception\ProtocolException;

/**
 * Class Packet
 * @package DTProtocol
 */
class Packet
{

    /**
     * pack format
     */
    const PACK_FORMAT = 'NN';

    /**
     * unpack format
     */
    const HEADER_STRUCT = 'Nlength/Nreqid';

    /**
     * @var array
     */
    protected $_config = [];

    /**
     * @param $field
     * @param $value
     */
    public function set($field, $value)
    {
        $this->_config[$field] = $value;
    }

    /**
     * @param $field
     * @return mixed
     * @throws ProtocolException
     */
    public function getConfig($field)
    {
        if (!isset($this->_config[$field])) {
            throw new ProtocolException(ProtocolException::CODE_CONFIG_NOT_SET, '', [
                $field,
            ]);
        }

        return $this->_config[$field];
    }

    /**
     * pack message
     * @param $data
     * @param int $requestId
     * @return string
     */
    public function encode($data, $requestId = 0)
    {
        $encodeData = json_encode($data);
        return pack(self::PACK_FORMAT, strlen($encodeData), $requestId) . $encodeData;
    }

    /**
     * unpack message
     * @param $data
     * @return array
     * @throws ProtocolException
     */
    public function decode($data)
    {
        if (strlen($data) > $this->getConfig('package_max_length')) {
            throw new ProtocolException(ProtocolException::CODE_PACKAGE_TOO_LARGE);
        }

        $header = substr($data, $this->getConfig('package_length_offset'), $this->getConfig('package_body_offset'));
        $body = substr($data, $this->getConfig('package_body_offset'));
        $decodeRes = unpack(self::HEADER_STRUCT, $header);
        if ($decodeRes === false) {
            throw new ProtocolException(ProtocolException::CODE_PACKAGE_DECODE_FAILED);
        }

        if ($decodeRes['length'] - $this->getConfig('package_body_offset') > $this->getConfig('package_max_length')) {
            throw new ProtocolException(ProtocolException::CODE_PACKAGE_TOO_LARGE);
        }

        return [
            'header'    => $decodeRes,
            'body'      => json_decode($body, true),
        ];
    }
}