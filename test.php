<?php 
require_once './vendor/autoload.php';
use Workerman\WebServer;
use Workerman\Worker;

function webserver()
{
	// WebServer
	$web = new WebServer("http://0.0.0.0:2345");

	// 4 processes
	$web->count = 4;

	// Set the root of domains
	$web->addRoot('0.0.0.0', '.');
	// run all workers
	Worker::runAll();
}
webserver();

function http()
{
	// #### http worker ####
	$http_worker = new Worker("http://0.0.0.0:2345");

	// 4 processes
	$http_worker->count = 4;

	// Emitted when data received
	$http_worker->onMessage = function($connection, $data)
	{
	    // $_GET, $_POST, $_COOKIE, $_SESSION, $_SERVER, $_FILES are available
	    var_dump($_GET, $_POST, $_COOKIE, $_SESSION, $_SERVER, $_FILES);
	    // send data to client
	    $connection->send("hello world \n");
	};

	// run all workers
	Worker::runAll();
}

function websocket()
{
	// Create a Websocket server
	$ws_worker = new Worker("websocket://0.0.0.0:2346");

	// 4 processes
	$ws_worker->count = 4;

	// Emitted when new connection come
	$ws_worker->onConnect = function($connection)
	{
	    echo "New connection\n";
	 };

	// Emitted when data received
	$ws_worker->onMessage = function($connection, $data)
	{
	    // Send hello $data
	    $connection->send('hello ' . $data);
	};

	// Emitted when connection closed
	$ws_worker->onClose = function($connection)
	{
	    echo "Connection closed\n";
	};

	// Run worker
	Worker::runAll();
}