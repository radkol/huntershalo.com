<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * @author Radko Lyutskanov
 */

/*
 * Default Controller for Web Pages.
 */

class HomeBannerItem extends CmsChildType {
    
    public function objectAsString($object) {
        return $object->title;
    }

    public function typeAsString() {
        return "Home Banner Carousel Slide";
    }
    
}


