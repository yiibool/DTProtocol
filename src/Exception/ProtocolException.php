<?php
namespace DTProtocol\Exception;

use Exception;

/**
 * Class ProtocolException
 * @package DTProtocol\Exception
 */
class ProtocolException extends Exception
{
    /**
     * error code
     */
    CONST CODE_PACKAGE_TOO_LARGE = 9001; // Packet is too large
    CONST CODE_PACKAGE_DECODE_FAILED = 9002; // Packet parsing failed
    CONST CODE_CONFIG_NOT_SET = 9003; // Configuration item is not set
    CONST CODE_UNKNOWN = 9999; // Unknown error

    /**
     * document
     * @var string[]
     */
    public static $errorMessages = [
        self::CODE_PACKAGE_TOO_LARGE         => 'Packet is too large',
        self::CODE_PACKAGE_DECODE_FAILED     => 'Packet parsing failed',
        self::CODE_CONFIG_NOT_SET            => 'Configuration %s is not set',
        self::CODE_UNKNOWN                   => 'Unknown error',
    ];

    /**
     * construct
     * @param integer $code error code
     * @param array $extendParams
     * @param string $message
     */
    public function __construct($code, $message = '', $extendParams = [])
    {
        if (is_array(static::$errorMessages) and !empty(static::$errorMessages)) {
            self::$errorMessages += static::$errorMessages;
        }

        $message = !empty($message) ? $message : (
            isset(self::$errorMessages[$code]) ? self::$errorMessages[$code] : self::$errorMessages[self::CODE_UNKNOWN]
        );

        if (!empty($extendParams)) {
            $message = vsprintf($message, $extendParams);
        }

        parent::__construct($message, $code);
    }

}
