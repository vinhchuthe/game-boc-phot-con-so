<?php
require_once("./glb/cfglb.php");
require_once("./src/config/constant.php");
require_once("./src/config/database.php");
//Todo: Xử lý get ảnh từ idShare
$idShare = $_GET['idShare'];
$infoData = [];
if($idShare){
    $db = db_connect();
    try {
        $sql = "SELECT * FROM log_game WHERE id = $idShare";
        $stmt = $db->prepare($sql);
        if($stmt)
        {
            $data = $db->query($sql);
            $data->setFetchMode(PDO::FETCH_ASSOC);
            $infoData = $data->fetch();
        }
    }catch(PDOException $e){
        die("Error: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="icon" href="../wp-content/themes/creativestudio/dist/images/favicon.png">
    <link rel="stylesheet" href="./css/detail.css">
    <link rel="stylesheet" href="./css/customer.css">
    <title>Game bóc phốt công sở</title>

    <!-- Share fb -->
    
    <meta property="og:locale" content="vi_VN">
    <meta property="og:type" content="article">
    <meta property="og:title" content="Game Bóc phốt công sở">
    <meta property="og:description"
          content="Thử tài nhanh tay - tinh mắt “bóc phốt” đồng nghiệp xung quanh bạn. Game được phát triển bởi Creative Studio Athena.">
    <meta property="og:url" content="<?php echo URL_ROOT_PROJECT; ?>sharefb.php?idShare=<?php echo $idShare ?>">
    <meta property="og:site_name" content="Game Bóc phốt công sở">
    <meta property="og:image" content="<?php  if(empty($infoData['image_horizontal'])) { echo CLOUD_IMG_DOMAIN. $infoData['image']; } else { echo CLOUD_IMG_DOMAIN. $infoData['image_horizontal'];}  ?>">
    <meta property="og:image:width" content="500"/>
    <meta property="og:image:height" content="250"/>

</head>

<body>
<main id="detail-wrapper">
    <audio id="btnsound" src="./audio/button-sound.mp3"></audio>
    <section id="section-content">
        <div class="sharefb section-left">
            <div id="img2download" class="section-image">
                <img class="img-title" src="<?php  echo CLOUD_IMG_DOMAIN. $infoData['image']; ?>" alt="">
            </div>
            <div class="section-btn">
                <a id="continue-btn" href="<?php echo $infoData; ?>">Bóc phốt tiếp</a>
            </div>
        </div>
        <div class="sharefb section-right">
            <h1>"Bóc phốt" công sở</h1>
            <h3>Share luôn với 500 anh em thôi chứ đợi gì nữa!</h3>
            <h4><a href="https://creativestudioa.admicro.vn" target="_blank">Tham khảo 4 dịch vụ của Creative Studio
                    Athena</a></h4>
        </div>
    </section>
</main>
</body>

</html>
