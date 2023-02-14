<?php namespace FLY\DSource;

/**
 * @author K.B Brew <flyartisan@gmail.com>
 * @version 2.0.0
 * @package FLY\DSource
 */

trait IDSource_Model {

    abstract protected function child_class(): string;

    abstract protected function set_protocols();

    abstract static public function get($ids): DSource_Model;

    abstract static public function all(): array;
};