<?php

class Asset extends CmsDataType {

    public function objectAsString($object) {
        return $object->name;
    }

        public function typeAsString() {
        return "Asset (File Resources)";
    }
}
