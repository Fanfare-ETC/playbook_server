<?php
// Routes
/*
$app->get('/events', function ($request, $response) {
	$selectStatement = $this->database
					     ->select()
                         ->from('event');
	$stmt = $selectStatement->execute();
	$data = $stmt->fetchAll();
	
	return $response->withJson($data);
});
*/
$app->post('/events', function ($request, $response) {
	$parsedBody = $request->getParsedBody();
	$sectionId = $parsedBody['id'];
	//$seatNo = $parsedBody['SeatNo'];
	$insertStatement = $this->database->insert(array('SeatNo', 'UserName', 'SecId'))
									->into('players')
									->values(array('noneedanymore', 'Zoe?', $sectionId));
	$insertId = $insertStatement->execute(false);
	//print_r($request->getQueryParam('stadiumSeatNumber'));
	//print_r($parsedBody['stadiumSeatNumber']);
	return $response;
});


$app->post('/score', function ($request, $response) {
	$parsedBody = $request->getParsedBody();
	$sectionId = $parsedBody['id'];
	$selectStatement = $this->database->select(array('SecScore'))
									  ->from('sections')
									  ->where('id', '=', $sectionId);
	$stmt = $selectStatement->execute();
	$data = $stmt->fetch();
	return $response->withJson($data);
	//print_r($request->getQueryParam('stadiumSeatNumber'));
	//print_r($parsedBody['stadiumSeatNumber']);
	//return $data;
});

$app->get('/highest', function ($request, $response) {
	//$parsedBody = $request->getParsedBody();
	//$sectionId = $parsedBody['id'];		

	//print_r($high);				  

	$selectStatement = $this->database->select(array('id'))
									  ->from('sections')
									  ->orderBy('SecScore', 'DESC')
									//  ->where('SecScore', '=', $high)
									  ;
	//$selectStatement->having('MAX(SecScore)', '<', 10000);								  
	$stmt = $selectStatement->execute();
	$data = $stmt->fetch();
	return $response->withJson($data);
	//print_r($request->getQueryParam('stadiumSeatNumber'));
	//print_r($parsedBody['stadiumSeatNumber']);
	//return $data;
});


$app->post('/move', function ($request, $response) {
				  
	$parsedBody = $request->getParsedBody();
	$sectionId = $parsedBody['id'];
	$selectStatement = $this->database->select(array('Move'))
									  ->from('treasure')
									  ->where('SecId', '=', $sectionId);
	$stmt = $selectStatement->execute();
	$data = $stmt->fetchAll();
	return $response->withJson($data);
	//print_r($request->getQueryParam('stadiumSeatNumber'));
	//print_r($parsedBody['stadiumSeatNumber']);
	//return $data;
});

/*
$app->get('/[{name}]', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});
*/
$app->get('/hello/{name}', function ($request, $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write(json_encode(array(
		"hello" => "world",
		"this" => "seems",
		"simple" => "!"
	)));

    return $response;
});
