<?php

namespace brunohanai\LogAware\Action;

interface IAction
{
    public function __construct(array $options = array());

    public function doAction($content);
}