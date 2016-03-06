<?php

namespace brunohanai\LogAware\Action;

interface IAction
{
    public function __construct($name, array $options = array());

    public function doAction($content);

    public function getType();

    public function getName();
}