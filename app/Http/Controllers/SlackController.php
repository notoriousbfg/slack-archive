<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use GuzzleHttp\Client as Guzzle;
use Elasticsearch\ClientBuilder;

class SlackController extends Controller
{
    private $clientId;
    private $clientSecret;
    private $redirectUri;

    private $searchIndex;

    public function __construct()
    {
        $this->clientId = env('SLACK_CLIENT_ID');
        $this->clientSecret = env('SLACK_CLIENT_SECRET');
        $this->redirectUri = env('SLACK_CLIENT_REDIRECT_URI');
        
        $this->searchIndex = env('ELASTICSEARCH_INDEX');
    }

    public function authorizeSlack()
    {
        return redirect()->to("https://slack.com/oauth/authorize?client_id={$this->clientId}&scope=commands&redirect_uri={$this->redirectUri}");
    }

    public function redirect(Request $request)
    {
        if($request->filled('code')) {
            $code = $request->input('code');

            $http = new Guzzle();
            
            $params = [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'code' => $code,
                'redirect_uri' => $this->redirectUri
            ];

            $response = $http->post('https://slack.com/api/oauth.access', [ 'form_params' => $params ]);
            
            $response_body = json_decode($response->getBody(), true);

            session_start();

            $_SESSION['slack'] = [
                'access_token' => $response_body['access_token'],
                'user_id' => $response_body['user_id'],
                'team_id' => $response_body['team_id'],
            ];

            return redirect('/');
        }

        return redirect('/');
    }
    
    public function archive(Request $request)
    {
        $payload = $request->input('payload');
        
        $client = ClientBuilder::create()->build();

        $client->index([
            'index' => $this->searchIndex,
            'body' => json_decode($payload)
        ]);

        return response()->json([
            'status' => 200
        ], 200);
    }
}
