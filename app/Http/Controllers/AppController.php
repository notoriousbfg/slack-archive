<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Elasticsearch\ClientBuilder;

class AppController extends Controller
{
    private $searchIndex;

    public function __construct()
    {        
        $this->searchIndex = env('ELASTICSEARCH_INDEX');
    }
    
    public function home(Request $request)
    {
        $searchText = $request->get('search');

        $client = ClientBuilder::create()->build();

        $results = $client->search([
            'index' => $this->searchIndex,
            'body'  => [
                'query' => [
                    'match_all' => new \stdClass()
                ]
            ]
        ]);

        return $results;
    }
}