<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 30.08.2016
 * Time: 12:40
 */

$this->addCssFile('print', 'webroot.css', 'print');

$this->breadcrumbs=array(
    '<span class="flaticon-phone-auricular-and-a-clock" style="position: relative; top: 1px;"></span> Заявки на туры'
);?>

<ul class="nav nav-pills">
    <li <?=($tab == 0 ? 'class="active"' : '')?>><a href="#currents_tours" tab="code" data-toggle="tab">Текущие</a></li>
    <li <?=($tab == 1 ? 'class="active"' : '')?>><a href="#accepted_tours" tab="design" data-toggle="tab">Оплаченные</a></li>
    <li <?=($tab == 2 ? 'class="active"' : '')?>><a href="#declined_tours" tab="values" data-toggle="tab">Отклоненные</a></li>
</ul>
<div class="tab-content">
    
    <div class="tab-pane fade <?=($tab == 0 ? 'active in' :  '')?>" id="currents_tours">
        <? $this->renderPartial('currents', ['model' => $model]); ?>
    </div>

    <div class="tab-pane fade <?=($tab == 1 ? 'active in' :  '')?>" id="accepted_tours">
        <? $this->renderPartial('accepted', ['model' => $model]); ?>
    </div>

    <div class="tab-pane fade <?=($tab == 2 ? 'active in' :  '')?>" id="declined_tours">
        <? $this->renderPartial('declined', ['model' => $model]); ?>
    </div>

</div>

<div class="modal fade" id="tourRequestInfo" info="" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content"></div>
    </div>
</div>

<?  Yii::app()->clientScript->registerScript(
    "checkboxGroup",
    'function reinstallDatePicker(id, data) {
        $.datepicker.setDefaults( $.datepicker.regional[ "ru" ] );
        $(".t-filter-datepicker").datepicker();
        $.hideFade();
        $.bindDatepickerEraser();
    }

    function updateToursRequestsCount(count){
        if( $.toInt(count) ){

            $(".t-menu-tours-requests a:first").css("padding-right", "35px");

            if( $(".t-menu-tours-requests .tour-request-count").length ){
                $(".t-menu-tours-requests .tour-request-count strong").text(count);
            } else {
                $(".t-menu-tours-requests span:first").after("<div class=\'text-nowrap tour-request-count\'><span class=\'fa fa-plus\'></span><strong>" + count + "</strong></div>");
            }

        } else {
            $(".t-menu-tours-requests a:first").css("padding-right", "15px");
            $(".t-menu-tours-requests .tour-request-count").remove();
        }
    }


    reinstallDatePicker();
    $("body").tooltip({selector: "[data-toggle=tooltip]"});


    $("body").on("click", ".t-view-tour-request", function() {

        var id = $.toInt($(this).closest("tr").find("td:first input:checkbox").val());

        var setRowAsRead = function(id, unread){
            var $tr = $("tr#element_id_" + id);
            $tr.removeClass("warning");
            var $a = $tr.find(".t-ownActions ul a.t-read-tour");
            $a.removeClass("t-read-tour").addClass("t-unread-tour");
            $a.find(".glyphicon-eye-open").removeClass("glyphicon-eye-open").addClass("glyphicon-eye-close");
            $a.find("span:last").text("Отметить как не просмотренное");

            if( typeof unread != "undefined" ) {
                updateToursRequestsCount(unread);
            }
        }

        $.sendRequest("ToursRequests/tourInfo", {"id": id}, function(data){

            $("#tourRequestInfo .modal-content").get(0).innerHTML = data.view;
            $("#tourRequestInfo").attr("info", id);
            $("#tourRequestInfo").modal();
            setRowAsRead(id, data.unread);

        });

    });
    ',
    CClientScript::POS_READY
);