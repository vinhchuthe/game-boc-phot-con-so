<?php 
include_once('src/config/constant.php');
session_set_cookie_params(
	0,
	'/',
	'.' . $_SERVER['HTTP_HOST'],
	(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? TRUE : FALSE),
	TRUE // HttpOnly; Yes, this is intentional and not configurable for security reasons
);

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
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <link rel="icon" href="../wp-content/themes/creativestudio/dist/images/favicon.png">
    <link rel="stylesheet" href="./css/mainstyle.css">
    <link rel="stylesheet" href="./plugin/swiper/swiper.min.css">
    <link rel="stylesheet" href="./plugin/paranoma/panoramix.min.css">
    <meta property="og:locale" content="vi_VN"> 
    <meta property="og:type" content="article">
    <meta property="og:title" content="Game Bóc phốt công sở">
    <meta property="og:description"
          content="Thử tài nhanh tay - tinh mắt “bóc phốt” đồng nghiệp xung quanh bạn. Game được phát triển bởi Creative Studio Athena.">
    <meta property="og:url" content="https://creativestudioa.admicro.vn/game-boc-phot-cong-so/">
    <meta property="og:site_name" content="Game Bóc phốt công sở">
    <meta property="og:image" content="https://creativestudioa.admicro.vn/game-boc-phot-cong-so/image/pic/cover-fb.jpg">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:description"
          content="Thử tài nhanh tay - tinh mắt “bóc phốt” đồng nghiệp xung quanh bạn. Game được phát triển bởi Creative Studio Athena.">
    <meta name="twitter:title" content="Game Bóc phốt công sở">
    <title>Game bóc phốt công sở</title>
    <!--Script-->
    <script src="./plugin/jQuery/jquery.min.js"></script> 
    <script src="./plugin/jQuery/Tweenmax.js"></script>
    <script src="./plugin/jQuery/draggable.js"></script>
    <script src="./plugin/niceScroll/nicescroll.min.js"></script> 
    <script src="./plugin/swiper/swiper.min.js"></script>
    <script> 

        $(function () {
            $(document).keydown(function (objEvent) {
                if (objEvent.ctrlKey) {
                    if (objEvent.keyCode == 65) {

                        return false;
                    }
                }
               
            });
            
        });
        var url_img_global = "";
        var content_global = "";


        function panoramix() {

            var draggable = Draggable.create($("#drag"), {
                bounds: $("#imgwrapper"),
                edgeResistance:1,
                type:"y,x",
                cursor: 'http://channel.mediacdn.vn/2020/6/8/icon-mouse-15916041285931269026525.png',
            })

        }

        function ClickBtn() {
            var click_state = localStorage.getItem("audio");
            if(click_state !== "disable") {
                document.getElementById('btnsound').play();
            } else {};
        }

        function loadFrame2() {
            ClickBtn();
            $.ajax({
                url: "frame/frame-2.php",
                success: function (data) {
                    $('#section-content-1').remove();
                    $('#section-1').append(data);
                },
                dataType: 'html'
            });
        }

        
        function playgame() {
            window.location.href="<?php echo URL_ROOT_PROJECT; ?>game.php";
        };

    </script>
</head>
<body>

<main>
    <audio id="audiogame" src="./audio/audiogame.mp3" autoplay loop></audio>
    <audio id="btnsound" src="./audio/button-sound.mp3"></audio>

        <section id="section-1">
            <header>
                <div class="col-left">
                    <h1><a href="https://creativestudioa.admicro.vn">Creative <br>Studio <br>Athena</a></h1>
                </div>
                <div class="col-right">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=https://creativestudioa.admicro.vn/game-boc-phot-cong-so/">
                        <img src="./image/icon/icon-share.png" alt="">
                        <span>Share</span>
                    </a>
                </div>


            </header>
            <div id="section-content-1">
                <div class="section-content">
                    <div class="section-content-title">
                    <span>
                        "Bóc phốt"
                    </span>Công sở
                    </div>
                    <div class="section-content-subtitle">
                        Game được phát triển <br>
                        bởi <a href="https://creativestudioa.admicro.vn/" title="Creative Studio Athena">Creative
                            Studio
                            Athena</a>
                    </div>
                    <div class="section-content-btn">
                        <button type="button" onclick="playgame()">Tham gia ngay</button>
                    </div>
                </div>
            </div>

            <button type="button" id="btn-mute">
                <img src="./image/icon/icon-volume.png" alt="">
            </button>
        </section>
</main>



</body>
<script>
    var audio = document.getElementById("audiogame");
    $('#btn-mute').click(function () {
        $(this).toggleClass('disabled');
        if($(this).hasClass("disabled")) {
            localStorage.setItem("audio", "disable");
            audio.muted = true;
        } else {
            localStorage.removeItem("audio");
            audio.muted = false;
        };
    });
</script>
<script>
        $(document).ready(function() {
            localStorage.removeItem("key-type");
            localStorage.removeItem("is-playing");
            localStorage.removeItem("action");
            localStorage.removeItem("audio");
            
            $(document).on( "click", "#result-wrapper", function(){
                var windowWidth = $(window).width();
                if(windowWidth < 768){
                    $("#result-wrapper").toggleClass("collapse");
                }
            } );
        });
        
</script>
</html>