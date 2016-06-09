<?php

namespace app\controllers;

use app\models\Favorite;
use app\models\Image;
use app\models\Tag;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex($tagId = 0)
    {
        $getPhotoInfoListUrl = '?r=site/get-photo-info-list';
        return $this->render('index', ['tagId' => $tagId, 'getPhotoInfoListUrl' => $getPhotoInfoListUrl]);
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionGetPhotoInfoList($sn, $page = 1, $numPerPage=50, $tagId = 0)
    {
        $start = ($page - 1)*$numPerPage;

        if($tagId == 0) {
            $imageList = Image::find()->limit($numPerPage)->offset($start)->asArray()->all();
        }else{
            $imageList = Image::find()->leftJoin('image_tag',
                'image.id = image_tag.imageId')
                ->where(['image_tag.tagId' => $tagId])->limit($numPerPage)->offset($start)->asArray()->all();
        }

        $imageIdList = [];
        foreach ($imageList as $image) {
            $imageIdList[] = $image['id'];
        }

        //是否收藏
        $favoriteList = Favorite::find()->where(['isDelete' => 0, 'imageId' => $imageIdList])->asArray()->all();
        $favoriteMap = [];
        foreach ($favoriteList as $favorite) {
            $favoriteMap[$favorite['imageId']] = true;
        }

        $output = [];

        //更改链接
        foreach ($imageList as &$image) {
            $t = [];
            $t['id'] = $image['id'];
            $t['url'] = './image/'.Image::getUrlFromResizedFilePath($image['filePath']);
//            $t['rawUrl'] = './rawImage/'.Image::getUrlFromRawFilePath($image['rawFilePath']);
            $t['thumbnailUrl'] = './image/'.Image::getUrlFromResizedFilePath($image['thumbnailFilePath']);

            $t['isFavorite'] = isset($favoriteMap[$image['id']]) ? true:false;

            $output[] = $t;
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
            'sn' => $sn,
            'data' => $output,
        ];
    }

    public function actionDoFavorite($imageId)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $favorite = Favorite::find()->where(['imageId' => $imageId])->one();
        if($favorite != null) {
            if($favorite->isDelete == 1) {
                $favorite->isDelete = 0;
                $favorite->update();
            }else{
                $favorite->isDelete = 1;
                $favorite->update();
            }

        }else{
            $favorite = new Favorite();
            $favorite->imageId = $imageId;
            $favorite->isDelete = 0;
            $now = date('Y-m-d H:i:s');
            $favorite->createTime = $now;
            $favorite->updateTime = $now;
            $favorite->save();
        }

        return [
            'code' => 0,
        ];

    }



}
