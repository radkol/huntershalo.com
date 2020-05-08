<!-- Render modules section. This should be available only for webpage type. -->
<?php
    $type = $objectData->typeName; 
?>
<?php if (showModulesPanel($type)) : ?>
    <hr />
    <div class="row">
        <div class="col-md-12">
            <div class="panel-heading">
                <div class="panel-title"><h3>Manage modules</h3></div>
            </div>
            <div class="panel-body">
                <?php
                    $templates = getTemplates();
                    $currentTemplateData = $templates[$object->template];
                ?>
                <?php foreach ($currentTemplateData as $contentZoneName => $contentZoneData): ?>
                    <div class="col-md-6">
                        <div class="content-box-header">
                            <div class="panel-title"><strong><?php echo $contentZoneData["title"]; ?></strong></div>
                        </div>
                        <div class="content-box-large box-with-header">
                            <?php $modulesForContentZone = isset($currentModules[$contentZoneName]) ? $currentModules[$contentZoneName] : NULL; ?>
                            <?php if ($modulesForContentZone): ?>
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <?php foreach ($header as $fieldName => $fieldTitle): ?>
                                                <th><?php echo $fieldTitle; ?></th>
                                            <?php endforeach; ?>
                                            <th>Remove</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($modulesForContentZone as $moduleData): ?>
                                            <tr>
                                                <?php foreach ($header as $fieldName => $fieldTitle): ?>
                                                    <?php if($fieldName == "stringRepresentation") : ?>
                                                        <td><a href="<?php echo base_url(getEditActionForType($moduleData["moduleType"], $moduleData["moduleId"])); ?>" target="_blank"><?php echo $moduleData[$fieldName]; ?></a></td>
                                                    <?php else : ?>
                                                        <td><?php echo $moduleData[$fieldName]; ?></td>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                                <td>
                                                    <form method="POST" action="<?php echo base_url(getEditActionForType($type, $editId)); ?>">
                                                        <?php echo form_hidden("contentZone", $contentZoneName); ?>                   
                                                        <?php echo form_hidden("moduleType", $moduleData["moduleType"]); ?>
                                                        <?php echo form_hidden("moduleId", $moduleData["moduleId"]); ?>
                                                        <?php echo form_hidden("position", $moduleData["position"]); ?>
                                                        <button type="submit" class="btn btn-danger btn-xs" name="removeModule" value="1"><i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;Remove</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <hr/>
                            <?php endif; ?>

                            <!-- Add new module to that content zone -->
                            <h4>Select Module type</h4>
                            <?php echo form_open(getEditActionForType($type, $editId), array('method' => 'POST', 'id' => 'adminselectmodule-form', 'class' => 'form-inline', 'role' => 'form')); ?>
                            <fieldset>
                                <div class="form-group col-sm-8">
                                    <select class="selectpicker form-control" style="display: none;" name="moduleType">
                                        <?php foreach ($contentZoneData["modules"] as $moduleName) : ?>
                                            <option value="<?php echo $moduleName ?>"><?php echo ucfirst($moduleName); ?> Module</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <?php echo form_hidden("contentZone", $contentZoneName); ?>
                                <div class="form-group col-sm-4">
                                    <button type="submit" name="selectModule" class="btn btn-info" value="1">Show instances</button>
                                </div>
                            </fieldset>
                            <?php echo form_close(); ?>
                            <hr />
                            <!-- End add module to this content zone form -->

                            <!-- Show all module instances if requested -->
                            <?php if ($listContentZone && $contentZoneName == $listContentZone) : ?>
                                <h4>Select instance to add to this zone</h4>
                                <?php echo form_open(getEditActionForType($type, $editId), array('method' => 'POST', 'id' => 'adminaddmodule-form', 'class' => 'form-inline', 'role' => 'form')); ?>
                                <fieldset>
                                    <div class="form-group col-sm-7">
                                        <select class="selectpicker form-control" style="display: none;" id="moduleId" name="moduleData">
                                            <?php foreach ($listModules as $module) : ?>
                                                <option value="<?php echo $module->id . '|' . $listModuleController->objectAsString($module); ?>"><?php echo $listModuleController->objectAsString($module); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <input type="text" class="form-control" id="position" name="position" placeholder="Pos">
                                    </div>
                                    <?php echo form_hidden("contentZone", $contentZoneName); ?>
                                    <?php echo form_hidden("moduleType", $moduleType); ?>
                                    <div class="form-group col-sm-2">
                                        <button type="submit" name="addModule" class="btn btn-success" value="1">Add</button>
                                    </div>
                                </fieldset>
                                <?php echo form_close(); ?>
                            <?php endif; ?>
                            <!-- End show all module instances if requested -->
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>