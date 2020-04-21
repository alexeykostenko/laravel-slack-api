<?php

namespace SlackLite\SlackApi;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class SlackApiServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Methods to register.
     * @var array
     */
    protected $methods = [
        'Channel',
        'Group',
        'Chat',
        'InstantMessage',
        'Search',
        'File',
        'User',
        'Team',
        'Star',
        'RealTimeMessage',
        'UserAdmin',
    ];

    /**
     * Default contracts namespace.
     * @var string
     */
    protected $contractsNamespace = 'SlackLite\SlackApi\Contracts';

    /**
     * Default methods namespace.
     * @var string
     */
    protected $methodsNamespace = 'SlackLite\SlackApi\Methods';

    /**
     * Default prefix of facade accessors.
     * @var string
     */
    protected $shortcutPrefix = 'slack.';

    /**
     * Register the service provider.
     */
    public function register()
    {
        /* Lumen autoload services configs */
        if (Str::contains($this->app->version(), 'Lumen')) {
            $this->app->configure('services');
        }

        $this->app->singleton('SlackLite\SlackApi\Contracts\SlackApi', function () {
            $api = new SlackApi(null, config('services.slack.token'));

            return $api;
        });

        $this->app->alias('SlackLite\SlackApi\Contracts\SlackApi', 'slack.api');

        foreach ($this->methods as $method) {
            $this->registerSlackMethod($method);
        }

        $this->app->alias('SlackLite\SlackApi\Contracts\SlackInstantMessage', 'slack.im');

        $this->app->alias('SlackLite\SlackApi\Contracts\SlackRealTimeMessage', 'slack.rtm');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['slack.api'];
    }

    public function registerSlackMethod($name)
    {
        $contract = Str::finish($this->contractsNamespace, '\\') . "Slack{$name}";
        $shortcut = $this->shortcutPrefix . Str::case($name);
        $class = Str::finish($this->methodsNamespace, '\\') . $name;

        $this->registerSlackSingletons($contract, $class, $shortcut);
    }

    /**
     * @param $contract
     * @param $class
     * @param $shortcut
     */
    public function registerSlackSingletons($contract, $class, $shortcut = null)
    {
        $this->app->singleton($contract, function () use ($class) {
            return new $class($this->app['slack.api'], $this->app['cache.store']);
        });

        if ($shortcut) {
            $this->app->alias($contract, $shortcut);
        }
    }
}
