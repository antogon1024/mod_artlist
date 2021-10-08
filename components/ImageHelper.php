<?php


namespace app\components;


use app\models\Genre;
use app\models\user\UserGenre;
use app\modules\artlist\models\user\UserMedia;
use app\models\user\UserType;
use DateTime;
use Intervention\Image\ImageManager;
use Yii;
use yii\helpers\BaseFileHelper;

class ImageHelper
{
    /**
     * @param UserMedia $media
     * @param $width
     * @return string
     * @throws \Exception
     */

    //public $host = 'http://artlist';
    public static $host = 'http://artlist';

    //private $hostFull = 'https://images.artlist.pro';
    public $hostFull = 'http://artlist';

    //private $pathFull = '/var/www/www-root/data/www/artlist.pro';
    public static $pathFull = '/var/www/artlist';

    public static function thumb(UserMedia $media, $width)
    {
        //$bpath = Yii::getAlias('@app').'/web';
        $bpath = '/var/www/artlist/web';

        if(!$media)
            return Yii::$app->request->hostInfo."/images/_system/video_null.png";

        $datetime = new DateTime($media->created_date);

        $year  = $datetime->format('Y');
        $month = $datetime->format('m');
        $day   = $datetime->format('d');
		
		$oldprevfile = $bpath."/images/photos/o/p/".$media->user->city_id.'/'.$media->user_type_id.'/'.$media->name;
		$prevfile = $bpath."/images/photos/n/p/".$year."/".$month.'-'.$day.'/'.$media->user->city_id.'/'.$media->user_type_id.'/'.$media->name;
		$prevfilehere = $bpath . "/images/photos/n/p/".$year."/".$month.'-'.$day.'/'.$media->user->city_id.'/'.$media->user_type_id.'/'.$media->name;
		$file = $bpath . "/images/photos/n/f/".$year."/".$month.'-'.$day.'/'.$media->user->city_id.'/'.$media->user_type_id.'/'.$media->name;
		
		if($media->id < 680556) 
		{
			if(file_exists($oldprevfile))
				return self::$host."/images/photos/o/p/".$media->user->city_id.'/'.$media->user_type_id.'/'.$media->name;
			else 				
				return Yii::$app->request->hostInfo."/images/_system/video_null.png";
		}
		
		if(file_exists($prevfilehere)) 
		{
			return Yii::$app->request->hostInfo."/images/photos/n/p/".$year."/".$month.'-'.$day.'/'.$media->user->city_id.'/'.$media->user_type_id.'/'.$media->name;
		}
		elseif(file_exists($prevfile)) 
		{
			return self::$host."/images/photos/n/p/".$year."/".$month.'-'.$day.'/'.$media->user->city_id.'/'.$media->user_type_id.'/'.$media->name;
		}
		elseif(file_exists($file))
		{
			$manager = new ImageManager(array('driver' => 'gd'));
			$imageSize = $manager->make($file);
			$w=$imageSize->getWidth();
			$h=$imageSize->getHeight();
			$x1=0;
			$y1=0;
			if($w>$h){
				$x1= round(($w-$h)/2);
				$w=$h;
			}else{
				$y1=round(($h-$w) / 2);
				$h=$w;
			}
			$imageSize->crop($w,$h, $x1, $y1);
			$imageSize->resize($width,null, function($img) {
				$img->aspectRatio();
			});
			$imageSize->interlace(true);
			
			if(!is_dir(__DIR__ . "/../web/images/photos/n/p/".$year."/".$month.'-'.$day.'/'.$media->user->city_id.'/'.$media->user_type_id))
				BaseFileHelper::createDirectory(__DIR__ . "/../web/images/photos/n/p/".$year."/".$month.'-'.$day.'/'.$media->user->city_id.'/'.$media->user_type_id, 0755);
				
			$imageSize->save($bpath . "/images/photos/n/p/".$year."/".$month.'-'.$day.'/'.$media->user->city_id.'/'.$media->user_type_id.'/'.$media->name);
			return Yii::$app->request->hostInfo."/images/photos/n/p/".$year."/".$month.'-'.$day.'/'.$media->user->city_id.'/'.$media->user_type_id.'/'.$media->name;
		}
		else	
		{
			return Yii::$app->request->hostInfo."/images/_system/video_null.png";
		}
	}
	
