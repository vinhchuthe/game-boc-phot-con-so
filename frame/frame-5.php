<?php 
include_once('../src/helper/common.php');

$token = isset($_GET['token']) ? trim($_GET['token']) : '';
$token = removeAllTags($token);
?>
<div id="result-container">
    <button type="button" class="btnclose" onclick="closeFrame5(this)">
        <img src="./image/icon/btn-close.png" alt="">
    </button>
    <div id="result-form">
        <div class="result-form-img">
            <img src="./image/pic/rs-1.jpg" alt="">
        </div>
        <div class="result-form-content">
            <h3>"Nạn nhân" của bạn cần một cái tên</h3>
            <h4>* tối đa 30 ký tự</h4>
        </div>
        <form action="detail.php?token=<?php echo $token; ?>" method="post" id="form-frame5-submit">
            <input type="text"  name="title" id="title" required >
            <p id="error-name"></p>
            <input type="hidden" value="" name="url" id="image-url">
            <input type="hidden" value="<?php echo isset($_POST['content']) ? htmlentities($_POST["content"], ENT_QUOTES | ENT_IGNORE, "UTF-8") : ''; ?>" name="content" id="image-content">
            <button type="button" class="form-btn-submit" onclick="regexErrForm()">Xác nhận</button>
        </form>
    </div>

</div>
<script>
    $( document ).ready(function() {
      $('input#title').focus();
    });
</script>