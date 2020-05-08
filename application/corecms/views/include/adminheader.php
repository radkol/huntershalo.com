<!DOCTYPE html>
<html>
    <head>
        <title>Administration Panel</title>
        <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" >
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="<?php echo adminResourcePath("bootstrap/bootstrap.css"); ?>" rel="stylesheet">
        <link href="<?php echo adminResourcePath("bootstrap/bootstrap-theme.css"); ?>" rel="stylesheet">
        <link href="<?php echo adminResourcePath("bootstrap/bootstrap-select.css"); ?>" rel="stylesheet">
        <link href="<?php echo adminResourcePath("bootstrap/datepicker3.css"); ?>" rel="stylesheet">
        <link href="<?php echo adminResourcePath("tinymce/skins/lightgray/skin.min.css", "js"); ?>" rel="stylesheet">
        <link href="<?php echo adminResourcePath("styles.css"); ?>" rel="stylesheet">
        
    <style>
    .ui-autocomplete-loading {
      background: white url("<?php echo adminResourcePath("loading.gif","img"); ?>") right center no-repeat;
    }
  </style>
  
    </head>
    <body class="login-bg">
        <div class="header">
            <div class="container">
                <div class="row">
                    <div class="col-md-2">
                        <!-- Logo -->
                        <div class="logo">
                            <h1><a href="<?php echo base_url("admin"); ?>">Web Rade</a></h1>
                        </div>
                    </div>
                    <?php if (AuthorizationService::instance()->isAdminLoggedIn()): ?>
                        <div class="col-md-8">
                            <form action="" class='form-horizontal'>
                                <fieldset>
                                    <label class='col-sm-1'>Selected WebSite:</label>
                                    <div class='col-sm-3'>
                                        <select name="website" class="selectpicker form-control" style="display: none;">
                                            <?php foreach ($webSiteType->search()->getRecords() as $siteItem) : ?>
                                                <option value="<?php echo $siteItem->id; ?>" <?php echo $currentSite->id == $siteItem->id ? "selected" : ""; ?>><?php echo $siteItem->title; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <label class='col-sm-1'>Selected Language:</label>
                                    <div class='col-sm-3'>
                                        <select name="language" class="selectpicker form-control" style="display: none;">
                                            <?php foreach ($languageType->search()->getRecords() as $langItem) : ?>
                                                <option value="<?php echo $langItem->code; ?>" <?php echo $currentLanguage->id == $langItem->id ? "selected" : ""; ?> ><?php echo $langItem->name; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class='col-sm-3'>
                                        <button class="btn btn-primary" name="submit" type="submit">Change Language & Site</button>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                        <div class="col-md-2">
                            <div class="navbar navbar-inverse" role="banner">
                                <nav class="collapse navbar-collapse bs-navbar-collapse navbar-right" role="navigation">
                                    <ul class="nav navbar-nav">
                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Actions<b class="caret"></b></a>
                                            <ul class="dropdown-menu animated fadeInUp">
                                                <li><a href="<?php echo base_url(); ?>" target="_blank">View The Site</a></li>
                                                <li><a href="<?php echo base_url(); ?>admin/logout">Logout</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php if (AuthorizationService::instance()->isAdminLoggedIn()): ?>
            <div class="page-content">
                <div class="row">
                    <!-- Navigation -->
                    <?php $this->load->view("navigation"); ?>
                    <!-- Page Wrapper for wide column-->
                    <div class="col-md-9">
                        <?php $flashMessage = $this->session->flashdata('message'); ?>
                        <?php if ($flashMessage) : ?>
                            <div class='row'>
                                <div class='col-lg-12'>
                                    <div class="alert alert-success">
                                        <p><?php echo $flashMessage; ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>