    public static function thumbMin(UserMedia $media, $width)
    {
        //$bpath = Yii::getAlias('@app').'/web';
        $bpath = '/var/www/artlist/web';
        if(!$media)
            return Yii::$app->request->hostInfo."/images/_system/video_null.png";

        $datetime = new DateTime($media->created_date);

        $year  = $datetime->format('Y');
        $month = $datetime->format('m');
        $day   = $datetime->format('d');
		
		$file = $bpath . "/images/photos/n/f/".$year."/".$month.'-'.$day.'/'.$media->user->city_id.'/'.$media->user_type_id.'/'.$media->name;
		//$oldlazyfile = "/var/www/www-root/data/www/artlist.pro/web/images/photos/o/p_lazy/".$media->user->city_id.'/'.$media->user_type_id.'/'.$media->name;
        $oldlazyfile = "/var/www/artlist/web/images/photos/o/p_lazy/".$media->user->city_id.'/'.$media->user_type_id.'/'.$media->name;
		$lazyfile = "/var/www/www-root/data/www/artlist.pro/web/images/photos/n/p_lazy/".$year."/".$month.'-'.$day.'/'.$media->user->city_id.'/'.$media->user_type_id.'/'.$media->name;
		$lazyfilehere = $bpath . "/images/photos/n/p_lazy/".$year."/".$month.'-'.$day.'/'.$media->user->city_id.'/'.$media->user_type_id.'/'.$media->name;
		
		if($media->id < 680556)
		{
			if(file_exists($oldlazyfile)){
                //exit;
                //echo self::$host."/web/images/photos/o/p_lazy/".$media->user->city_id.'/'.$media->user_type_id.'/'.$media->name;exit;
                //return self::$host."/images/photos/o/p_lazy/".$media->user->city_id.'/'.$media->user_type_id.'/'.$media->name;
                return self::$host."/images/photos/o/p_lazy/".$media->user->city_id.'/'.$media->user_type_id.'/'.$media->name;
            } else	{
			    //exit;
                return Yii::$app->request->hostInfo."/images/_system/video_null.png";
            }


		}
		//echo $file;
		//exit;
		if(file_exists($lazyfilehere))
		{
			return Yii::$app->request->hostInfo."/images/photos/n/p_lazy/".$year."/".$month.'-'.$day.'/'.$media->user->city_id.'/'.$media->user_type_id.'/'.$media->name;
		}
		elseif(file_exists($lazyfile))
		{
			return self::$host."/images/photos/n/p_lazy/".$year."/".$month.'-'.$day.'/'.$media->user->city_id.'/'.$media->user_type_id.'/'.$media->name;
		}
		elseif(file_exists($file))
		{
			$manager = new ImageManager(array('driver' => 'gd'));
			$imageSize = $manager->make($file);
			$w=$imageSize->getWidth();
			$h=$imageSize->getHeight();
			$x1=0;
			$y1=0;
			if($w>$h){
				$x1= round(($w-$h)/2);
				$w=$h;
			}else{
				$y1=round(($h-$w) / 2);
				$h=$w;
			}
			$imageSize->crop($w,$h, $x1, $y1);
			$imageSize->resize($width,null, function($img) {
				$img->aspectRatio();
			});
			$imageSize->interlace(true);
			
			if(!is_dir(__DIR__ . "/../web/images/photos/n/p_lazy/".$year."/".$month.'-'.$day.'/'.$media->user->city_id.'/'.$media->user_type_id))
				BaseFileHelper::createDirectory(__DIR__ . "/../web/images/photos/n/p_lazy/".$year."/".$month.'-'.$day.'/'.$media->user->city_id.'/'.$media->user_type_id, 0755);
				
			$imageSize->save($bpath . "/images/photos/n/p_lazy/".$year."/".$month.'-'.$day.'/'.$media->user->city_id.'/'.$media->user_type_id.'/'.$media->name);
			return Yii::$app->request->hostInfo."/images/photos/n/p_lazy/".$year."/".$month.'-'.$day.'/'.$media->user->city_id.'/'.$media->user_type_id.'/'.$media->name;
		}
		else	
		{
			return Yii::$app->request->hostInfo."/images/_system/video_null.png";
		}
	}

