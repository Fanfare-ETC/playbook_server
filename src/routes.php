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
/**
 * CORS (Cross Origin Resource Sharing) support.
 */
$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

$app->post('/players', function ($request, $response) {
	$parsedBody = $request->getParsedBody();
	//$sectionId = $parsedBody['id'];
	$userId = $parsedBody['userId'];
	$userName = $parsedBody['userName'];
	//$seatNo = $parsedBody['SeatNo'];
	$selectStatement = $this->database->select(array('UserId', 'UserName'))
									->from('players')
									->where('UserId', '=', $userId);
	$stmt = $selectStatement->execute();
	$row = $stmt->fetch();
	//$data[] = array($row['UserId'], $row['UserName']);
	if($stmt->rowCount() < 1){
		$insertStatement = $this->database->insert(array('UserName', 'UserId'))
										->into('players')
										->values(array($userName, $userId));
		$insertId = $insertStatement->execute(false);
	}
	else if($row['UserName'] != $userName){
		$updateStatement = $this->database->update(array('UserName' => $userName))
										->table('players')
										->where('UserId', '=', $userId);
		$stmt = $updateStatement->execute();
	}
	else{
		return $response->write("player already exists");
	}
	//print_r($request->getQueryParam('stadiumSeatNumber'));
	//print_r($parsedBody['stadiumSeatNumber']);
	return $response;
});

