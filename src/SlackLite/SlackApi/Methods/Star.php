<?php

namespace SlackLite\SlackApi\Methods;

use SlackLite\SlackApi\Contracts\SlackStar;

class Star extends SlackMethod implements SlackStar
{
    protected $methodsGroup = 'stars.';

    /**
     * This method lists the items starred by a user.
     *
     * @param string $user Show stars by this user. Defaults to the authed user
     * @param array  $options ['count' => 100, 'page' = 1]
     *
     * @return array
     */
    public function lists($user = null, $options = [])
    {
        return $this->method('list', array_merge(['user' => $user], $options));
    }

    /**
     * Alias to lists.
     *
     * @param null  $user Show stars by this user. Defaults to the authed user
     * @param array $options ['count' => 100, 'page' = 1]
     *
     * @return array
     */
    public function all($user = null, $options = [])
    {
        return $this->lists($user, $options);
    }
}
