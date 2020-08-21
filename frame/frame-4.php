<?php require('../base.php');?>
<div id="result-wrapper">
    <h3>Xem ai vừa bị bắt quả tang nào</h3>
    <div class="result-img">
        <img src="./image/pic/rs-1.jpg" alt="">
    </div>
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