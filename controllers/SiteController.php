<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\models\Url;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use yii\httpclient\Client;

class SiteController extends Controller {
    /**
     * {@inheritdoc}
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex() {
        $model = new Url();

        return $this->render('index', [
                    'model' => $model
        ]);
    }

    public function actionGenerate() {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new Url();
        $model->load(Yii::$app->request->post());

        if (!$this->checkAvailability($model->url)) {
            return [
                'success' => false,
                'error' => 'Данный URL не доступен'
            ];
        }

        $existingModel = Url::findOne(['url' => $model->url]);

        if ($existingModel) {
            return $this->generateQRData($existingModel->url_code);
        }

        $model->url_code = Yii::$app->security->generateRandomString(6);

        if (!$model->validate()) {
            return [
                'success' => false,
                'error' => $model->getFirstError('url') ?: 'Ошибка валидации'
            ];
        }

        if (!$model->save()) {
            $errors = $model->getFirstErrors();
            return [
                'success' => false,
                'error' => 'Ошибка сохранения: ' . reset($errors)
            ];
        }

        return $this->generateQRData($model->url_code);
    }

    private function checkAvailability($url) {
        try {
            $client = new Client();
            $response = $client->createRequest()
                    ->setMethod('HEAD')
                    ->setUrl($url)
                    ->setOptions([
                        'timeout' => 8,
                        'followLocation' => true,
                        'maxRedirects' => 3
                    ])
                    ->send();

            $allowedCodes = array_merge(range(200, 399), [405]);
            return in_array($response->statusCode, $allowedCodes);
        } catch (\Throwable $e) {
            return false;
        }
    }

    private function generateQRData($code) {
        $qrCode = new QrCode(Yii::$app->urlManager->createAbsoluteUrl(['redirect/index', 'code' => $code]));
        $writer = new PngWriter();
        $qrData = $writer->write($qrCode)->getString();

        return [
            'success' => true,
            'short_url' => Yii::$app->urlManager->createAbsoluteUrl(['redirect/index', 'code' => $code]),
            'qr_code' => 'data:image/png;base64,' . base64_encode($qrData)
        ];
    }
}
