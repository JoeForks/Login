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

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * This is the login provider class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class LoginProvider
{
    /**
     * The http request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * The client id.
     *
     * @var string
     */
    protected $clientId;

    /**
     * The client secret.
     *
     * @var string
     */
    protected $clientSecret;

    /**
     * The redirect url.
     *
     * @var string
     */
    protected $redirectUrl;

    /**
     * The guzzle http client.
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * The request scope.
     *
     * @var array
     */
    protected $scope = ['admin:repo_hook', 'read:org', 'repo', 'user:email'];

    /**
     * Create a new provider instance.
     *
     * @param \Illuminate\Http\Request $request
     * @param string                   $clientId
     * @param string                   $clientSecret
     * @param string                   $redirectUrl
     *
     * @return void
     */
    public function __construct(Request $request, $clientId, $clientSecret, $redirectUrl)
    {
        $this->request = $request;
        $this->clientId = $clientId;
        $this->redirectUrl = $redirectUrl;
        $this->clientSecret = $clientSecret;
        $this->client = new Client();
    }

    /**
     * Redirect the user of the application to the provider's authentication screen.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirect()
    {
        $state = sha1(time().$this->request->getSession()->get('_token'));

        $this->request->getSession()->set('state', $state);

        return new RedirectResponse($this->buildAuthUrlFromBase('https://github.com/login/oauth/authorize', $state));
    }

    /**
     * Get the authentication url for the provider.
     *
     * @param string $url
     * @param string $state
     *
     * @return string
     */
    protected function buildAuthUrlFromBase($url, $state)
    {
        $query = [
            'client_id'     => $this->clientId,
            'redirect_uri'  => $this->redirectUrl,
            'scope'         => implode(',', $this->scope),
            'state'         => $state,
            'response_type' => 'code',
        ];

        return $url.'?'.http_build_query($query, '', '&');
    }

    /**
     * Get the User instance for the authenticated user.
     *
     * @return array
     */
    public function user()
    {
        if ($this->request->input('state') !== $this->request->getSession()->get('state')) {
            throw new InvalidStateException();
        }

        $token = $this->getAccessToken($this->request->input('code'));

        return $this->getUserByToken($token);
    }

    /**
     * Get the access token for the given code.
     *
     * @param string $code
     *
     * @return string
     */
    protected function getAccessToken($code)
    {
        $body = [
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code'          => $code,
            'redirect_uri'  => $this->redirectUrl,
        ];

        $response = $this->client->post('https://github.com/login/oauth/access_token', [
            'headers' => ['Accept' => 'application/json'],
            'body'    => $body,
        ]);

        return json_decode($response->getBody(), true)['access_token'];
    }

    /**
     * Get the raw user for the given access token.
     *
     * @param string $token
     *
     * @return array
     */
    protected function getUserByToken($token)
    {
        $options = ['headers' => ['Accept' => 'application/vnd.github.v3+json']];

        $response = $this->client->get('https://api.github.com/user?access_token='.$token, $options);
        $user = json_decode($response->getBody(), true);

        $user['email'] = $this->getEmail($token);
        $user['token'] = $token;

        return $user;
    }

    /**
     * Get email address for the given access token.
     *
     * @param string $token
     *
     * @return string|null
     */
    protected function getEmail($token)
    {
        $options = ['headers' => ['Accept' => 'application/vnd.github.v3+json']];

        $response = $this->client->get('https://api.github.com/user/emails?access_token='.$token, $options);
        $emails = json_decode($response->getBody(), true);

        foreach ($emails as $email) {
            if ($email['primary'] && $email['verified']) {
                return $email['email'];
            }
        }
    }

    /**
     * Set the request instance.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Set the request scope.
     *
     * @param array $scope
     *
     * @return $this
     */
    public function setScope(array $scope)
    {
        $this->scope = $scope;

        return $this;
    }
}
