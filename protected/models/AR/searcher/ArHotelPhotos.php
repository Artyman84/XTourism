<?php

/**
 * This is the model class for table "{{hotel_photos}}".
 *
 * The followings are the available columns in table '{{hotel_photos}}':
 * @property integer $dir_hotel_id
 * @property string  $photos
 * @property integer $count
 */
class ArHotelPhotos extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{hotel_photos}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('dir_hotel_id, count', 'required', 'integerOnly' => true),
            array('photos', 'safe'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        return [
            'hotel' => array(self::BELONGS_TO, 'ArDirHotels', 'dir_hotel_id'),
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'dir_hotel_id' => 'ID отеля',
            'photos' => 'Список фото',
            'count' => 'Количество фото',
        );
    }


    /**
     * @param integer $country
     * @param integer $city
     * @param bool $url
     * @return array
     */
    public function images($country, $city, $url){
        if( !$this->isNewRecord ) {
            return self::imagesList($country, $city, $this->dir_hotel_id, $this->count, $url);
        }

        return null;
    }

    /**
     * @param integer $nr
     * @param integer $country
     * @param integer $city
     * @param bool $url
     * @return array
     */
    public function image($nr, $country, $city, $url){
        if( !$this->isNewRecord ){
            $images = self::imagesList($country, $city, $this->dir_hotel_id, $this->count, $url);
            return isset($images[$nr]) ? $images[$nr] : null;
        }

        return null;
    }

    /**
     * Returns images list
     * @param int $country
     * @param int $city
     * @param int $hotel
     * @param integer $count
     * @param bool $url
     * @return array
     */
    public static function imagesList($country, $city, $hotel, $count, $url=true){

        $path = $country . '/' . $city . '/' . $hotel . '/';
        $ret_path = ($url ? self::imgBaseUrl() : self::imgBasePath()) . $path;
        $dirPath = self::imgBasePath();

        $images = [];
        if( $count ) {
            for($photo = 1; $photo <= $count; ++$photo ) {
                // TODO: for checking for existence of the file - uncomment line below
                //if( is_file($dirPath . $path . $photo . '.jpg') )
                $images[] = $ret_path . $photo . '.jpg';
            }
        }

        return $images;
    }


    /**
     * Deletes hotels photos
     * @param array $h_ids
     */
    public static function deletePhotos($h_ids){
        $h_ids = (array)$h_ids;
        $hotels = Yii::app()->db->createCommand()
            ->select('*')
            ->from('{{directory_hotels}}')
            ->where(['IN', 'id', $h_ids])
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryAll();

        foreach($hotels as $hotel) {
            self::removeImgPath($hotel->dir_country_id, $hotel->dir_city_id, $hotel->id);
        }
    }


    /**
     * Creates Images hotel path
     * @param integer $country
     * @param integer $city
     * @param integer $hotel
     */
    private static function removeImgPath($country, $city, $hotel){
        $dir = self::imgBasePath() . $country . '/' . $city . '/' . $hotel . '/';

        if ( is_dir($dir) )  {

            if($images = glob($dir . "/*.jpg"))  {
                foreach ($images as $img) {
                    unlink($img);
                }
            }

            rmdir($dir);
        }
    }

    /**
     * @param ArDirHotels $hotel
     * @param array $file_ids
     */
    public static function savePhotos($hotel, $file_ids=[]) {

        $p_index = 0;

        if (!($hotelPhotos = self::model()->findByPk($hotel->id))) {

            $dir = self::createImgPath($hotel->dir_country_id, $hotel->dir_city_id, $hotel->id);

            $hotelPhotos = new self();
            $hotelPhotos->dir_hotel_id = $hotel->id;

        } else {

            $dir = self::imgBasePath() . $hotel->dir_country_id . '/' . $hotel->dir_city_id . '/' . $hotel->id . '/';

            $photos = range(1, $hotelPhotos->count);
            $deleted_images = array_diff($photos, $file_ids);

            // deletes photos
            if (!empty($deleted_images)) {

                foreach ($deleted_images as $img) {
                    $file = $dir . $img . '.jpg';

                    if (is_file($file)) {
                        unlink($file);
                    }
                }

                // renames photos
                if ($exists_images = glob($dir . "/*.jpg")) {
                    foreach ($exists_images as $img) {
                        rename($img, $dir . (++$p_index) . '.jpg');
                    }
                }
            } else {
                $p_index = $hotelPhotos->count;
            }

        }

        $HotelImages = new HotelImages();
        $HotelImages->images = CUploadedFile::getInstances($HotelImages, 'images');

        if ($HotelImages->validate()) {
            foreach ($HotelImages->images as $image) {
                $imgPath = $dir . (++$p_index) . '.jpg';
                $image->saveAs($imgPath);
                Yii::app()->simpleImage->load($imgPath)->crop(840, 460)->save($imgPath, IMAGETYPE_JPEG, 75);
            }
        }

        $hotelPhotos->count = $p_index;
        $hotelPhotos->save(false);
    }

    /**
     * Creates Images hotel path
     * @param integer $country
     * @param integer $city
     * @param integer $hotel
     * @return string
     */
    private static function createImgPath($country, $city, $hotel) {
        $baseDir = self::imgBasePath();

        if( !is_dir($baseDir . $country) ) {
            mkdir($baseDir . $country);
        }

        if( !is_dir($baseDir . $country . '/' . $city) ) {
            mkdir($baseDir . $country . '/' . $city);
        }

        if( !is_dir($baseDir . $country . '/' . $city . '/' . $hotel) ) {
            mkdir($baseDir . $country . '/' . $city . '/' . $hotel);
        }

        return $baseDir . $country . '/' . $city . '/' . $hotel . '/';
    }


    /**
     * Returns img base url of hotel
     * @return string
     */
    public static function imgBaseUrl(){
        return Yii::app()->baseUrl . '/images/hotels/';
    }

    /**
     * Returns img base dir of hotel
     * @return string
     */
    public static function imgBasePath(){
        return Yii::getPathOfAlias('webroot') . '/images/hotels/';
    }

    /**
     * Returns default photo url for hotel
     * @return string
     */
    public static function defaultPhotoUrl(){
        return self::imgBaseUrl() . 'no_photo.jpg';
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ArHotelPhotos the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }
}