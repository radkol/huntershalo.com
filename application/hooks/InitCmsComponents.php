<?php

class InitCmsComponents {
    
    public function loadCMS() {
        
        // load engine
        require APPPATH.'corecms/main/engine/SingletonClass.php';
        require APPPATH.'corecms/main/engine/db/DbGenerator.php';
        require APPPATH.'corecms/main/util/CmsCoreUtility.php';
        
        // require APPPATH.'corecms/main/engine/Type.php';
        require APPPATH.'corecms/main/engine/Search.php';
        
        // load main types
        require APPPATH.'corecms/main/type/CmsGenericType.php';
        require APPPATH.'corecms/main/type/CmsModuleType.php';
        require APPPATH.'corecms/main/type/CmsDataType.php';
        require APPPATH.'corecms/main/type/CmsChildType.php';
        
        // load main type definitions
        require APPPATH.'corecms/main/definition/CmsGenericTypeDefinition.php';
        require APPPATH.'corecms/main/definition/CmsModuleTypeDefinition.php';
        require APPPATH.'corecms/main/definition/CmsDataTypeDefinition.php';
        require APPPATH.'corecms/main/definition/CmsChildTypeDefinition.php';
        
        // load all supported fields.
        require APPPATH.'corecms/main/CmsFields.php';
        
        // load template and interceptor processor
        require APPPATH.'corecms/main/CmsInterceptor.php';
        require APPPATH.'corecms/main/CmsInterceptorProcessor.php';
        require APPPATH.'corecms/main/CmsTemplateRenderer.php';

        // load services
        require APPPATH.'corecms/services/TypeService.php';
        require APPPATH.'corecms/services/CmsService.php';
        require APPPATH.'corecms/services/RequestService.php';
        require APPPATH.'corecms/services/FieldService.php';
        require APPPATH.'corecms/services/PaginationService.php';
        require APPPATH.'corecms/services/EmailService.php';
        require APPPATH.'corecms/services/AuthorizationService.php';
        require APPPATH.'corecms/services/UploadService.php';
        require APPPATH.'corecms/services/CaptchaService.php';
        require APPPATH.'corecms/services/DatabaseService.php';
        require APPPATH.'corecms/services/I18NService.php';
        require APPPATH.'corecms/services/WebsiteService.php';
        require APPPATH.'corecms/services/SessionService.php';
        require APPPATH.'corecms/services/StructureService.php';
        require APPPATH.'corecms/services/UtilityService.php';
        
        require APPPATH.'corecms/services/ResourceService.php';
        require APPPATH.'corecms/services/NavigationService.php';
        require APPPATH.'corecms/services/FileService.php';
        require APPPATH.'corecms/services/TransactionService.php';
        
        // data importer
        require APPPATH.'corecms/main/util/DataImporter.php';
        require APPPATH.'corecms/main/util/CmsDataImportProcessor.php';
        
        //social services
        require APPPATH.'corecms/services/social/instagram/InstagramService.php';
        
        //payment services
        require APPPATH.'corecms/services/payment/paypal/PayPalService.php';
        
        //load constants class
        require APPPATH.'corecms/main/util/CmsConstants.php';
        
        // set encoding 
        mb_internal_encoding('UTF-8');
        
    }
}