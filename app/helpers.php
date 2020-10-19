<?php 


function gravatar_url($email)
{
	$email = md5($email);
	return "https://gravatar.com/avatar/{$email}?" . http_build_query([
		's' => 60,
		'd' => 'https://cdn.pixabay.com/photo/2016/08/20/05/38/avatar-1606916_960_720.png'
	]);
	// return "https://gravatar.com/avatar/{$email}?s=60&d=https://cdn.pixabay.com/photo/2016/08/20/05/38/avatar-1606916_960_720.png";
}

?>