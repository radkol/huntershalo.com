<?php

class InstagramService extends SingletonClass {
    
    const DB_TABLENAME = "corecms_social_instagram";
    const UNFILTERED_RECORDS_LIMIT = 100;
    const DOWNLOAD_LIMIT = 10;
    
    // in days
    const REFRESH_RATE = 1;
    const DELETE_RATE = 5;
    
    public static $imageTypes = array(
       "low_resolution"      => "Low Resolution", 
       "thumbnail"           => "Thumbnail Resolution", 
       "standard_resolution" => "Starndard Resolution" 
    );
    
    private $instagramApi;
    
    private function createdb() {
        if(enableDbSiteGeneration()) {
            $query =  "CREATE TABLE IF NOT EXISTS `". self::DB_TABLENAME . "` ("
                . "`id` int(11) NOT NULL AUTO_INCREMENT, "
                . "`url` varchar(255) NOT NULL, "
                . "`type` varchar(50) NOT NULL, "
                . "`link` varchar(255) NOT NULL, "
                . "`created` date NOT NULL, "
                . "`width` int(4) NOT NULL, "
                . "`height` int(4) NOT NULL, "
                . "PRIMARY KEY (`id`)) "
                . "ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";     
            $this->ci->db->query($query);
        }
    }
    
    public function __construct() {
        parent::__construct();
        $this->createdb();
        require APPPATH.'corecms/services/social/instagram/InstagramAPI.php'; 
        require APPPATH.'corecms/services/social/instagram/InstagramAPIException.php';
        $resourceService = ResourceService::instance();
//        $this->instagramApi = new InstagramAPI(array(
//                'apiKey' => $resourceService->getConfig("instragram.client.id"),
//                'apiSecret' => $resourceService->getConfig("instragram.client.secret"),
//                'apiCallback' => $resourceService->getConfig("instragram.client.returnurl"),
//            ));
        $this->instagramApi = new InstagramAPI($resourceService->getConfig("instragram.client.id"));
    }
    
    private function getTagMedia() {
        $result =  $this->instagramApi->getTagMedia(ResourceService::instance()->getConfig("instragram.client.searchtag"), self::UNFILTERED_RECORDS_LIMIT);
        return $result->data;
    }
    
    private function getUserMedia($limit) {
        $result =  $this->instagramApi->getUserMedia(ResourceService::instance()->getConfig("instragram.user.id"), $limit);
        return $result->data;
    }
    
    private function downloadPhotos($limit = self::DOWNLOAD_LIMIT, $requestType = 'low_resolution') {
        $data = $this->getUserMedia($limit);
        
        foreach($data as $record) {
            $link = $record->link;
            $image = $record->images->$requestType;
            $this->ci->db->insert(self::DB_TABLENAME, array(
                'url' => $image->url,
                'width' => $image->width,
                'height' => $image->height,
                'type' => $requestType,
                'link' => $link,
                'created' => date('Y-m-d')
            ));
        }
        
        $offsetTime = new DateTime();
        $offsetTime->modify('-'.self::DELETE_RATE.' day');
        
        $delQuery = "DELETE FROM ".self::DB_TABLENAME." WHERE created < '{$offsetTime->format('Y-m-d')}' ";
        $this->ci->db->query($delQuery);
    }
    
    public function getPhotos($limit = self::DOWNLOAD_LIMIT, $type = 'low_resolution') {
        
        $sql = "SELECT * FROM ". self::DB_TABLENAME ." WHERE type='{$type}' ORDER BY id DESC LIMIT {$limit}";
        $query = $this->ci->db->query($sql);
        
        // do we need to download images !?
        $doDownload = $this->checkForDownload($query, $limit);
        
        if($doDownload) {
            $query->free_result();
            $this->downloadPhotos($limit, $type);
            $query = $this->ci->db->query($sql);
        }
        
        return $query->result();
    }
    
    private function isOldEnough($latestCreatedDate) {
        
        $imageTime = strtotime($latestCreatedDate);
        $offsetTime = new DateTime();
        $offsetTime->modify('-'.self::REFRESH_RATE.' day');
        if($imageTime < $offsetTime->getTimestamp()) {
            return TRUE;
        }
        return FALSE;
    }
    
    private function checkForDownload($query, $limit) {
        
        $isEmpty = $query->num_rows() == 0;
        $notEnoughRecords = $query->num_rows() < $limit;
        
        if($isEmpty) {
            return TRUE;
        } else {
            if($notEnoughRecords) {
                return TRUE;
            } else {
                $latestImage = $query->row();
                if($this->isOldEnough($latestImage->created)) {
                    return TRUE;
                }
            }
        }
    }
}

