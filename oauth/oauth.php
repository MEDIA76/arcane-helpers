<?php

/**
 * OAuth 19.05.2-A Arcane Helper
 * https://github.com/MEDIA76/arcane
**/

return new class {
  public $connected;
  public $token;

  private $oauth;
  private $session;

  function __construct() {
    session_start();

    $this->connected = false;
    $this->oauth = basename(__FILE__, '.php');
    $this->session = $_SESSION[$this->oauth] ?? [];

    if(isset($_GET['code']) && isset($_GET['state'])) {
      list($service, $state) = explode('.', $_GET['state'], 2);

      if($this->session['state'] == $state) {
        $obtain = $this->session[$service]['obtain'];
        $obtain['query']['code'] = $_GET['code'];
        $response = $this->request($obtain);

        if($response->access_token) {
          $this->session['token'] = $response->access_token;
        }

        header('Location: ' . path($obtain['redirect_uri']));
      }
    }

    if(!isset($this->session['state'])) {
      $this->session['state'] = bin2hex(random_bytes(16));
    } else if(isset($this->session['token'])) {
      $this->connected = true;
      $this->token = $this->session['token'];
    }

    $_SESSION[$this->oauth] = $this->session;
  }

  function service($name, $requests) {
    $this->session[$name] = $requests;

    if(isset($_SESSION[$this->oauth])) {
      $_SESSION[$this->oauth] = $this->session;
    }

    return implode('?', [
      $requests['permit']['uri'],
      http_build_query($requests['permit']['query'])
    ]);
  }

  function request($request) {
    $request = !is_array($request) ? [
      'uri' => $request
    ] : $request;

    if(isset($this->connected)) {
      $request['query']['token'] = $this->token;
    }

    $request['query'] = array_filter($request['query'] ?? []);
    $request['query'] = http_build_query($request['query']);
    $request['method'] = strtoupper($request['method'] ?? 'GET');

    switch($request['method']) {
      case 'GET':
        $context = stream_context_create([
          'http' => [
            'method' => 'GET',
            'header'  => implode("\r\n", [
              "user-agent: {$_SERVER['HTTP_USER_AGENT']}"
            ])
          ]
        ]);
        $request = "{$request['uri']}?{$request['query']}";
      break;

      case 'POST':
        $context = stream_context_create([
          'http' => [
            'method'  => 'POST',
            'header'  => implode("\r\n", [
              'content-type: application/x-www-form-urlencoded',
              'accept: application/json'
            ]),
            'content' => $request['query']
          ]
        ]);
        $request = $request['uri'];
      break;
    }

    return json_decode(file_get_contents($request, false, $context));
  }

  function github($client_id, $client_secret, $parameters = []) {
    $scope = 'read:user user:email user:follow';
    $login = $allow_signup = $redirect_uri = '';

    return $this->service('github', [
      'permit' => [
        'method' => 'GET',
        'uri' => 'https://github.com/login/oauth/authorize',
        'query' => [
          'client_id' => $client_id,
          'login' => $parameters['login'] ?? $login,
          'scope' => $parameters['scope'] ?? $scope,
          'state' => "github.{$this->session['state']}",
          'allow_signup' => $parameters['allow_signup'] ?? $allow_signup
        ]
      ],

      'obtain' => [
        'method' => 'POST',
        'uri' => 'https://github.com/login/oauth/access_token',
        'query' => [
          'client_id' => $client_id,
          'client_secret' => $client_secret,
          'state' => "github.{$this->session['state']}",
        ],
        'redirect_uri' => $parameters['redirect_uri'] ?? $redirect_uri
      ]
    ]);
  }

  function slack($client_id, $client_secret, $parameters = []) {
    $scope = 'identity.basic identity.email identity.avatar';
    $redirect_uri = '';

    return $this->service('slack', [
      'permit' => [
        'method' => 'GET',
        'uri' => 'https://slack.com/oauth/authorize',
        'query' => [
          'client_id' => $client_id,
          'scope' => $parameters['scope'] ?? $scope,
          'state' => "slack.{$this->session['state']}"
        ]
      ],

      'obtain' => [
        'method' => 'GET',
        'uri' => 'https://slack.com/api/oauth.access',
        'query' => [
          'client_id' => $client_id,
          'client_secret' => $client_secret
        ],
        'redirect_uri' => $parameters['redirect_uri'] ?? $redirect_uri
      ]
    ]);
  }
};

?>