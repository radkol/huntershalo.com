    <div class="hidden sizes-container">
        <!--  Placeholder for hidden inputs -->
        <?php foreach ($readOnlySizes as $sizeObject): ?>
            <input type="hidden" name="<?php echo $field->name; ?>-sizes[]" value="<?php echo "{$sizeObject->width}:{$sizeObject->height}:{$sizeObject->resize}:{$sizeObject->crop}:{$sizeObject->xAxis}:{$sizeObject->yAxis}"; ?>" />
        <?php endforeach; ?>
        <?php foreach ($modifySizes as $sizeObject): ?>
            <input type="hidden" name="<?php echo $field->name; ?>-sizes[]" value="<?php echo "{$sizeObject->width}:{$sizeObject->height}:{$sizeObject->resize}:{$sizeObject->crop}:{$sizeObject->xAxis}:{$sizeObject->yAxis}"; ?>" />
        <?php endforeach; ?>
    </div>

    <!-- Modal -->
    <div id="<?php echo $field->name; ?>-modal" data-fieldname="<?php echo $field->name; ?>" class="modal fade upload-field-modal" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Predefined and Custom Sizes</h4>
                </div>
                <div class="modal-body">
                    <div class="modal-sizes-container">
                        <div class="row">
                            <div class="col-sm-12">
                                <a href="#" class="btn btn-danger add-size-html">Add New Size</a>
                                <div class="hidden width-height-pair">
                                    <div class="row size-row static-row">
                                        <div class="col-sm-2">
                                            <input type="text" placeholder="Width(px)" name="width[]" class="form-control input-width" >
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="text" placeholder="Height(px)" name="height[]" class="form-control input-height">
                                        </div>
                                        <label class='col-sm-1 control-label'>Scale Image:</label>
                                        <div class="col-sm-1">
                                            <input type="checkbox" name="scale[]" class="form-control input-resize" checked="checked" />
                                        </div>
                                        <label class='col-sm-1 control-label'>Crop(Cut) Image:</label>
                                        <div class="col-sm-1">
                                            <input type="checkbox" name="cut[]" class="form-control input-crop">
                                        </div>
                                        <div class="col-sm-1">
                                            <input type="text" placeholder="X" name="x_axis[]" class="form-control input-x-axis">
                                        </div>                                        
                                        <div class="col-sm-1">
                                            <input type="text" placeholder="Y" name="y_axis[]" class="form-control input-y-axis">
                                        </div>
                                        <div class="col-sm-1">
                                            <a href="#" class="btn btn-danger remove-size-row"><i class="glyphicon glyphicon-remove"></i></a>
                                        </div>
                                        <div class="col-sm-12"><hr/></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <?php foreach ($readOnlySizes as $sizeObject): ?>
                            <div class="row size-row">
                                <div class="col-sm-2">
                                    <input type="text" placeholder="Width(px)" name="width[]" class="form-control input-width" value="<?php echo $sizeObject->width; ?>" readonly="true">
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" placeholder="Height(px)" name="height[]" class="form-control input-height" value="<?php echo $sizeObject->height; ?>" readonly="true">
                                </div>
                                <label class='col-sm-1 control-label'>Scale Image:</label>
                                <div class="col-sm-1">
                                    <input type="checkbox" name="resize[]" class="form-control input-resize" disabled="true" <?php echo $sizeObject->resize ? "checked" : ""; ?> />
                                </div>
                                <label class='col-sm-1 control-label'>Crop(Cut) Image:</label>
                                <div class="col-sm-1">
                                    <input type="checkbox" name="crop[]" class="form-control input-crop" disabled="true" <?php echo $sizeObject->crop ? "checked" : ""; ?>>
                                </div>
                                <div class="col-sm-1">
                                    <input type="text" placeholder="X" name="x_axis[]" class="form-control input-x-axis" readonly="true" value="<?php echo $sizeObject->xAxis; ?>">
                                </div>                                        
                                <div class="col-sm-1">
                                    <input type="text" placeholder="Y" name="y_axis[]" class="form-control input-y-axis" readonly="true" value="<?php echo $sizeObject->yAxis; ?>">
                                </div>                                
                                <div class="col-sm-1">
                                    <a href="#" class="btn btn-default"><i class="glyphicon glyphicon-remove remove-size-row-disabled"></i></a>
                                </div>
                                <div class="col-sm-12"><hr/></div>
                            </div>
                        <?php endforeach; ?>
                        <?php foreach ($modifySizes as $sizeObject): ?>
                            <div class="row size-row">
                                <div class="col-sm-2">
                                    <input type="text" placeholder="Width(px)" name="width[]" class="form-control input-width" value="<?php echo $sizeObject->width; ?>" >
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" placeholder="Height(px)" name="height[]" class="form-control input-height" value="<?php echo $sizeObject->height; ?>" >
                                </div>
                                <label class='col-sm-1 control-label'>Scale Image:</label>
                                <div class="col-sm-1">
                                    <input type="checkbox" name="resize[]" class="form-control input-resize" <?php echo $sizeObject->resize ? "checked" : ""; ?> />
                                </div>
                                <label class='col-sm-1 control-label'>Crop(Cut) Image:</label>
                                <div class="col-sm-1">
                                    <input type="checkbox" name="crop[]" class="form-control input-crop" <?php echo $sizeObject->crop ? "checked" : ""; ?>>
                                </div>
                                <div class="col-sm-1">
                                    <input type="text" placeholder="X" name="x_axis[]" class="form-control input-x-axis" value="<?php echo $sizeObject->xAxis; ?>">
                                </div>                                        
                                <div class="col-sm-1">
                                    <input type="text" placeholder="Y" name="y_axis[]" class="form-control input-y-axis" value="<?php echo $sizeObject->yAxis; ?>">
                                </div>                                 
                                <div class="col-sm-1">
                                    <a href="#" class="btn btn-danger remove-size-row"><i class="glyphicon glyphicon-remove"></i></a>
                                </div>
                                <div class="col-sm-12"><hr/></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="clearfix" />
                </div>
                <button type="button" class="btn btn-success" data-dismiss="modal">Save & Close</button>
            </div>
        </div>
    </div>
</div>

