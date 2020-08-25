<?php 
include_once('src/config/constant.php');
include_once('src/helper/token.php');
include_once('src/helper/common.php');

session_set_cookie_params(
	0,
	'/',
	'.' . $_SERVER['HTTP_HOST'],
	(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? TRUE : FALSE),
	TRUE // HttpOnly; Yes, this is intentional and not configurable for security reasons
);

session_set_cookie_params(SESSION_START_TIME_EXPIRE);
session_start();
$start_time = 0;
if(isset($_SESSION[SESSION_START_TIME_NAME]))
{
	$start_time = $_SESSION[SESSION_START_TIME_NAME];
	$chkStartTime = date('Y-m-d H:i:s', $start_time);
	$start_time = $chkStartTime === -1 || $chkStartTime === FALSE ? 0 : $start_time;
}
if($start_time == 0)
{
	$start_time = time();
	$_SESSION[SESSION_START_TIME_NAME] = $start_time;
}

$token = isset($_GET['token']) ? trim($_GET['token']) : '';
$isValidToken = token::validate($token, PROJECT_KEY_NAME, TOKEN_KEY, TOKEN_EXPIRE_TIME);//12hour expire token

$post_url = '';
$post_title = '';
$post_content = '';
if($isValidToken)
{
	$new_token = token::generate(PROJECT_KEY_NAME, TOKEN_KEY);
	// neu co post form
	if(isset($_POST['title']))
	{ 
		$post_url = '';
		if (isset($_POST['url']))
		{
			$post_url = trim($_POST['url']);
			$post_url = preg_match('/[a-zA-Z0-9_@\-\.]+$/',$post_url) ? $post_url : '';
			if($post_url != '')
			{
				$post_url = "./image/pic/" . $post_url;
			}
			else
			{
				$$post_url = "./image/pic/rs-1.jpg";
			}
		}
		else
		{
			$$post_url = "./image/pic/rs-1.jpg";
		}
		
		$post_title = '';
		if (isset($_POST['title'])) {
			$post_title = removeAllTags($_POST['title']);
		}
		
		$post_content = '';
		if(isset($_POST['content']))
		{
			$post_content = removeAllTags($_POST['content']);
		}

		function randtext($post_title = 'Vinh',$post_content = 'Hành động') {
			$textArr = [
				"$post_title vừa bị bóc phốt vì $post_content trong giờ làm.",
				"Chết, $post_title vừa $post_content nhé!",
				"Ra đây mà xem $post_title đang $post_content nè.",
				"Á à, $post_title $post_content trong giờ làm việc nè.",
				"Nhà bao việc mà $post_title cứ $post_content thế.",
				"Ê, anh em lại mà xem $post_title đang $post_content trong giờ kìa."
			];
			return $textRandom = $textArr[rand(0,count($textArr) - 1)];
		}
		
		$randomString = randtext($post_title, $post_content);
	}
}

