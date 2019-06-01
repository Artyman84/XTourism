<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 25.04.2015
 * Time: 10:15
 */

$image1 = ArHotelPhotos::imagesList($tour->dir_country_id, $tour->dir_city_id, $tour->h_dir_id, $tour->img_count, false)[0];
$image = ArHotelPhotos::defaultPhotoUrl();

if(file_exists($image1)){
    $image = ArHotelPhotos::imagesList($tour->dir_country_id, $tour->dir_city_id, $tour->h_dir_id, $tour->img_count)[0];
}

$tour_info_url = Yii::app()->createUrl('FrontTourShowcase/tourInfo', ['id' => $uid, 'tid' => $tour->id]);
?>

<div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 t-blockTour<?//=$this->tourBlockClasses($settings['per_row'])?>">
    <div class="thumbnail t-bg-block" style="background-color: <?=$settings['bg_block_color']?>;">
        <div class="tour-img">
            <a class="t-open-target" href="<?=$tour_info_url?>" target="<?=$settings['open_tour_target']?>">
                <img title="" src="<?php echo $image; ?>" class="thumbnail-showcase">
            </a>

            <div class="tour-direction">
                <strong><?=CHtml::encode($tour->country_name)?>, <?=CHtml::encode($tour->resort_name)?></strong>
            </div>

            <?php echo $this->priceLabel($tour, ['price_color' => $settings['price_color'], 'label_color' => $settings['price_label_color'], 'currency' => $settings['currency']]); ?>

        </div>

        <div class="caption tour-showcase-properties">

            <div class="row row-main">
                <div class="col-xs-7 text-crop col-main">
                    <a style="color: <?=$settings['tour_link_color']?>;" class="t-tour-link t-open-target" href="<?=$tour_info_url?>" target="<?=$settings['open_tour_target']?>">
                        <?php echo htmlspecialchars($tour->hotel_name);?>
                    </a>
                </div>
                <div class="col-xs-5 text-right col-main">
                    <div class="text-nowrap tour-stars"><?=TSearch\TourHelper::hCategoryInStars($tour->category_name)?></div>
                </div>
            </div>

            <div class="row row-property">
                <div class="col-xs-6 text-crop col-property">
                    <span class="glyphicon glyphicon-calendar text-muted i-margin t-icon"  title="Дата"></span> <?php echo \TSearch\TourHelper::formatDate1($tour->start_date);?>
                </div>
                <div class="col-xs-6 text-crop text-right col-property">
                    <span class="glyphicon glyphicon-time i-margin text-muted t-icon" title="Дней/Ночей"></span> <?php echo TSearch\TourHelper::getTourNights($tour->nights + 1, false) . ' / ' . TSearch\TourHelper::getTourNights($tour->nights);?>
                </div>
            </div>

            <div class="row row-property">
                <div class="col-xs-6 text-crop col-property">
                    <?php echo $this->residence($tour->adults, $tour->kids)?>
                </div>
                <div class="col-xs-6 text-crop text-right col-property">
                    <span class="glyphicon glyphicon-cutlery i-margin text-muted t-icon" title="Питание"></span> <?php echo CHtml::encode($tour->meal_name) . '&nbsp;' . CHtml::encode($tour->meal_description);?>
                </div>
            </div>

        </div>
    </div>
</div>