    /**
     * @param UserMedia $media
     * @return string
     * @throws \Exception
     */
    public static function fulla(UserMedia $media)
    {
        //$bpath = Yii::getAlias('@app').'/web';
        $bpath = '/var/www/artlist/web';
        if(!$media)
            return Yii::$app->request->hostInfo. "/img/404-250.png";
		
        $datetime = new DateTime($media->created_date);
		$oldday = new DateTime('-2 days');
		if($oldday < $datetime)
			$newserver = 1;
		else
			$newserver = 0;

        $year  = $datetime->format('Y');
        $month = $datetime->format('m');
        $day   = $datetime->format('d');
		
		if($newserver == 0) 
		{
			if($media->id < 680556)
			{
				if(file_exists( static::$pathFull."/web/images/photos/o/p/".$media->user->city_id.'/'.$media->user_type_id.'/'.$media->name))
					return self::$hostFull."/images/photos/o/f/".$media->user->city_id.'/'.$media->user_type_id.'/'.$media->name;
				else
					return Yii::$app->request->hostInfo. "/img/404-250.png";
			}
			
			if(file_exists( self::$pathFull."/web/images/photos/n/p/".$year."/".$month.'-'.$day.'/'.$media->user->city_id.'/'.$media->user_type_id.'/'.$media->name))
				return self::$pathFull."/images/photos/n/f/".$year."/".$month.'-'.$day.'/'.$media->user->city_id.'/'.$media->user_type_id.'/'.$media->name;
			else
				return Yii::$app->request->hostInfo. "/img/404-250.png";			
		}
		else
		{
			if(file_exists( $bpath."/images/photos/n/f/".$year."/".$month.'-'.$day.'/'.$media->user->city_id.'/'.$media->user_type_id.'/'.$media->name))
				return Yii::$app->request->hostInfo. "/images/photos/n/f/".$year."/".$month.'-'.$day.'/'.$media->user->city_id.'/'.$media->user_type_id.'/'.$media->name;
			else
				return Yii::$app->request->hostInfo. "/img/404-250.png";
		}
    }

    public static function full(UserMedia $media)
    {
        //$bpath = Yii::getAlias('@app').'/web';
        $bpath = '/var/www/artlist/web';
        if(!$media)
            return Yii::$app->request->hostInfo. "/img/404-250.png";

        $datetime = new DateTime($media->created_date);
        $oldday = new DateTime('-2 days');
        if($oldday < $datetime)
            $newserver = 1;
        else
            $newserver = 0;

        $year  = $datetime->format('Y');
        $month = $datetime->format('m');
        $day   = $datetime->format('d');

        if($newserver == 0)
        {
            if($media->id < 680556)
            {
                //if(file_exists( "/var/www/www-root/data/www/artlist.pro/web/images/photos/o/p/".$media->user->city_id.'/'.$media->user_type_id.'/'.$media->name))
                if(file_exists( static::$pathFull."/web/images/photos/o/p/".$media->user->city_id.'/'.$media->user_type_id.'/'.$media->name))
                    return "https://images.artlist.pro/images/photos/o/f/".$media->user->city_id.'/'.$media->user_type_id.'/'.$media->name;
                else
                    return Yii::$app->request->hostInfo. "/img/404-250.png";
            }

            if(file_exists( "/var/www/www-root/data/www/artlist.pro/web/images/photos/n/p/".$year."/".$month.'-'.$day.'/'.$media->user->city_id.'/'.$media->user_type_id.'/'.$media->name))
                return "https://images.artlist.pro/images/photos/n/f/".$year."/".$month.'-'.$day.'/'.$media->user->city_id.'/'.$media->user_type_id.'/'.$media->name;
            else
                return Yii::$app->request->hostInfo. "/img/404-250.png";
        }
        else
        {
            if(file_exists( $bpath."/images/photos/n/f/".$year."/".$month.'-'.$day.'/'.$media->user->city_id.'/'.$media->user_type_id.'/'.$media->name))
                return Yii::$app->request->hostInfo. "/images/photos/n/f/".$year."/".$month.'-'.$day.'/'.$media->user->city_id.'/'.$media->user_type_id.'/'.$media->name;
            else
                return Yii::$app->request->hostInfo. "/img/404-250.png";
        }
    }



/**
     * @param UserType $user
     * @param Genre $genre
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getGenreCover(UserType $user, Genre $genre)
    {
        return UserMedia::find()->where(['user_type_id' => $user->id, 'genre_id' => $genre->id])->orderBy('sort ASC')->limit(1)->one();
    }

    /**
     * @param UserType $user
     * @param UserGenre $genre
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getUserGenreCover(UserType $user, UserGenre $genre)
    {
        return UserMedia::find()->where(['user_type_id' => $user->id, 'user_genre_id' => $genre->id])->orderBy('sort ASC')->limit(1)->one();
    }
}