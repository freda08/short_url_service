<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Сервис коротких ссылок';
?>

<?php
$form = ActiveForm::begin([
        'id' => 'url-form',
        'enableClientValidation' => true,
        'enableAjaxValidation' => false
    ])
?>

<div class="mb-3">
    <?=
    $form->field($model, 'url', [
        'options' => ['class' => 'w-100'],
        'inputOptions' => [
            'class' => 'form-control',
            'placeholder' => 'Введите URL'
        ],
        'template' => "{input}\n{error}",
        'errorOptions' => ['class' => 'invalid-feedback d-block']
    ])->label(false)
    ?>

    <div class="text-center mt-4">
        <?=
        Html::submitButton('OK', [
            'class' => 'btn btn-primary w-100',
            'id' => 'submit-btn'
        ])
        ?>
    </div>

</div>

<?php ActiveForm::end(); ?>

<div id="result" class="mt-4" style="display:none; text-align: center">
    <p>Короткая ссылка: <a id="short-url" target="_blank" href="#"></a></p>
    <img id="qr-code" src="" alt="QR Code" class="img-thumbnail">
</div>

<div id="error-alert" class="alert alert-danger mt-3" style="display: none;"></div>

<?php
$js = <<<JS
$('#url-form').on('beforeSubmit', function(e) {
    e.preventDefault();
    
    $('#result').hide();
    $('#result-message').text('');
        
    $.ajax({
        url: '/site/generate',
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            if (response.success) {
                $('#short-url').attr('href', response.short_url).text(response.short_url);
                $('#qr-code').attr('src', response.qr_code);
                $('#result').show();
                $('#error-alert').hide();
            } else {
                $('#error-alert').text(response.error);
                $('#error-alert').show();
            }
        },
        error: function(err) {
            $('#error-alert').text('Произошла ошибка при обработке запроса ' + err.responseJSON.message);
            $('#error-alert').show();
        }
    });
}).on('submit', function(e) {
    e.preventDefault();
});
JS;

$this->registerJs($js);
?>