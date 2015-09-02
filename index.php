<?php
	function auth($id, $password, $login_array) {
		$auth = false;
		foreach ($login_array as $record) {
			if (($id == $record->id) and ($password == $record->password)) {
				$auth = $record;
				break;
			}
		}
		return $auth;
	}

	function findAndResult($id, $filename)
	{
		$fp = fopen('data/'.$filename, 'r');
		$rawdata = fread($fp, filesize('data/'.$filename));
		$data_array = json_decode($rawdata);

		$find = false;
		foreach ($data_array as $record) {
			if ($id == $record->id) {
				$find = $record;
				break;
			}
		}
		return $find;
	}

	function getCount($filename) {
		$fp = fopen('data/'.$filename, 'r');
		$rawdata = fread($fp, filesize('data/'.$filename));
		$data_array = json_decode($rawdata);

		$len = count($data_array);
		return $len;
	}



	$fp = fopen('data/login.json', 'r');
	$rawdata = fread($fp, filesize('data/login.json'));

	$login_array = json_decode($rawdata);

	$id = $_POST['id'];
	$password = $_POST['password'];

	if ($id == null or $password == null) {
		echo json_encode(array("error"=>"login error"));
		die();
	}

	$user = auth($id, $password, $login_array);
	if ($user == false) {
		echo json_encode(array("error"=>"login error"));
		die();
	}

	$outdata = array("name"=>$user->name, "error"=>"null");

	$math_res = findAndResult($user->id, "math.json");
	if ($math_res) {
		$outdata["math"] = array("rating"=>$math_res->rating, "score"=>$math_res->score);
		$outdata["math"]["count"] = getCount("math.json");
	}

	$bio_res = findAndResult($user->id, "bio.json");
	if ($bio_res) {
		$outdata["bio"] = array("rating"=>$bio_res->rating, "score"=>$bio_res->score);
		$outdata["bio"]["count"] = getCount("bio.json");
	}

	echo json_encode($outdata);
?>