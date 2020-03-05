<?php 
	require_once('../../config.php');
	require_once('../../classes/Search.php');

	if(isset($_POST['text'])){
		$text = $_POST['text'];

		$search = new Search($con);
		
		$allWorkers = $search->searchForWorker($text);

		echo json_encode($allWorkers);
	}


?>