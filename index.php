<?php
function is_youtube($url) {
    $pattern = '#^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch\?v=|/watch\?.+&v=))([\w-]{11})(?:.+)?$#x';
    preg_match($pattern, $url, $matches);
    return (isset($matches[1]))? $matches[1]: false;
}
$links = array ();
if (isset($_POST['link'])) {
	$id = is_youtube($_POST['link']);
	if(!$id) {
		echo "<script>alert('invalid url'); window.location = 'index.php';</script>";
	} 
	$url = 'https://www.youtube.com/get_video_info?video_id='.$id;
	$code = file_get_contents($url);
	parse_str($code);
	$response = json_decode($player_response);
	foreach( $response->streamingData->formats as $key => $format ) {
		$links[$key]['url'] 		= $format->url;
	    $links[$key]['height']		= $format->height;
	    $links[$key]['width'] 		= $format->width;
	    $links[$key]['quality'] 	= $format->quality;
	    $links[$key]['qualityLabel']= $format->qualityLabel;
		$links[$key]['thumb'] 		= $response->videoDetails->thumbnail->thumbnails[0]->url;
	}
}?>
<!DOCTYPE html>
<html>
<head>
	<title>YouTube Downloader - Download Video from YouTube | Vineet Kumar</title>
	<meta name="viewport" content="initial-scale=1.0,user-scalable=no,maximum-scale=1,width=device-width"/>
	<meta name="description" content="Online download videos from YouTube for FREE to PC, mobile. Supports downloading all formats: MP4, 3GP, WebM, HD videos, convert YouTube to MP3, M4A" />
	<meta name="keywords" content="download youtubes video for free, download youtube video from link, download youtube video by link, youtube video download by link, youtube video download from link, youtube video downloader,  youtube video download online, youtube video download online for free, download youtube video online for free">
	<meta name="author" content="John Doe">
	<meta itemprop="image" content="http://vijetahum.com/youtube/vineet.png" />
	<meta property="og:type" content="website" />
	<meta property="og:image" content="http://vijetahum.com/youtube/vineet.png" />
	<meta property="og:title" content="Vineet's Video Downloader" />
	<meta property="og:description" content="Online download videos from YouTube for free, Created by Vineet" />
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<style type="text/css">
		@import url(https://fonts.googleapis.com/css?family=Lato);
		body {
			display: flex;
			height: 100vh;
			justify-content: center;
			align-items: center;
			text-align: center;
		}

		.header {
			font-size: 3em;
			font-family: Lato, sans-serif;
			letter-spacing: 4px;
			text-transform: uppercase;
			background: linear-gradient(90deg, rgba(0,0,0,1) 0%, rgba(255,255,255,1) 50%, rgba(0,0,0,1) 100%);
			background-size: 80%;
			background-repeat: no-repeat;
			// below two lines create text gradient effect
			color: transparent;
			background-clip: text;
			animation: shining 1s linear infinite;
		}

		@keyframes shining {
			from {
				background-position: -500%;
			}
			to {
				background-position: 500%;
			}
		}
	</style>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<p class="header">created by vineet</p>
			</div>
			<div class="col-md-12">
				<form method="post" action="" id="form">
					<div class="input-group mb-3 mt-3">
						<div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon3">Past link</span>
						</div>
						<input type="text" class="form-control" name="link" id="basic-url" aria-describedby="basic-addon3" required="">
						<div class="input-group-append">
					    	<span class="input-group-text" id="basic-addon3" onclick="getElementById('form').submit()" style="cursor: pointer;">Get Link</span>
					  	</div>
					</div>
				</form>
				<?php if (!empty($links)): ?>
				<table class="table bordered">
					<thead>
						<tr>
							<th>Thumb</th>
							<th>Size</th>
							<th>Quality</th>
							<th>Button</th>
						</tr>
					</thead>	
					<tbody>
						<?php foreach ($links as $key => $link): ?>
						<tr>
							<td style="width: 200px;"><img src="<?=$link['thumb']?>" style="width: 150px;" /></td>
							<td><?=$link['width']?>px * <?=$link['height']?>px</td>
							<td><?=$link['quality']?>, <?=$link['qualityLabel']?></td>
							<td><a href="<?=$link['url']?>" class="btn btn-primary" downlaod target="_blank">Download</a></td>
						</tr>
						<?php endforeach ?>
					</tbody>
				</table>
				<?php endif ?>
			</div>
		</div>
	</div>
</body>
</html>