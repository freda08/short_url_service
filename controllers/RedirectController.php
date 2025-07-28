<?php

namespace app\controllers;

use Yii;

use yii\web\Controller;
use app\models\Url;

class RedirectController extends Controller {
    public function actionIndex($code) {
        $url = Url::findOne(['url_code' => $code]);
        $url->logAccess(Yii::$app->request->getUserIP());

        return $this->redirect($url->url);
    }
}
