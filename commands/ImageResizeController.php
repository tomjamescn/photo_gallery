<?php
/**
 * Created by PhpStorm.
 * User: tomjamescn
 * Date: 15/12/5
 * Time: 上午6:47
 */

namespace app\commands;

use Yii;
use yii\console\Controller;
use Intervention\Image\ImageManagerStatic as Image;


class ImageResizeController extends Controller
{
    /**
     * 为图片生成信息
     */
    public function actionInit()
    {

        $imageRootPath = Yii::$app->params['rootPath'];
        $outputPath = Yii::$app->params['outputPath'];
        $resizeWidth = Yii::$app->params['resizeWidth'];
        $thumbnailWidth = Yii::$app->params['thumbnailWidth'];

        $filePathList = $this->listAllFiles($imageRootPath, ['/jpg/i']);

        Yii::trace(print_r($filePathList, true));

        foreach ($filePathList as $filePath) {

            Yii::trace('mem usage:'.(round(memory_get_usage(true)/1024/1024, 2)).' MB');

            if($this->isResized($filePath)) {
                Yii::trace('this image is resized:'.$filePath);
                continue;
            }

            $resizeFilePath = $this->getFilePath($outputPath, $filePath, $resizeWidth);
            $thumbnailFilePath = $this->getFilePath($outputPath, $filePath, $thumbnailWidth);
            $exif = Image::make($filePath)->exif();
            Image::make($filePath)->resize($resizeWidth, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($resizeFilePath);

            Image::make($filePath)->resize($thumbnailWidth, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($thumbnailFilePath);


            $image = new \app\models\Image();
            $image->rawFilePath = $filePath;
            $image->filePath = $resizeFilePath;
            $image->thumbnailFilePath = $thumbnailFilePath;
            $now = date('Y-m-d H:i:s');
            $image->createTime = $now;
            $image->updateTime = $now;
            $image->exif = json_encode([
                'Model' => isset($exif['Model']) ? $exif['Model'] : '未知',
                'FileDateTime' => isset($exif['FileDateTime']) ? date('Y-m-d H:i:s', $exif['FileDateTime']) : '未知',
            ]);
            $image->save();
        }


    }

    public function actionTestIsResized($rawFilePath)
    {
        $this->isResized($rawFilePath);


    }

    private function isResized($rawFilePath)
    {
        $image = \app\models\Image::findOne(['rawFilePath' => $rawFilePath]);
        return $image !== null;
    }

    private function getFilePath($root, $filePath, $targetWidth)
    {
        $md5 = md5_file($filePath);
        $subDir = $targetWidth.'/'.substr($md5, 0, 2);
        $filePathInfo = pathinfo($filePath);
        $dirPath = $root.'/'.$subDir;
        if(!file_exists($dirPath)) {
            mkdir($dirPath, 0777, true);
        }
        return $dirPath.'/'.$filePathInfo['filename'].'_'.$targetWidth.'.'.$filePathInfo['extension'];
    }

    private function listAllFiles($rootPath, $patternList = [], $excludePatternList = [])
    {

        Yii::beginProfile(__METHOD__);

        if(! is_dir($rootPath)) {
            return false;
        }

        $filePathList = [];
        $dirs = array( $rootPath);
        while( NULL !== ($dir = array_pop( $dirs)))
        {
            if( $dh = opendir($dir))
            {
                while( false !== ($file = readdir($dh)))
                {
                    if( $file == '.' || $file == '..')
                        continue;
                    $path = $dir . '/' . $file;
                    if( is_dir($path))
                        $dirs[] = $path;
                    else
                        $filePathList[] = $path;
                }
                closedir($dh);
            }
        }

        if (!empty($patternList)) {
            $whiteFilePathList = [];
            foreach ($filePathList as $filePath) {
                foreach ($patternList as $pattern) {
                    if(preg_match($pattern, $filePath)) {
                        $whiteFilePathList[] = $filePath;
                        break;
                    }
                }
            }

            $filePathList = [];
            foreach ($whiteFilePathList as $filePath) {
                $flag = false;
                foreach ($excludePatternList as $pattern) {
                    if(preg_match($pattern, $filePath)) {
                        $flag = true;
                        break;
                    }
                }

                if($flag === false) {
                    $filePathList[] = $filePath;
                }
            }
        }



        Yii::endProfile(__METHOD__);


        return $filePathList;
    }
}