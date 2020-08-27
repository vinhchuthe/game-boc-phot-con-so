<?php 
include_once('src/config/constant.php');
include_once('src/helper/token.php');
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

$token = token::generate(PROJECT_KEY_NAME, TOKEN_KEY);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="icon" href="../wp-content/themes/creativestudio/dist/images/favicon.png">
    <link rel="stylesheet" href="./css/game.css">
    <link rel="stylesheet" href="./css/customer.css">
    <link rel="stylesheet" href="./css/edit-custome.css">
    <link rel="stylesheet" href="./plugin/swiper/swiper.min.css">
    <title>Game bóc phốt công sở</title>
    <!--Script-->
    <script src="./plugin/jQuery/jquery.min.js"></script>
    <script src="./plugin/jQuery/Tweenmax.js"></script>
    <script src="./plugin/jQuery/draggable.js"></script>
    <script src="./plugin/niceScroll/nicescroll.min.js"></script>
    <script src="./plugin/swiper/swiper.min.js"></script>
    
    <script> 
		var ssepushSource = null;
        function panoramix() {

            var draggable = Draggable.create($("#drag"), {
                bounds: $("#imgwrapper"),
                edgeResistance: 1,
                type: "y,x",
                cursor: 'http://channel.mediacdn.vn/2020/6/8/icon-mouse-15916041285931269026525.png',
            })

        }

        function ClickBtn() { 
            var click_state = localStorage.getItem("audio");
            if(click_state !== "disable") {
                document.getElementById('btnsound').play();
            } else {};
        }

        $(document).ready(function() {
            var randomQuote;
            var randomNum; 
            getQuote();
            function getQuote(){
              
            var quotes = ["Ê, tôi đang action mà.",  
                          "Ủa, đang action thôi mà.", 
                          "action thôi mà cũng bị tóm à.",]
            
            randomNum = Math.floor(Math.random()*quotes.length);
              randomQuote = quotes[randomNum];
            }
              
            $(".overlay-gr .gif").click(function(event) { 
                 $('.txt-succ').hide();
                $(".txt-rand").hide();
                var show = $(this).data('content'); 
                $(this).toggleClass("active");
                $(this).parents().children(".txt-rand").show();
                $(this).parents().children('.txt-succ').show();
                $('.txt-rand span').text(randomQuote);
 
              getQuote();
              function walkText(node) {
                  if (node.nodeType == 3) { 
                    node.data = node.data.replace(/action/ig, show);
                  } 
                  if (node.nodeType == 1 && node.nodeName != "SCRIPT") {
                    for (var i = 0; i < node.childNodes.length; i++) { 
                      walkText(node.childNodes[i]);
                    }
                  }
                }
                walkText(document.body);

                $('#action_frame').html(show);
                var href_img = $(this).attr('src');
                $('#img-cv, #img-gif').attr("src",href_img);
                $('#inp_gif').attr("value", href_img);

                setTimeout(function(){
                    $('.txt-rand').hide();
                }, 2000);
            });
        });

        function loadFrame5(elm) {

            ClickBtn();
            var data_url_video = $(elm).attr('data-url_video');
            var data_url_img = $(elm).attr('data-url_img');
            var data_content = $(elm).attr("data-content");
            var storage = localStorage.getItem("key-type");
            var content_global = $(elm).attr('data-content-id');
            setTimeout(function(){
                $.ajax({
                    url: "frame/frame-5.php?token=<?php echo $token; ?>",
                    method: "POST",
                    data: {
                        content: data_content
                    },
                    success: function(data) {
                        $('#section-2').addClass("overllay").append(data);
                        if (storage == "image") {
                            console.log("download image");
                            $('.result-form-img img').attr('src', "./image/pic/" + data_url_img);
                            $('#image-url').val(data_url_img);
                        } else {
                            console.log("download video");
                            $('.result-form-img img').attr('src', "./image/pic/" + data_url_video);
                            $('#image-url').val(data_url_video);
                        };

                        $(document).on('keyup', function(evt) {
                            if (evt.keyCode == 27) {
                                console.log('Esc key pressed.');
                                $('#section-2').removeClass('overllay');
                                $('#result-form').remove();
                                $('#result-container').remove();
                            }
                        });

                    },
                    dataType: 'html'
                });
            }, 1000);
        }

        function closeFrame5(elm) {
            ClickBtn();
            $('#section-2').removeClass('overllay');
            $('#result-form').remove();
            $('#result-container').remove();
            $('.txt-succ').hide();
            elm.remove();
        }

        function regexErrForm() {
            var name = $('#title').val();
            var patt1 = /\s/g;
            var result = name.match(patt1);
            if (name.length <= 30 && name !== '')
			{
				if(typeof(ssepushSource) == 'object')
				{ 
					// ssepushSource.close();
				}
                $('#form-frame5-submit').submit();
            } else if (name === '') {
                $('#error-name').removeClass('hidden');
                $('#error-name').html('Tên viết không quá 30 ký tự');
            } else if (result) {
                $('#error-name').removeClass('hidden');
                $('#error-name').html('Tên viết không quá 30 ký tự');
            }
        }

        function setwidth() {
            var widthbg = $("#map-bg").width();
            $("#drag").css("width", widthbg + 'px');
            $("#overlay-wrapper").css("width", widthbg + 'px');
        };

        function changetype() {
            $(document).on("click",".type-btn", function() {    
                var type = $(this).attr("data-type");
                console.log(type);
                // Check browser support
                if (typeof(Storage) !== "undefined") {
                // Store
                localStorage.setItem("key-type", type);
                localStorage.setItem("is-playing", "play");
                } else {
                console.log("Sorry, your browser does not support Web Storage...");
                };
                $('#tutorial-wrapper').remove();
            });
        };

        function randOrder() {
            return (Math.round(Math.random()) - 0.5);
        }
    </script>
