<?php

namespace Andou;

/**
 * Your own personal Inireader.
 * 
 * The MIT License (MIT)
 * 
 * Copyright (c) 2014 Antonio Pastorino <antonio.pastorino@gmail.com>
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 * 
 * 
 * @author Antonio Pastorino <antonio.pastorino@gmail.com>
 * @category inireader
 * @package andou/inireader
 * @copyright MIT License (http://opensource.org/licenses/MIT)
 */
class Inireader {

  /**
   * An array of configurations
   *
   * @var array
   */
  protected $_configs;

  /**
   * If we should process sections or not
   *
   * @var boolean
   */
  protected $_process_sections = FALSE;

  /**
   * Static property to implement singleton
   *
   * @return Andou\Inireader
   */
  protected static $instances = array();

  /**
   * An array with a cache for camel to underscore conversion
   *
   * @var array 
   */
  protected static $_underscoreCache = array();

  /**
   * Returns an instance of a this class
   * 
   * @param string $inifile Path to the ini file
   * @param boolean $process_sections If we should process sections or not
   * @return Andou\Inireader
   */
  public static function getInstance($inifile, $process_sections = FALSE) {
    $_p = $process_sections ? '1' : '0';
    if (!isset(self::$instances[md5($inifile) . $_p])) {
      $c = __CLASS__;
      self::$instances[md5($inifile) . $_p] = new $c($inifile, $process_sections);
    }
    return self::$instances[md5($inifile) . $_p];
  }

  /**
   * Class constructor. Take the path to the ini file as parameter
   * 
   * @param string $inifile Path to the ini file
   */
  protected function __construct($inifile, $process_sections = FALSE) {
    if (!file_exists($inifile)) {
      die('You should specify a valid ini file path');
    }
    $this->_process_sections = $process_sections;
    $this->_configs = parse_ini_file($inifile, $process_sections);
  }

  /**
   * Returns a configuration reading it from an INI file.
   * 
   * @param type $configuration
   * @return boolean|string
   */
  public function getConfiguration($configuration = FALSE) {
    return $configuration ? $this->_getConfiguration($configuration) : FALSE;
  }

  /**
   * Get configuration magic method wrapper
   *
   * @param   string $method
   * @param   array $args
   * @return  string
   */
  public function __call($method, $args) {
    switch (substr($method, 0, 3)) {
      case 'get' :
        return $this->getConfiguration($this->_underscore(substr($method, 3)));
    }
  }

  /**
   * Internal function to retrieve configurations from array
   * 
   * @param string $configuration
   * @return string
   */
  protected function _getConfiguration($configuration) {
    $value = FALSE;
    if ($this->_process_sections) {
      $path = explode("_", $configuration);
      $section = array_shift($path);
      $key = implode("_", $path);
      if (isset($this->_configs[$section][$key])) {
        $value = $this->_configs[$section][$key];
      }
    } else {
      if (isset($this->_configs[$configuration])) {
        $value = $this->_configs[$configuration];
      }
    }
    return $value;
  }

  /**
   * Makes a string conversion from camel to underscore
   * 
   * @param string $name
   * @return string
   */
  protected function _underscore($name) {
    if (isset(self::$_underscoreCache[$name])) {
      return self::$_underscoreCache[$name];
    }
    $result = strtolower(preg_replace('/(.)([A-Z])/', "$1_$2", $name));
    self::$_underscoreCache[$name] = $result;
    return $result;
  }

}
