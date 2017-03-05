<?php

namespace Ethereum;

/**
 * Basic Ethereum data types.
 *
 * @see: https://github.com/ethereum/wiki/wiki/Ethereum-Contract-ABI#types
 */
class EthDataTypePrimitive extends EthDataType {

  protected $value;

  const MAP = array(
    // Bytes data.
    'D' => 'EthD',
    // Bytes data, length 20
    // 40 hex characters, 160 bits. E.g Ethereum Address.
    'D20' => 'EthD20',
    // Bytes data, length 32
    // 64 hex characters, 256 bits. Eg. TX hash.
    'D32' => 'EthD32',
    // Number quantity.
    'Q' => 'EthQ',
    // Boolean.
    'B' => 'EthB',
    // String data.
    'S' => 'EthS',
    // Default block parameter: Address/D20 or tag [latest|earliest|pending].
    'Q|T' => 'EthBlockParam',
    // Either an array of DATA or a single bytes DATA with variable length.
    'Array|DATA' => 'EthData',
  );

  /**
   * Constructor.
   *
   * @param string|int $val
   *   Hexadecimal or number value.
   * @param array $params
   *   Array with optional parameters. Add Abi type $params['abi'] = 'unint8'.
   */
  public function __construct($val, array $params = array()) {
    $this->setValue($val, $params);
  }

  /**
   * Constructor.
   */
  public static function typeMap($type) {
    return self::MAP[$type];
  }

  /**
   * ReverseTypeMap().
   *
   * @param string $class_name
   *   Classname of the type.
   *
   * @return string
   *    Schema name of the type.
   *
   * @throws \Exception
   */
  public static function reverseTypeMap($class_name) {
    $schema_type = array_search($class_name, self::MAP);
    if (is_string($schema_type)) {
      return $schema_type;
    }
    else {
      throw new \Exception('Could not determine data type.');
    }
  }

  /**
   * Get type of data instance.
   *
   * @param bool $schema
   *   If Schema is TRUE the schema name will be returned.
   *
   * @return string
   *   Returns the CLass name of the type or The schema name if $schema is TRUE.
   */
  public function getType($schema = FALSE) {
    $class_name = get_called_class();
    if (substr($class_name, 0, strlen(__NAMESPACE__)) === __NAMESPACE__) {
      // Cut of namespace and Slash. E.g "Ethereum\".
      $class_name = substr(get_called_class(), strlen(__NAMESPACE__) + 1);
    }
    if ($schema) {
      return $this->reverseTypeMap($class_name);
    }
    else {
      return $class_name;
    }
  }

  /**
   * Validation is implemented in subclasses.
   *
   * @param mixed $val
   *   Value to set.
   *
   * @throws \Exception
   *   If validation is not implemented for type.
   */
  public function setValue($val, array $params = array()) {
    if (method_exists($this, 'validate')) {
      $this->value = $this->validate($val, $params);
    }
    else {
      throw new \Exception('Validation of ' . $this->getType() . ' not implemented yet.');
    }
  }

}
