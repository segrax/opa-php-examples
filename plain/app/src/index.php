<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Segrax\OpenPolicyAgent\Client;

// Policy to be used
$apiPolicy = "package my.api
              default allow=false
              allow {
                  input.path = [\"abc\"]
                  input.user == \"a random user\"
              }";

// Setup the client
$client = new Client(
    [ Client::OPT_AGENT_URL  => 'http://127.0.0.1:8180/',
      Client::OPT_AUTH_TOKEN => 'MyToken']
);

// Push a policy simular to an API AuthZ to the agent
$client->policyUpdate('my/api', $apiPolicy, false);

/**
 **********************************************************************************************************************
 **********************************************************************************************************************
 */
echo "\nPreparing to test if user is allowed to access route /abc\n";
/**
 * Try /abc with 'a random user'
 */
$inputs = [ 'path' => ['abc'],
            'user' => 'a random user'];

// Execute the policy
$res = $client->policy('my/api', $inputs, false, false, false, false);
if ($res->getByName('allow') === true) {
    echo " Result was allow\n";
} else {
    echo " Result was deny\n";
}
/**
 **********************************************************************************************************************
 **********************************************************************************************************************
 */
echo "\nPreparing to test if user is allowed to access route /ab\n";
/**
 * Try /ab with 'a random user'
 */
$inputs = [ 'path' => ['ab'],
            'user' => 'a random user'];

// Execute the policy
$res = $client->policy('my/api', $inputs, false, false, false, false);
if ($res->getByName('allow') === true) {
    echo " Result was allow\n";
} else {
    echo " Result was deny\n";
}

/**
 **********************************************************************************************************************
 **********************************************************************************************************************
 */
echo "\nPreparing to query for servers on network\n";
/**
 * Create some fake server-data
 */
$client->dataUpdate('servers', json_encode([
    ['network' => [1, 2], 'name' => 'A Server'],
    ['network' => [3, 4], 'name' => 'B Server'],
    ['network' => [2, 3], 'name' => 'C Server'],
    ['network' => [2, 3], 'name' => 'D Server'],
    ]));

//Try a query which finds the name of all servers using port 2
$response = $client->query('data.servers[serverid].network[x] == 2; 
							data.servers[serverid].name = name; 
							data.servers[serverid].network[x] = port', false, false, false, false);
$results = $response->getResults();

foreach ($results as $result) {
    echo " Server found on network " . $result['port'] . ": " . $result['name'] . "\n";
}

echo "Done\n";
