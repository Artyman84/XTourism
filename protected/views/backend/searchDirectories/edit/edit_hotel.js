/**
 * Created by Arti on 16.04.2015.
 */

;(function($, undefined){

    var MAX_FILE_COUNT = 90;
    var MAX_IMAGE_SIZE = 3145728;

    var checkFileSupport = function(){
        // Check for the various File API support.
        if ( window.FileReader ) {
            // Great success! All the File APIs are supported.
            return true;
        }

        alert('Файловая система вашего браузера не поддерживает объект "window.FileReader".\nДля корректной работы загрузчика фото, пожалуйста, установите браузер, который полностью поддерживает файловую систему, либо обновите текущий браузер до нужной версии.');
        return false
    }

    var checkMaxFilesCount = function(fileCount){
        if( fileCount >= MAX_FILE_COUNT ) {
            alert("Выбрано максимальное число изображений: " + MAX_FILE_COUNT);
            return false;
        }

        return true;
    }

    var handleFileSelect = function(evt) {
        var files = evt.target.files; // FileList object
        var handleId = evt.target.attributes.id.value;
        var fileCount = getFilesCount();

        // Loop through the FileList and render image files as thumbnails.
        for (var i = 0, f; f = files[i]; i++) {

            if( !checkMaxFilesCount(fileCount)) break;

            // Only process image files.
            // Только изображения и не больше 3 МБ
            if ( !f.type.match('image.jpeg') ) {
                alert("Файл " + f.name + " не является изображением с расширением JPEG/JPG");
                continue;
            }

            if ( f.size > MAX_IMAGE_SIZE ) {
                alert("Файл " + f.name + " превышает максимальный размер 3МБ.");
                continue;
            }


            var reader = new FileReader();

            // Closure to capture the file information.
            reader.onload = (function(theFile, i) {
                return function(e) {
                    // Render thumbnail.
                    $("div.t-tourPhotoSection").append(
                        ['<div class="col-lg-2 col-md-2 col-sm-3 col-xs-3">',
                            '<div class="thumbnail">',
                                '<span style="float: right;"><span class="glyphicon glyphicon-remove text-danger remove-tour-icon" remove-handle="', handleId, '"', 'i-file="', i, '"', 'title="Удалить"></span></span>',
                                '<img src="', e.target.result, '"', /*'title="', escape(theFile.name), '"',*/ 'class="thumbnail-showcase" alt="" >',
                                '<div class="text-center text-info" style="margin-bottom: -4px;"><span class="t-nr-hotel-photo"></span></div>',
                            '</div>',
                        '</div>']
                        .join('')
                    );

                    recountFileValues();
                };
            })(f, i);

            // Read in the image file as a data URL.
            //reader.removeData();
            reader.readAsDataURL(f);
            ++fileCount;
        }

        var $fileContainer = $("div.t-tourPhotoSection div.t-tourFiles");
        var newId = $.toInt($fileContainer.find("input:file").length) + 1;

        $fileContainer.find("input.t-newTourFile").removeClass("t-newTourFile");
        $fileContainer.append('<input type="file" id="tourPhotos' + newId + '" name="HotelImages[images]" class="t-newTourFile" multiple="true" accept="image/jpeg" style="display: none;">');
    }

    var getFilesCount = function(){
        return $.toInt( $("div.t-tourPhotoSection div.thumbnail").length );
    }

    var recountFileValues = function(){
        // Пересчет значений всех новых файлов
        var k = 0;
        $(".t-tourPhotoSection .thumbnail span.t-nr-hotel-photo").each(function(i, v){
            $(this).text(i+1);
        });
    }


    $(function(){
        // Добавить фотки
        $("body").on("click", "a#addNewTourFiles", function(){

            if( checkMaxFilesCount(getFilesCount()) && checkFileSupport() ){
                $("div.t-tourPhotoSection div.t-tourFiles input.t-newTourFile").trigger("click");
            }

            return false;
        });

        // Обработчик выборки файлов
        $("body").on("change", "div.t-tourPhotoSection div.t-tourFiles input.t-newTourFile", function(evt){
            $.showFade();
            handleFileSelect(evt);
            $.hideFade();
        });

        // Обработчик удаления файла
        $("body").on("click", "div.t-tourPhotoSection div.thumbnail span.remove-tour-icon", function(){
            $(this).closest("div.thumbnail").parent().remove();
            recountFileValues();
        });

    })


})(jQuery);