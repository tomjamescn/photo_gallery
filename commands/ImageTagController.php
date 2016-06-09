<?php
namespace app\commands;

use app\models\Image;
use app\models\ImageTag;
use app\models\Tag;
use yii\console\Controller;


class ImageTagController extends Controller
{

    public function actionInit()
    {
        $imageList = Image::find()->asArray()->all();
        foreach ($imageList as $image) {
            $rawFilePath = $image['rawFilePath'];
            $match = [];
            if (preg_match('/\/homeNASDownloads\/photo_[0-9\-]+\/?(.*)/', dirname($rawFilePath), $match)) {
                $tagName = $match[1];

                if(empty($tagName)) {
                    continue;
                }

                $tag = Tag::find()->where(['tagName' => $tagName])->one();
                if($tag === null) {
                    $tag = new Tag();
                    $tag->tagName = $tagName;
                    $tag->isDelete = false;
                    $now = date('Y-m-d H:i:s');
                    $tag->createTime = $now;
                    $tag->updateTime = $now;

                    $tag->save();
                }




                $imageTag = ImageTag::find()->where(['imageId' => $image['id'], 'tagId' => $tag->id])->one();
                if($imageTag == null) {
                    $imageTag = new ImageTag();
                    $imageTag->imageId = $image['id'];
                    $imageTag->tagId = $tag->id;
                    $imageTag->isDelete = false;
                    $imageTag->createTime = $now;
                    $imageTag->updateTime = $now;

                    $imageTag->save();
                }


            }
        }
    }

    public function actionTest()
    {
//        $rawFilePath = '/homeNASDownloads/photo_2011-2012/DSC_0155.JPG';
//        $match = [];
//        if (preg_match('/\/homeNASDownloads\/photo_[0-9\-]+(.*)/', dirname($rawFilePath), $match)) {
////        if (preg_match('/\/homeNASDownloads\/(.*)/i', $rawFilePath, $match)) {
//            var_dump($match);
//        }else{
//            echo "o my god!\n";
//        }

        $image = Image::find()->where(['id' => 2])->one();
        var_dump($image->tags);
    }
}
