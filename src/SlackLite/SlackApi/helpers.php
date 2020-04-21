<?php

if (! function_exists('slack')) {

    /**
     * Helper to easy load an slack method or the api.
     * @param  string $method slack method name
     * @return \SlackLite\SlackApi\Contracts|SlackApi|\SlackLite\SlackApi\Methods\SlackMethod
     */
    function slack($method = null)
    {
        $slack = app('SlackLite\SlackApi\Contracts\SlackApi');

        return $method ? $slack->load($method) : $slack;
    }
}