// valid form post
// if had validated post => store image and save to database
if(!$isValidToken || $post_url == '' || $post_title == '' || $post_content == '')
{
	header('Location: '. URL_ROOT_PROJECT);
	die();
}
// xu ly luu anh + luu vao database
else
{
?>
	<!DOCTYPE html>
	<html lang="vi">

	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<link rel="icon" href="../wp-content/themes/creativestudio/dist/images/favicon.png">
		<link rel="stylesheet" href="./css/detail.css">
		<link rel="stylesheet" href="./css/customer.css">
		<title>Game bóc phốt công sở</title>
		<meta name="twitter:card" content="summary_large_image">
		<meta name="twitter:description" content="Game Bóc phốt công sở được phát triển bởi Creative Studio Athena">
		<meta name="twitter:title" content="Game bóc phốt công sở">
	</head>

	<body>
		<script>
			var img_link = localStorage.getItem("img-link");
			window.fbAsyncInit = function() {
				FB.init({
					appId: '1359102264479606',
					autoLogAppEvents: true,
					xfbml: true,
					version: 'v7.0'
				});

			};
		</script>
		<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js"></script>
		<main id="detail-wrapper">
			<audio id="btnsound" src="./audio/button-sound.mp3"></audio>
			<section id="section-content">
				<div class="section-left">
					<div id="img2download" class="section-image">
						<p class="title-container">
							<img class="img-title" src="./image/icon/downloadimg-title.png" alt="">
						</p>
						<img class="download-img" src="<?php echo $post_url; ?>" alt="">
						<h2>
							<?php echo $randomString ?>
						</h2>
					</div>
					<div class="section-btn">
						<a id="continue-btn" href="<?php echo URL_ROOT_PROJECT; ?>game.php" onclick="continueBtn();">Bóc phốt tiếp</a>
					</div>
				</div>
				<div class="section-right">
					<h1>"Bóc phốt" công sở</h1>
					<h3>Share luôn với 500 anh em thôi chứ đợi gì nữa!</h3>
					<div class="section-social">
						<ul>
							<li>
								<a id="share-fb" class="ico ico-fb" target="_blank" title="Share Facebook"></a>
							</li>
							<li>
								<a id="share-mess" class="ico ico-mess" title="Share message"></a>
							</li>
							<li>
								<a id="btn-download" class="ico ico-download" title="Download"></a>
							</li>
						</ul>
					</div>
					<h4><a href="https://creativestudioa.admicro.vn" target="_blank">Tham khảo các dịch vụ của Creative Studio
							Athena</a></h4>
				</div>
			</section>
			<input type="hidden" id="imageShareSocialNetwork" value=""/>
		</main>

		<!--Script-->
		<script src="./plugin/jQuery/jquery.min.js"></script>
		<script src="./plugin/html2canvas.min.js"></script>

		<script>
			function continueBtn() {
				var click_state = localStorage.getItem("audio");
				if(click_state !== "disable") {
					document.getElementById('btnsound').play();
				} else {};

				var isplay = localStorage.getItem("is-playing");
				if(isplay == "play") {
					localStorage.setItem("action", "replay");
				} else {};
			};

			$(document).ready(function() {
				saveimg();
			});

			function saveimg() {
				var ele = document.getElementById('img2download');
				var w = parseInt(window.innerWidth);
				if(w < 576) {
					html2canvas(ele,{
						width: ele.scrollWidth,
                   		height: ele.scrollHeight
					}).then(canvas => {
						rendercanvas(canvas);
					});
				} else {
					html2canvas(ele).then(canvas => {
						rendercanvas(canvas);
					});
				}
			}

			function rendercanvas(canvas) {
				var image = canvas.toDataURL("image/png");
				var nameUser = "<?php  echo $post_title;; ?>";
				var contentImage = "<?php echo $post_content; ?>";
				
				$('#btn-download').attr('href', image).attr('target', '_blank');
				var host = '<?php echo URL_ROOT_PROJECT; ?>';
				$.ajax({
					url: host+'src/module/post.php?token=<?php echo $new_token; ?>',
					type: "POST",
					data: { image: image, myMessage: '<?php echo $randomString; ?>'},
					success: function (result) 
					{
						result = JSON.parse(result);
						if(result.ok === true){
							$('#imageShareSocialNetwork').val(result.data.image_url);
							$('#btn-download').attr('href', result.data.image_url);
						}
						else
						{
							console.log(result);
						}
					}
				})
			};

			$("#btn-download").click(function() {
				var image_link = $("#imageShareSocialNetwork").val();
				if( image_link !== "") {
					$('#btn-download').attr("download", "<?php echo $post_title; ?>");
				}else{
					alert('Không tạo được ảnh nhân vật')
				}
			});
			//share image
			$("#share-fb").click(function() {

				var image_link = $("#imageShareSocialNetwork").val();
				if( image_link !== "")
				{
					FB.ui({
						display: 'popup',
						method: 'share_open_graph',
						action_type: 'og.likes',
						action_properties: JSON.stringify({
							object:{
								'og:url': "https://creativestudioa.admicro.vn/game-boc-phot-cong-so/detail.php",
								'og:title': "Game Bóc phốt công sở",
								'og:description': "Game Bóc phốt công sở được phát triển bởi Creative Studio Athena",
								'og:type':"article",
								'og:locale':"vi_VN",
								'og:image': image_link
							},
						})
					}, function(response){
						if (response && response.post_id) {

						}
					});
				}
				else{
					alert('Không tạo được ảnh nhân vật')
				}
			});

			$("#share-mess").click(function() {

				FB.ui({
					display: 'popup',
					method: 'share',
					href: 'https://creativestudioa.admicro.vn/game-boc-phot-cong-so/',
				}, function(response){});

			});
		</script>
	</body>

	</html>
<?php
}
?>