// define global cms object
var raddycms = raddycms || {};

$(document).ready(function () {

    //init single select
    $('.selectpicker').selectpicker();

    $('.datefield input').datepicker({
        forceParse: false,
        todayBtn: "linked",
        todayHighlight: true,
        enabledHours: true,
        format: "yyyy-mm-dd"
    });
    
    $('.datetimefield input').datetimepicker({
        format: "YYYY-MM-DD hh:mm:ss",
    });

    // add sliding effect on navigation panel
    $(".submenu > a").click(function (e) {
        e.preventDefault();
        var $li = $(this).parent("li");
        var $ul = $(this).next("ul");
        if ($li.hasClass("open")) {
            $ul.slideUp(350);
            $li.removeClass("open");
        } else {
            $(".nav > li > ul").slideUp(350);
            $(".nav > li").removeClass("open");
            $ul.slideDown(350);
            $li.addClass("open");
        }
    });

    // button representing ordered relations fields selected.
    $(".relationsfield-add").click(function () {
        var selectName = $(this).data("selectname");
        var isAutocomplete = $(this).data("autocomplete");
        
        var optionText = '';
        var optionVal = '';
        
        if(!isAutocomplete) {
            var $selectedOption = $("#" + selectName + "-source").find(":selected");
            optionText = $selectedOption.text();
            optionVal = $selectedOption.val();
        } else {
            optionText = $("#multiple-" + selectName).val();
            optionVal =  $("#autocomplete-multiple-" + selectName).val();
            // reset the input field
            $("#multiple-" + selectName).val('');
        }
        
        if(!optionVal || !optionText) {
            return;
        }
        
        var currentCount = $("#" + selectName + "-storage option").length;
        
        //generate html for the current row.
        var displayHtml = "<tr><td>" + optionText +
                "</td><td><a href='' class='relationsfield-record-remove btn btn-danger btn-xs' element='" +
                (currentCount+1) + "'><i class='glyphicon glyphicon-remove'></i></a>";
        //generate html for the hidden select
        var hiddenSelectHtml = "<option selected>" + optionVal + "</option>";

        // access hidden select and add option
        $("#" + selectName + "-storage").append(hiddenSelectHtml);
        // access display table and add row/
        $("#" + selectName + "-display tbody").append(displayHtml);

    });

    $("table").on('click', ".relationsfield-record-remove", function (e) {
        e.preventDefault();
        
        var elementId = $(this).attr("element");
        var $table = $(this).parents("table");
        var hiddenSelectId = $table.attr("linkto");
        
        var count = 1;
        $(hiddenSelectId + " option").each(function () {
            if (count == elementId) {
                $(this).remove();
                return false;
            }
            count++;
        });
        $(this).parent().parent().remove();
        
        // re-index from 1.
        count = 1;
        $table.find('.relationsfield-record-remove').each(function(){
            $(this).attr('element', count);
            count++;
        })
    });
    
    
    // UPLOADS Field - Add
    $('.uploadsfield-add').click(function(e) {
        e.preventDefault();
        
        var fieldname = $(this).data('fieldname');
        var $parentField = $(this).parents('.form-group-uploadsfield');
        var $addContainer = $parentField.find('.uploadsfield-add-container');
        var $containerExistingInputs = $parentField.find('.uploadsfield-existing').find("input[type='file']");
        var $alreadyAdded = $addContainer.find("input[type='file']");
        
        //check if we have clicked on add button but we already have one field that is not populated.
        var tryToAdd = true;
        $alreadyAdded.each(function(){
            if(!$(this).val()) {
                tryToAdd = false;
                return tryToAdd;
            }
        });
        if(!tryToAdd) return;
        
        var inputName = fieldname + '-' + ($containerExistingInputs.length + $alreadyAdded.length);
        var html = "<div class='col-sm-4'><input type='file'  name='" + inputName + "' class='btn btn-default' >" +
        "<p class='help-block'> Upload New File </p></div>";

        $addContainer.append(html);
        
    });
    
    /**
     * ====================  Upload field size handling ========================
     */
    $('a.add-size-html').click(function (e) {
        e.preventDefault();
        var emptySizeRow = $(this).parent().find(".width-height-pair").html();
        $(this).parents(".modal-sizes-container").append(emptySizeRow);
    });

    $('.remove-size-row-disabled').click(function (e) {
        e.preventDefault();
    });

    $(document).on('click', "a.remove-size-row", function (e) {
        e.preventDefault();
        $(this).parents(".size-row").remove();
    })

    $(".upload-field-modal").on('hidden.bs.modal', function () {

        var data = [];

        var fieldName = $(this).data("fieldname");

        $(this).find(".size-row").each(function () {
            var width = $(this).find(".input-width").val();
            var height = $(this).find(".input-height").val();
            var resize = $(this).find(".input-resize").attr("checked") == "checked" ? 1 : 0;
            var crop = $(this).find(".input-crop").attr("checked") == "checked" ? 1 : 0;
            var xAxis = $(this).find(".input-x-axis").val();
            var yAxis = $(this).find(".input-y-axis").val();

            if ($.isNumeric(width) && $.isNumeric(height) && (resize || crop)) {
                data.push(width + ":" + height + ":" + resize + ":" + crop + ":" + xAxis + ":" + yAxis);
            }
        });

        $sizesContainer = $(this).parents(".form-group-uploadable").find(".sizes-container");
        // clear everything so far from the container.
        $sizesContainer.html('');

        data.forEach(function (sizePair) {
            $sizesContainer.append("<input type='hidden' name='" + fieldName + "-sizes[]' value='" + sizePair + "' >");
        });

    });
    /**
     * =============================================================
     */

});

$(function () {
    
    //HANDLE CLEAN AUTOCOMPLETE FIELDS IN CASE OF linklistfield
    $(document).on('change', '.linklistfield-select', function(){
        var name = $(this).data("fieldname");
        $("#autocomplete-" + name + "-placeholder").val('');
        $("#autocomplete-" + name).val('');
    });
    
    // HANDLE AUTOCOMPLETE FIELDS
    var autocompleteLimit = 20;
    
    $(".autocomplete").each(function(){
        
        var type = $(this).data('fieldtype');
        var name = $(this).data('fieldname');
        var typefromselect = $(this).data('selecttype');
        
        $(this).autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: "/adminajax/autocomplete/" + (typefromselect ? $("#" + name + "-type").val() : type) + "/" + autocompleteLimit,
                    dataType: "json",
                    data: {term: request.term},
                    success: function (data) {
                        response(data);
                    }
                });
            },
            minLength: 2,
            select: function (event, ui) {
                $('#autocomplete-' + name).val(ui.item.id);
            },

        }).autocomplete( "instance" )._renderItem = function( ul, item ) {
            var html = "<span class='ui-menu-item-label'>" + item.label + "</span>" + (item.image ? '<span class="ui-menu-item-image"><img src="' + item.image + '" /></span>'  : '');

          return $( "<li>" )
            .append(html)
            .appendTo( ul );
        };
    
    });

});

$(function () {
    //initialize rich text TinyMC editor
    // Tiny MCE
    tinymce.init({
        selector: ".richtext-field",
        plugins: [
            "advlist autolink lists link image hr charmap anchor",
            "searchreplace textcolor visualblocks code fullscreen",
            "insertdatetime media table contextmenu"
        ],
        height: 300,
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
    });
});