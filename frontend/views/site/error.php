<?php

use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
       服务器在处理您的请求过程中，发生以上错误。 The above error occurred while the Web server was processing your request.
    </p>
    <p>
    如果是服务器错误，请联系我们。谢谢！     Please contact us if you think this is a server error. Thank you.
   
    </p>

</div>
