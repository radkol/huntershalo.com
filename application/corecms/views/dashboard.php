<div class="content-box-large">
    <h1>Dashboard</h1>
    <hr>
    <div class="row">
        <div class="col-lg-10">
            <form action="<?php echo base_url(getAdminPrefix()); ?>" class='form-horizontal'>
                <fieldset>
                    <div class="form-group">
                        <label class='col-sm-2 control-label'>Select WebSite:</label>
                        <div class='col-sm-4'>
                            <select name="website" class="selectpicker form-control" style="display: none;">
                                <?php foreach ($webSiteType->search()->getRecords() as $siteItem) : ?>
                                    <option value="<?php echo $siteItem->id; ?>" <?php echo $currentSite->id == $siteItem->id ? "selected" : ""; ?>><?php echo $siteItem->title; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class='col-sm-2 control-label'>Select Language:</label>
                        <div class='col-sm-4'>
                            <select name="language" class="selectpicker form-control" style="display: none;">
                                <?php foreach ($languageType->search()->getRecords() as $langItem) : ?>
                                    <option value="<?php echo $langItem->code; ?>" <?php echo $currentLanguage->id == $langItem->id ? "selected" : ""; ?> ><?php echo $langItem->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class='col-sm-2 control-label'>Save properties</label>
                        <div class='col-sm-4'>
                            <button class="btn btn-primary" name="submit" type="submit">Submit</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <h4>Manage Items</h4> <hr>
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-green">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="glyphicon glyphicon-link dashboard-size"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div><strong><?php echo $webPageType->typeAsString(); ?></strong></div>
                                <div class="huge">Items count: <?php echo count($webPageType->search()->getRecords()); ?></div>
                            </div>
                        </div>
                    </div>
                    <a href="<?php echo base_url(getListActionForType("webpage")); ?>">
                        <div class="panel-footer">
                            <span class="pull-left">View All <?php echo $webPageType->typeAsString(); ?></span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row hidden">
        <div class="col-lg-12">
            <h4>System Operations</h4> <hr>
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-red">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="glyphicon glyphicon-wrench dashboard-size"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div><strong>Clean obsolete files</strong></div>
                                <div>This action will remove all uploaded files that are no longer used in any object</div>
                            </div>
                        </div>
                    </div>
                    <a href="<?php echo base_url(getTaskAction("cleanassets", getFileTablename())); ?>">
                        <div class="panel-footer">
                            <span class="pull-left">Trigger Action</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>