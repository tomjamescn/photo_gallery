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

class TagController extends Controller
{


    public function actionIndex()
    {
        $tagList = Tag::find()->asArray()->all();
        return $this->render('index', ['tagList' => $tagList]);
    }


}