$app->post('/updateTrophy', function ($request, $response) {
	$parsedBody = $request->getParsedBody();
	//$sectionId = $parsedBody['id'];
	$userId = $parsedBody['userId'];
	$trophyId = $parsedBody['trophyId'];
	//$seatNo = $parsedBody['SeatNo'];
	$selectStatement = $this->database->select(array('playerId', 'trophyId'))
									->from('playertrophy')
									->where('playerId', '=', $userId)
									->where('trophyId', '=', $trophyId);
	$stmt = $selectStatement->execute();
	$row = $stmt->fetch();
	//$data[] = array($row['UserId'], $row['UserName']);
	if($stmt->rowCount() < 1){
		$insertStatement = $this->database->insert(array('playerId', 'trophyId'))
										->into('playertrophy')
										->values(array($userId, $trophyId));
		$insertId = $insertStatement->execute(false);
	}
/*	else if($row['UserName'] != $userName){
		$updateStatement = $this->database->update(array('UserName' => $userName))
										->table('players')
										->where('UserId', '=', $userId);
		$stmt = $updateStatement->execute();
	}*/
	else{
		return $response->write("Trophy already gained");
	}
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

$app->post('/selfScore', function ($request, $response) {
	$parsedBody = $request->getParsedBody();
	$userId = $parsedBody['id'];
	$selectStatement = $this->database->select(array('PredictionScore', 'CollectionScore', '(PredictionScore+CollectionScore) AS Total'))
									  ->from('players')
									  ->where('UserId', '=', $userId)
									  ;
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

$app->get('/leaderboard', function ($request, $response) {
	//$parsedBody = $request->getParsedBody();
	//$sectionId = $parsedBody['id'];		

	//print_r($high);				  

	$selectStatement = $this->database->select(array('UserName', 'PredictionScore', 'CollectionScore', '(PredictionScore+CollectionScore) AS Total'))
									  ->from('players')
									  ->orderBy('Total', 'DESC')
									  ->limit(10,0)
									//  ->where('SecScore', '=', $high)
									  ;
	//$selectStatement->having('MAX(SecScore)', '<', 10000);								  
	$stmt = $selectStatement->execute();
	$data = $stmt->fetchAll();
	return $response->withJson($data);
	//print_r($request->getQueryParam('stadiumSeatNumber'));
	//print_r($parsedBody['stadiumSeatNumber']);
	//return $data;
});

$app->get('/leaderboardP', function ($request, $response) {
	//$parsedBody = $request->getParsedBody();
	//$sectionId = $parsedBody['id'];		

	//print_r($high);				  

	$selectStatement = $this->database->select(array('UserName', 'PredictionScore', 'CollectionScore', '(PredictionScore+CollectionScore) AS Total'))
									  ->from('players')
									  ->orderBy('PredictionScore', 'DESC')
									  ->limit(10,0)
									//  ->where('SecScore', '=', $high)
									  ;
	//$selectStatement->having('MAX(SecScore)', '<', 10000);								  
	$stmt = $selectStatement->execute();
	$data = $stmt->fetchAll();
	return $response->withJson($data);
	//print_r($request->getQueryParam('stadiumSeatNumber'));
	//print_r($parsedBody['stadiumSeatNumber']);
	//return $data;
});

$app->get('/leaderboardC', function ($request, $response) {
	//$parsedBody = $request->getParsedBody();
	//$sectionId = $parsedBody['id'];		

	//print_r($high);				  

	$selectStatement = $this->database->select(array('UserName', 'PredictionScore', 'CollectionScore', '(PredictionScore+CollectionScore) AS Total'))
									  ->from('players')
									  ->orderBy('CollectionScore', 'DESC')
									  ->limit(10,0)
									//  ->where('SecScore', '=', $high)
									  ;
	//$selectStatement->having('MAX(SecScore)', '<', 10000);								  
	$stmt = $selectStatement->execute();
	$data = $stmt->fetchAll();
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

$app->post('/myTrophy', function ($request, $response) {
				  
	$parsedBody = $request->getParsedBody();
	$userId = $parsedBody['id'];
	$selectStatement = $this->database->prepare(
		'SELECT `playerId`, `trophy`.`trophyId`, `trophy`.`description`, `trophy`.`trophyName`, `trophy`.`category`, `trophy`.`color` '.
		'FROM `playertrophy` '.
		'RIGHT OUTER JOIN `trophy` ON '.
			'`playertrophy`.`trophyId` = `trophy`.`trophyId` AND '.
			'`playertrophy`.`playerId` = :userId '.
		'ORDER BY `trophyId` ASC'
	);
	$selectStatement->bindParam(':userId', $userId, PDO::PARAM_STR);
	/*
	$selectStatement = $this->database->select(array('playerId', 'playertrophy.trophyId'))
									  ->from('playertrophy')
									  //->where('playerId', '=', $userId)
									  ->join('trophy', 'playertrophy.trophyId = trophy.trophyId AND playertrophy.playerId = :userId')
									  ;
	$selectStatement->bindParam(':userId', $userId, PDO::PARAM_STR);
	*/
	/*$joinStatement = $this->database->prepare("SELECT *
											FROM trophy");*/
    //$joinStatement->bindParam(':userId', $userId, PDO::PARAM_STR);
	if ($selectStatement->execute()) {
		$data = $selectStatement->fetchAll();
		return $response->withJson($data);
	} else {
		throw new Exception('Error fetching trophies from the database');
	}
	//echo $response;
	//return $response;
	//print_r($request->getQueryParam('stadiumSeatNumber'));
	//print_r($parsedBody['stadiumSeatNumber']);
	//return $data;
});

$app->post('/updateScore', function ($request, $response) {
				  
	$parsedBody = $request->getParsedBody();
	$userId = $parsedBody['id'];
	$cat = $parsedBody['cat'];
	if($cat == "predict"){
		$predictionScore = $parsedBody['predictScore'];
		$updateStatement = $this->database->prepare("UPDATE players
												SET PredictionScore = PredictionScore + :predictScore
												WHERE UserId = :userId");
		$updateStatement->bindParam(':predictScore', $predictionScore, PDO::PARAM_INT);
        $updateStatement->bindParam(':userId', $userId, PDO::PARAM_STR);
		/*$updateStatement = $this->database->update(array('PredictionScore'))
									  ->set(array('PredictionScore' => 'PredictionScore' + $predictionScore))
									  ->table('players')
									  ->where('UserId', '=', $userId);
									  */
	}
	else if ($cat == "collect"){
		$collectionScore = $parsedBody['collectScore'];
		$updateStatement = $this->database->prepare("UPDATE players
												SET CollectionScore = CollectionScore + :collectScore
												WHERE UserId = :userId");
		$updateStatement->bindParam(':collectScore', $collectionScore, PDO::PARAM_INT);
        $updateStatement->bindParam(':userId', $userId, PDO::PARAM_STR);
	}
	else{
		echo "Wrong category";
	}
	$stmt = $updateStatement->execute();
	//$data = $stmt->fetch();
	return $response;
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