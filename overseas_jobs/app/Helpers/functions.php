<?php 

if (!function_exists('generate_image_link'))
{
	function generate_image_link($filename, $subfolder = "")
	{
		if (empty($filename))
			return asset('/images/default.png');		

		$file = './uploads/'.(!empty($subfolder) ? $subfolder.'/' : '').$filename;		  

		return file_exists($file) ? asset($file)."?cache=".uniqid() : asset('/images/default.png');
	}
}

if (!function_exists('generate_youtube_link'))
{
	function generate_youtube_embed_link($url)
	{
		$video_id = null;
		preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);
		if (isset($match[1])) {
			$video_id = $match[1];
		}

		return $video_id ? 'https://www.youtube.com/embed/'.$video_id : asset('/images/youtube-frame.jpg');
		// return $video_id ? 'https://www.youtube.com/embed/'.$video_id : '';
	}
}

if (!function_exists('delete_file')) {
    function delete_file($filename, $subfolder)
    {
		if (!$filename)
			return;			
		$file = './uploads/'.(!empty($subfolder) ? $subfolder.'/' : '').$filename;
		// $file = base_path($path);
        if (file_exists($file)) {
			unlink($file);
        }        
    }
}

if (!function_exists('blur_image')) {
	function blur_image($type,$file,$name){
		
        if($type == 'face_image'){
			$filename = md5($name);
		}
		else{
			$filename = $name;
		}

		$ext = $file->getClientOriginalExtension();
		//hash image name for blur and face_image
		
		$blur_image = md5($filename);
		$blur_filename = $blur_image.'.'.$ext;
        
        // base_path
		$blur_filepath = './uploads/jobseekers/images/'.$blur_filename;
		$img = Image::make($file);
		$img->resize(150, 117, function ($constraint) {

			$constraint->aspectRatio();
		});

		$img->blur(50)->save($blur_filepath);
		return $filename;
	}
}

if (!function_exists('put_file')) {
    function put_file($file, $filename, $subfolder)
    {
        // base_path
		$path = './uploads/'.(!empty($subfolder) ? $subfolder.'/' : '');
		$file->move($path, $filename);
    }
}

if (!function_exists('get_sign_image_base64')) {
    function get_sign_image_base64()
    {
		// $avatarUrl = public_path('/images/sign.jpg');
		$avatarUrl = './images/sign.png';
        $arrContextOptions= [
			"ssl" => [
				"verify_peer"=>false,
				"verify_peer_name"=>false,
			],
		];
        $type = pathinfo($avatarUrl, PATHINFO_EXTENSION);
        $avatarData = file_get_contents($avatarUrl, false, stream_context_create($arrContextOptions));
        $avatarBase64Data = base64_encode($avatarData);
        return 'data:image/'. $type .';base64,'.$avatarBase64Data;
    }
}