</head>

<body>
    <main>
        <audio id="audiogame" src="" autoplay loop></audio>
        <!-- <audio id="audiogame" src="./audio/audiogame.mp3" autoplay loop></audio> -->
        <audio id="btnsound" src="./audio/button-sound.mp3"></audio>
        <div class="preload">
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
            <div id="preload-content">
                <div class="preload-content-inner">
                    <div class="preload-content-inner-title">
                        <span>
                            "Bóc phốt"
                        </span>Công sở
                    </div>
                    <div class="preload-content-inner-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="492" height="47" viewBox="0 0 492 47">
                            <g id="loading" transform="translate(-691 -567)">
                                <g id="Vector_Smart_Object" data-name="Vector Smart Object" transform="translate(400.247 93.053)">
                                <rect id="Rectangle_5" data-name="Rectangle 5" width="478.663" height="34.21" rx="17.105" transform="translate(298.08 480.384)" fill="#5f752f"/>
                                <path id="Path_13" data-name="Path 13" d="M781.733,490.648a23.084,23.084,0,0,0-18.516-15.868l-1.058-.159-1.068-.057c-.714-.037-1.435-.075-2.085-.06l-4.008.009-8.016.019-16.032.038-32.064.075-127.654.3-.6,0-128.257-.708-128.257-.285a23.517,23.517,0,0,0-23.3,21.7,23.106,23.106,0,0,0,.971,8.63,23.487,23.487,0,0,0,18.838,16.148l1.077.161,1.088.059c.727.037,1.462.076,2.107.06l4.009-.009,8.016-.018,16.032-.036,32.063-.073,43.411-.24,20.718.189,64.128.345c85.5.262,171.009-.184,256.512-.4a23.119,23.119,0,0,0,22.9-21.336A22.712,22.712,0,0,0,781.733,490.648ZM503.276,516.861l-64.128.346-20.718.189-43.411-.241-32.063-.072-16.032-.036-8.016-.018-4.009-.009c-.691.012-1.29-.031-1.9-.064l-.914-.05-.9-.143a19.644,19.644,0,0,1-12.348-7.192,19.27,19.27,0,0,1-4.176-13.63,19.592,19.592,0,0,1,19.455-17.994l128.257-.284,128.257-.708.61,0,127.646.3,32.064.075,16.032.037,8.016.019,4.008.009c.687-.012,1.3.031,1.921.065l.932.051.922.146a20.046,20.046,0,0,1,12.6,7.339,19.677,19.677,0,0,1,4.261,13.911,19.99,19.99,0,0,1-19.854,18.361C674.285,517.045,588.781,516.6,503.276,516.861Z" fill="#030404"/>
                                <g id="Group_5" data-name="Group 5">
                                    <path id="Path_14" data-name="Path 14" d="M304.282,495.822a1.5,1.5,0,0,1-1.449-1.9c1.337-4.807,5.77-8.779,11.57-10.369,5.033-1.378,10.4-.966,15.128-.6a1.5,1.5,0,0,1-.23,3c-4.709-.36-9.575-.735-14.1.507-3.751,1.027-8.208,3.741-9.469,8.276A1.5,1.5,0,0,1,304.282,495.822Z" fill="#fff"/>
                                    <path id="Path_15" data-name="Path 15" d="M303.693,502.084h-.041a1.5,1.5,0,0,1-1.461-1.542c.011-.411.022-.616.033-.82.01-.184.02-.37.03-.739a1.5,1.5,0,1,1,3,.081c-.011.41-.022.615-.033.819-.01.185-.02.37-.03.74A1.5,1.5,0,0,1,303.693,502.084Z" fill="#fff"/>
                                </g>
                                </g>
                            </g>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <section id="section-2">

            <div id="imgwrapper">
                <div class="render-images">
                    <input type="text" readonly id="inp_gif">
                    <div class="box-frame text-center" id="frame-img">
                        <div class="title-frame"><img src="./image/icon/downloadimg-title.png" class="img-fluid" alt=""></div>
                        <div class="gif-frame"><img src="" class="img-fluid" alt="" id="img-cv" style="opacity: 0"></div>
                        <div class="text-frame">
                            <p><span id="name_frame"></span> vừa bị bóc phốt vì <span id="action_frame"></span> trong giờ làm</p> 
                        </div>
                    </div>
                    <img id="preview-frame" src="" alt="">

                    <div class="end-code">
                        <img id="img-top" src=""></img>
                        <img id="img-gif" src=""></img>
                        <p></p>
                        <canvas id="bitmap" style="display:none;"></canvas>
                        <img id="image">
                        <input type="hidden" name="imagebase">
                    </div>
                </div>
                <div id="drag">
                    <img id="map-bg" src="./image/pic/background-min.png" alt="">
                    <div id="overlay-wrapper">
                        <div class="overlay-gr" id="gr1">
                            <img class="npc" src="./image/pic/char5.png" alt="">
                            <img class="gif" src="./image/pic/nv5.gif" alt="" data-content="trang điểm" data-url_video="nv5.gif" data-url_img="nv5.png" onclick="loadFrame5(this)">
                            <div class="txt-succ"><img src="./image/pic/click-true.png"></div>
                        </div>
                        <div class="overlay-gr" id="gr2">
                            <img class="npc" src="./image/pic/char4.png" alt="">
                            <img class="gif" src="./image/pic/nv4.gif" alt="" data-content="ngủ gật" data-url_video="nv4.gif" data-url_img="nv4.png" onclick="loadFrame5(this)">
                            <div class="txt-succ"><img src="./image/pic/click-true.png"></div>
                        </div>
                        <div class="overlay-gr" id="gr3">
                            <img class="gif" src="./image/pic/nv10.gif" alt="" data-content="cày phim" data-url_video="nv10.gif" data-url_img="nv10.png" onclick="loadFrame5(this)">
                            <div class="txt-succ"><img src="./image/pic/click-true.png"></div>
                        </div>
                        <div class="overlay-gr" id="gr4">
                            <img class="npc" src="./image/pic/char1.png" alt="">
                            <img class="gif" src="./image/pic/nv1.gif" alt="" data-content="uống trà sữa" data-url_video="nv1.gif" data-url_img="nv1.png" onclick="loadFrame5(this)">
                            <div class="txt-succ"><img src="./image/pic/click-true.png"></div>
                        </div>
                        <div class="overlay-gr" id="gr5">
                            <img class="npc" src="./image/pic/nvp3.png" alt="">
                            <img class="gif" src="./image/pic/nvp3.gif" alt="" data-content="brainstorm" data-url_video="nvp3.gif" >
                            <div class="txt-rand"><img src="./image/pic/click-false.png"><span></span></div>
                        </div>
                        <div class="overlay-gr" id="gr6">
                            <img class="gif" src="./image/pic/nv7.gif" alt="" data-content="tự sướng" data-url_video="nv7.gif" data-url_img="nv7.png" onclick="loadFrame5(this)">
                            <div class="txt-succ"><img src="./image/pic/click-true.png"></div>
                        </div>
                        <div class="overlay-gr" id="gr7">
                            <img class="gif" src="./image/pic/nv9.gif" alt="" data-content="tám" data-url_video="nv9.gif" data-url_img="nv9.png" onclick="loadFrame5(this)">
                            <div class="txt-succ"><img src="./image/pic/click-true.png"></div>
                        </div>
                        <div class="overlay-gr" id="gr8">
                            <img class="npc" src="./image/pic/nvp4.gif" alt="" data-url_video="nvp4.gif" >
                            <img class="gif" src="./image/pic/nv8.gif" alt="" data-content="live stream bán hàng" data-url_video="nv8.gif" data-url_img="nv8.png" onclick="loadFrame5(this)">
                            <div class="txt-succ"><img src="./image/pic/click-true.png"></div>
                        </div>
                        <div class="overlay-gr" id="gr9">
                            <img class="npc" src="./image/pic/nvp5.gif" alt="" data-url_video="nvp5.gif" >
                            <img class="gif" src="./image/pic/nv6.gif" alt="" data-content="lướt Facebook thả thính" data-url_video="nv6.gif" data-url_img="nv6.png" onclick="loadFrame5(this)">
                            <div class="txt-succ"><img src="./image/pic/click-true.png"></div>
                        </div>
                        <div class="overlay-gr" id="gr10">
                            <img class="npc" src="./image/pic/char2.png" alt="">
                            <img class="gif" src="./image/pic/nv3.gif" alt="" data-content="quẹt Tinder" data-url_video="nv3.gif" data-url_img="nv3.png" onclick="loadFrame5(this)">
                            <div class="txt-succ"><img src="./image/pic/click-true.png"></div>
                        </div>
                        <div class="overlay-gr" id="gr11">
                            <img class="npc" src="./image/pic/char2.png" alt="">
                            <img class="gif" src="./image/pic/nv2.gif" alt="" data-content="ăn quà vặt" data-url_video="nv2.gif" data-url_img="nv2.png" onclick="loadFrame5(this)">
                            <div class="txt-succ"><img src="./image/pic/click-true.png"></div>
                        </div>
                        <div class="overlay-gr" id="gr12">
                            <img class="npc" src="./image/pic/nvp2.png" alt="">
                            <img class="gif" src="./image/pic/nvp2.gif" alt="" data-content="nghiên cứu tài liệu" data-url_video="nvp2.gif" >
                            <div class="txt-rand"><img src="./image/pic/click-false.png"><span></span></div>
                        </div>
                        <div class="overlay-gr" id="gr13">
                            <img class="npc" src="./image/pic/nvp1.png" alt="">
                            <img class="gif" src="./image/pic/nvp1.gif" alt="" data-content="gọi điện cho khách hàng" data-url_video="nvp1.gif" >
                            <div class="txt-rand"><img src="./image/pic/click-false.png"><span></span></div>
                        </div>
                        <div class="overlay-gr" id="gr14">
                            <img class="npc" src="./image/pic/nvp6.png" alt="">
                            <img class="gif" src="./image/pic/nvp6.gif" alt="" data-content="lau dọn chỗ ngồi" data-url_video="nvp6.gif">
                            <div class="txt-rand"><img src="./image/pic/click-false.png"><span></span></div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="tutorial-wrapper">
                <div class="tutorial-slider">
                    <div class="tutorial-title">
                        <p>Hướng dẫn chơi game</p>
                    </div>
                    <div class="swiper-container">
                        <div class="swiper-wrapper">
                            <!-- <div class="swiper-slide">
                                <div class="item">
                                    <img class="pc-img" src="./image/pic/tutorial-0.png" alt="">
                                    <img class="mb-img" src="./image/pic/mbtutorial-0.png" alt="">
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="item">
                                    <img class="pc-img" src="./image/pic/tutorial-1.jpg" alt="">
                                    <img class="mb-img" src="./image/pic/mbtutorial-1.png" alt="">
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="item">
                                    <img class="pc-img" src="./image/pic/tutorial-2.jpg" alt="">
                                    <img class="mb-img" src="./image/pic/mbtutorial-2.png" alt="">
                                </div>
                            </div> -->
                            <div class="swiper-slide">
                                <div class="item">
                                    <img class="pc-img" src="./image/pic/tutorial-3.png" alt="">
                                    <img class="mb-img" src="./image/pic/mbtutorial-3.png" alt="">
                                    <div class="section-content-btn">
                                        <h4>Lựa chọn hình thức “bóc phốt”</h4>
                                        <ul>
                                            <li>
                                                <button class="type-btn" type="button" onclick="changetype()" data-type="image">
                                                    <img src="./image/icon/btn-photo.png" alt="">
                                                </button>
                                            </li>
                                            <li>
                                                <button class="type-btn" type="button" onclick="changetype()" data-type="video">
                                                    <img src="./image/icon/btn-video.png" alt="">
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="swiper-pagination"></div>
                        <div class="tutorial-text">
                            <button type="button" class="btn-prev-slider" id="slide-prev">
                                <img class="pc-icon" src="./image/icon/arrow-active.png" alt="">
                                <img class="mb-icon" src="./image/icon/mb-arrow.png" alt="">
                            </button>
                            <button type="button" class="btn-next-slider" id="slide-next">
                                <img class="pc-icon" src="./image/icon/arrow-active-right.png" alt="">
                                <img class="mb-icon" src="./image/icon/mb-arrow-right.png" alt="">
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="result-wrapper">

                <div class="result-icon-group">
                    <div class="group-left">
                    <h1><a href="https://creativestudioa.admicro.vn">Creative <br>Studio <br>Athena</a></h1>
                    </div>
                    <div class="group-right">
                        <div class="group-icon icon1">
                            <a href="<?php echo URL_ROOT_PROJECT; ?>">
                                <img src="./image/icon/preload.png" alt="home">
                                <span>Reload</span>
                            </a>
                        </div>
                        <div class="group-icon icon2">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=https://creativestudioa.admicro.vn/game-boc-phot-cong-so/">
                                <img src="./image/icon/icon-share.png" alt="share">
                                <span>Share</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="result-content-group">
                    <h3>Xem ai vừa bị bắt quả tang nào</h3>
                    <div class="result-group">
                        <ul id="urlResultMsg">
                            <li id="ticontainer">
                                <div class="tiblock">
                                    <div class="tidot"></div>
                                    <div class="tidot"></div>
                                    <div class="tidot"></div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="result-bot-group">
                    <div class="result-player">
                        <div class="user-icon">
                            <div class="rand-number">
                                <input id="min" type="number" class="d-none" placeholder="Enter a number" value="50">
                                <input id="max" type="number" class="d-none" placeholder="Enter a number" value="150">
                                <button id="clc-rand" class="d-none" onclick="generateRandomNumber();"> Generate</button> 
                            </div>
                            <img src="./image/icon/user-icon.png" alt="">
                            <span id="display">180</span><label>Người chơi cùng bạn</label>
                        </div>
                    </div>
                    <div class="result-audio">
                        <button type="button" id="btn-mute">
                            <img src="./image/icon/icon-volume.png" alt="">
                        </button>
                    </div>
                </div>
            </div>


        </section>
    </main>


