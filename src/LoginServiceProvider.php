<?php

/*
 * This file is part of StyleCI Login.
 *
 * (c) Graham Campbell <graham@mineuk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StyleCI\Login;

use Illuminate\Support\ServiceProvider;

/**
 * This is the login service provider class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class LoginServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerLoginProvider();
    }

    /**
     * Register the login provider class.
     *
     * @return void
     */
    protected function registerLoginProvider()
    {
        $this->app->singleton('login.provider', function ($app) {
            $request = $app['request'];
            $clientId = $app->config->get('login.services.client_id');
            $clientSecret = $app->config->get('login.services.client_secret');
            $clientRedirect = $app->config->get('login.services.redirect');

            $provider = new LoginProvider($request, $clientId, $clientSecret, $clientRedirect)
            $app->refresh('request', $provider, 'setRequest');

            return $provider;
        });

        $this->app->alias('login.provider', LoginProvider::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            'login.provider',
        ];
    }
}
