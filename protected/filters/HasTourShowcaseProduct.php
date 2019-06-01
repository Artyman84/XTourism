<?php

class HasTourShowcaseProduct extends CFilter {

    public  $user_id;

    protected function preFilter($filterChain) {

        $package = ArShopUsersPackages::model()->with('products')->findByAttributes(['user_id' => $this->user_id]);

        if($package && $package->hasProduct(ArShopProductsTypes::PDT_TOUR_SHOWCASE) && $package->isValid()) {

            $filterChain->run();

        }
    }

}
?>
