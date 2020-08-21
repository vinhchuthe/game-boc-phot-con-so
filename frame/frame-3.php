<?php require('../base.php');?>
<section id="section-2">

    <div id="imgwrapper">
        <div id="drag">
            <img id="map-bg" src="./image/pic/background-min.png" alt="">
            <div id="overlay-wrapper">
                <div class="overlay-gr" id="gr1">
                    <img class="npc" src="./image/pic/char5.png" alt="">
                    <img class="gif" src="./image/pic/nv5.gif" alt="" data-content="Trang điểm." data-url_video="nv5.gif" data-url_img="nv5.png" onclick="loadFrame5(this)">
                </div>
                <div class="overlay-gr" id="gr2">
                    <img class="npc" src="./image/pic/char4.png" alt="">
                    <img class="gif" src="./image/pic/nv4.gif" alt="" data-content="Ngủ gật." data-url_video="nv4.gif" data-url_img="nv4.png" onclick="loadFrame5(this)">
                </div>
                <div class="overlay-gr" id="gr3">
                    <img class="gif" src="./image/pic/nv10.gif" alt="" data-content="Cày phim." data-url_video="nv10.gif" data-url_img="nv10.png" onclick="loadFrame5(this)">
                </div>
                <div class="overlay-gr" id="gr4">
                    <img class="npc" src="./image/pic/char1.png" alt="">
                    <img class="gif" src="./image/pic/nv1.gif" alt="" data-content="Uống trà sữa." data-url_video="nv1.gif" data-url_img="nv1.png" onclick="loadFrame5(this)">
                </div>
                <div class="overlay-gr" id="gr5">
                    <img class="npc" src="./image/pic/overlayc.png" alt="">
                    <img class="gif" src="./image/pic/nvp3.gif" alt="" data-content="Tám." data-url_video="nvp3.gif" onclick="loadFrame4(this)">
                </div>
                <div class="overlay-gr" id="gr6">
                    <img class="gif" src="./image/pic/nv7.gif" alt="" data-content="Cày phim." data-url_video="nv7.gif" data-url_img="nv7.png" onclick="loadFrame5(this)">
                </div>
                <div class="overlay-gr" id="gr7">
                    <img class="gif" src="./image/pic/nv9.gif" alt="" data-content="Tự sướng." data-url_video="nv9.gif" data-url_img="nv9.png" onclick="loadFrame5(this)">
                </div>
                <div class="overlay-gr" id="gr8">
                    <img class="npc" src="./image/pic/nvp4.gif" alt="" data-url_video="nvp4.gif" onclick="loadFrame4(this)">
                    <img class="gif" src="./image/pic/nv8.gif" alt="" data-content="Live stream bán hàng." data-url_video="nv8.gif" data-url_img="nv8.png" onclick="loadFrame5(this)">
                </div>
                <div class="overlay-gr" id="gr9">
                    <img class="npc" src="./image/pic/nvp5.gif" alt="" data-url_video="nvp5.gif" onclick="loadFrame4(this)">
                    <img class="gif" src="./image/pic/nv6.gif" alt="" data-content="Lướt Facebook thả thính." data-url_video="nv6.gif" data-url_img="nv6.png" onclick="loadFrame5(this)">
                </div>
                <div class="overlay-gr" id="gr10">
                    <img class="npc" src="./image/pic/char2.png" alt="">
                    <img class="gif" src="./image/pic/nv3.gif" alt="" data-content="Quẹt Tinder." data-url_video="nv3.gif" data-url_img="nv3.png" onclick="loadFrame5(this)">
                </div>
                <div class="overlay-gr" id="gr11">
                    <img class="npc" src="./image/pic/char2.png" alt="">
                    <img class="gif" src="./image/pic/nv2.gif" alt="" data-content="Ăn quà vặt." data-url_video="nv2.gif" data-url_img="nv2.png" onclick="loadFrame5(this)">
                </div>
                <div class="overlay-gr" id="gr12">
                    <!-- <img class="npc" src="./image/pic/nvp2.png" alt=""> -->
                    <img class="gif" src="./image/pic/nvp2.gif" alt="" data-content="Nghiên cứu tài liệu." data-url_video="nvp2.gif" onclick="loadFrame4(this)">
                </div>
                <div class="overlay-gr" id="gr13">
                    <!-- <img class="npc" src="./image/pic/nvp1.png" alt=""> -->
                    <img class="gif" src="./image/pic/nvp1.gif" alt="" data-content="Gọi điện cho khách hàng." data-url_video="nvp1.gif" onclick="loadFrame4(this)">
                </div>
                <div class="overlay-gr" id="gr14">
                    <img class="npc" src="./image/pic/nvp6.png" alt="">
                    <img class="gif" src="./image/pic/nvp6.gif" alt="" data-content="lau dọn chỗ ngồi." data-url_video="nvp6.gif" onclick="loadFrame4(this)">
                </div>
            </div>
        </div>
    </div>

    <div id="tutorial-wrapper">
        <!-- <button type="button" class="btnclose" onclick="closeTutorial()">
            <img src="./image/icon/btn-close.png" alt="">
        </button> -->
        <div class="tutorial-slider">
            <div class="swiper-container">
                <div class="swiper-wrapper">
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
                    </div>

                </div>
                <div class="swiper-pagination"></div>
                <ul class="tutorial-text">
                    <li>
                        <button type="button" onclick="closeTutorial()">Bỏ qua</button>
                    </li>
                    <li>
                        <button type="button" class="btn-next-slider" id="slide-next">Tiếp theo</button>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div id="result-wrapper">
        <div class="result-img">
            <img src="" alt="">
        </div>
        <h3>Xem ai vừa bị bắt quả tang nào</h3>
        <div class="result-group">
            <ul>
                <?php foreach ( $arr as $key => $value ): ?>
                    <li data-content="<?= $value ?>" data-content-id="<?= $key ?>">
                        <?= $value ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>


</section>