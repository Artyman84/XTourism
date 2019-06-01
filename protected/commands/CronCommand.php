<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 26.09.2015
 * Time: 14:02
 */

class CronCommand extends CConsoleCommand {

    // Запуск крона с локали: Z:\home\localhost\www\xtourism\protected>Z:\usr\local\php5\php.exe yiic.php cron

    public function run($args) {

        $cron = new TSearch\Cron();

        $cron->updateOperatorsData(null, false);

        $cron->activatePackages();

        $cron->updateShowcaseTours();

        echo 'End Cron.' . PHP_EOL;
    }

}