</body>

<script>
    $(window).on("load", function() {   
        console.log("ready");
        $(".preload").css("display", "none");

        $('#clc-rand').click();
    });
    function generateRandomNumber() { 
      var min = parseInt(document.getElementById('min').value);
      var max = parseInt(document.getElementById('max').value);
      var rand = Math.floor(Math.random()* (max - min + 1)) + min;
      if (min == 1 && max == 30) {rand = 6}
      document.getElementById('display').innerText = rand
    }
    setInterval(function generateRandomNumber() {
        $('#clc-rand').click();
    }, 5000)


    $(window).on("load", function() {
        setwidth();
        $(".preload").fadeOut();
        
    });

    TweenMax.set($("#Rectangle_5"),{css:{width:"10%"}});
    TweenMax.to($("#Rectangle_5"),30,{css:{width:"97%"}});

    $(document).ready(function() {

        $(window).keydown(function(event){
            if(event.keyCode == 13) {
            event.preventDefault();
            regexErrForm();
            }
        });

        var audiokey = localStorage.getItem("audio");
        var audio = document.getElementById("audiogame");
        if(audiokey == "disable") {
            audio.muted = true;
        } else { 
            audio.muted = false;
        };

        $(document).on("click", "#result-wrapper", function() {
            var windowWidth = $(window).width();
            if (windowWidth < 768) {
                $("#result-wrapper").toggleClass("collapse");
            }
        });

        // load success
        panoramix();
        $('.overlay-gr').removeClass('active').sort(randOrder).slice(0, 3).addClass('active');
        
        var swiperTutorial = new Swiper('.tutorial-slider .swiper-container', {
            pagination: {
                el: '.swiper-pagination',
            },
            effect: 'fade',
            navigation: {
                nextEl: '.btn-next-slider',
                prevEl: '.btn-prev-slider',
            },
        });

        var replay = localStorage.getItem("action");
        if(replay == "replay") {
            swiperTutorial.slideTo(3);
            localStorage.removeItem("key-type");
        } else {
            swiperTutorial.slideTo(0);
        };

        $(".result-group").niceScroll({
            cursorwidth: "7px",
            cursorcolor: '#007ed7',
        });
		
		initMsg('', '<?php echo $token; ?>');
    });
	
	function initMsg(nextKey, token)
	{
		$.ajax({
			url: '<?php echo URL_ROOT_PROJECT; ?>src/module/initmsg.php?token=' + token,
			type: 'GET',
			data: {nxt: nextKey},
			success: function (response) {
				response = response.trim();
				try {
					var objData = jQuery.parseJSON(response);
					// error
					if(objData.errmsg == 'INVALID_TOKEN' || objData.errmsg != '')
					{
						//window.location.reload(true);
					}
					else if(objData.errmsg == '') // success
					{
						jQuery.each(objData.data, function(idx, val){
							var strpreappend = '<li>' + val + '</li>';
							$('#urlResultMsg').append(strpreappend);
						});
						if(window.EventSource)
						{
							ssepushSource = new EventSource('<?php echo URL_ROOT_PROJECT; ?>src/module/push.php?token=' + objData.token + '&nxt=' + objData.next);
							
							ssepushSource.addEventListener("message", function(event){
								if(event.data == 'invalid_token')
								{
									ssepushSource.close();
								}
								else
								{
									var data = JSON.parse(event.data);
									jQuery.each(data, function(idx, itemVal){
										var strpreappend = '<li>' + itemVal + '</li>';
										$('#urlResultMsg').prepend(strpreappend);
									});
								}
							});
						}
						else
						{
							setTimeout(function(){
								initMsg(objData.next, objData.token);
							}, 5000);
						}
					}
					else
					{
						//
					}
				}
				catch(err) {
					//err.message;
				}
				
				
			},
			error: function( jqXHR, textStatus, errorThrown ) {
				//console.info(textStatus);
			}
		}).done(function(){
			//hide_loading();
		});
	}
</script>

</html>