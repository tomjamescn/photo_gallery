<?php

namespace app\controllers;

use app\models\Image;
use app\models\Tag;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class FavoriteController extends Controller
{


    public function actionIndex()
    {
        $getPhotoInfoListUrl = '?r=favorite/get-photo-info-list';
        return $this->render('index', ['getPhotoInfoListUrl' => $getPhotoInfoListUrl]);
    }


    public function actionGetPhotoInfoList($sn, $page = 1, $numPerPage=50)
    {
        $start = ($page - 1)*$numPerPage;

        $sql = <<<eof
        select image.*
        from image left join favorite on image.id = favorite.imageId
        where favorite.isDelete = 0
eof;
        $command = Yii::$app->db->createCommand($sql);
        $imageList = $command->queryAll();

        $output = [];

        //更改链接
        foreach ($imageList as &$image) {
            $t = [];
            $t['id'] = $image['id'];
            $t['url'] = './image/'.Image::getUrlFromResizedFilePath($image['filePath']);
//            $t['rawUrl'] = './rawImage/'.Image::getUrlFromRawFilePath($image['rawFilePath']);
            $t['thumbnailUrl'] = './image/'.Image::getUrlFromResizedFilePath($image['thumbnailFilePath']);

            $t['isFavorite'] = true;

            $output[] = $t;
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
            'sn' => $sn,
            'data' => $output,
        ];
    }


}
