<?php

namespace SlackLite\SlackApi\Contracts;

interface SlackStar
{
    public function lists($user = null, $options = []);
    public function all($user = null, $options = []);
}
