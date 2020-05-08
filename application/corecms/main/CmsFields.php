<?php

/**
 * Generic field class
 */
abstract class Field {

    private $name;
    private $dbColumnType;
    private $htmlField;
    private $title;
    private $value;
    private $visibleAdd = true;
    private $visibleEdit = true;
    private $readOnly = false;
    private $validation = "";
    private $multiLanguage = false;

    public function __construct($name, $title) {
        $this->name = $name;
        $this->title = $title;
    }

    public function __get($name) {
        return $this->$name;
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __toString() {
        return $this->name . ' ' . $this->title;
    }

}

/**
 * Integer field
 */
class IntegerField extends Field {

    public function __construct($name, $title) {
        parent::__construct($name, $title);
        $this->dbColumnType = "int(11)";
        $this->htmlField = "text";
    }

}

/**
 * Double field
 */
class DecimalField extends Field {

    public function __construct($name, $title) {
        parent::__construct($name, $title);
        $this->dbColumnType = "float";
        $this->htmlField = "text";
    }

}

/**
 * Boolean field
 */
class BooleanField extends Field {

    public function __construct($name, $title) {
        parent::__construct($name, $title);
        $this->dbColumnType = "tinyint(1)";
        $this->htmlField = "checkbox";
    }

}

/**
 * String field
 */
class StringField extends Field {

    public function __construct($name, $title) {
        parent::__construct($name, $title);
        $this->dbColumnType = "varchar(255)";
        $this->htmlField = "text";
    }

}

/**
 * Date field
 */
class DateField extends Field {

    public function __construct($name, $title) {
        parent::__construct($name, $title);
        $this->dbColumnType = "date";
        $this->htmlField = "text";
    }

}

/**
 * Date field
 */
class DateTimeField extends StringField {

    public function __construct($name, $title) {
        parent::__construct($name, $title);
        $this->dbColumnType = "datetime";
        $this->htmlField = "text";
    }

}

/**
 * String field
 */
class PasswordField extends StringField {

    public function __construct($name, $title) {
        parent::__construct($name, $title);
        $this->dbColumnType = "varchar(50)";
        $this->htmlField = "password";
    }

}

/**
 * Text field
 */
class TextField extends Field {

    public function __construct($name, $title) {
        parent::__construct($name, $title);
        $this->dbColumnType = "text";
        $this->htmlField = "textarea";
    }

}

/**
 * Field representing list of predefined data
 */
class ListField extends Field {

    private $values;

    public function __construct($name, $title) {
        parent::__construct($name, $title);
        $this->dbColumnType = "varchar(80)";
        $this->htmlField = "select";
    }

    public function __get($name) {
        if ($name == 'values') {
            return $this->values;
        }
        return parent::__get($name);
    }

    public function __set($name, $value) {
        if ($name == 'values') {
            $this->values = $value;
        } else {
            parent::__set($name, $value);
        }
    }

}

class LinkListField extends ListField {
    
}

/**
 * Relation Field
 */
class RelationField extends Field {

    private $linkTo;

    public function __construct($name, $title) {
        parent::__construct($name, $title);
        $this->dbColumnType = "int(11)";
        $this->htmlField = "select";
    }

    public function __get($name) {
        if ($name == 'linkTo') {
            return $this->linkTo;
        }
        return parent::__get($name);
    }

    public function __set($name, $value) {
        if ($name == 'linkTo') {
            $this->linkTo = $value;
        } else {
            parent::__set($name, $value);
        }
    }

}

class RelationsField extends Field {

    private $linkTo;

    public function __construct($name, $title) {
        parent::__construct($name, $title);
        $this->visibleList = false;
        $this->htmlField = "select";
    }

    public function __get($name) {
        if ($name == 'linkTo') {
            return $this->linkTo;
        }
        if ($name == 'values') {
            return $this->value;
        }
        return parent::__get($name);
    }

    public function __set($name, $value) {
        if ($name == 'values') {
            $this->value = $value;
        } else if ($name == 'linkTo') {
            $this->linkTo = $value;
        } else {
            parent::__set($name, $value);
        }
    }

}

/**
 * Rich Text Field with TinyMC Editor
 */
class RichTextField extends TextField {
    
}

/**
 * Asset field , upload content
 */
class UploadField extends Field {

    private $filename;
    private $filetype;
    private $originalFilename;
    private $extension;
    private $filesize;
    private $imageHeight;
    private $imageWidth;
    private $isImage;
    private $allowedTypes;
    private $saveOriginal;
    private $sizes = array();

    public function __construct($name, $title) {
        parent::__construct($name, $title);
        $this->dbColumnType = "int(11)";
        $this->htmlField = "file";
        $this->saveOriginal = TRUE;
        $this->allowedTypes = array("jpg", "png", "jpeg", "gif");
        $this->isImage = true;
    }

    public function __get($name) {

        if ($name == "filename") {
            return $this->filename;
        }

        if ($name == "filetype") {
            return $this->filetype;
        }

        if ($name == "originalFilename") {
            return $this->originalFilename;
        }

        if ($name == "filesize") {
            return $this->filesize;
        }
        if ($name == "imageHeight") {
            return $this->imageHeight;
        }

        if ($name == "imageWidth") {
            return $this->imageWidth;
        }

        if ($name == "extension") {
            return $this->extension;
        }

        if ($name == "isImage") {
            return $this->isImage;
        }

        if ($name == "allowedTypes") {
            return $this->allowedTypes;
        }
        
        if ($name == "saveOriginal") {
            return $this->saveOriginal;
        }
        
        if ($name == "sizes") {
            if (!is_array($this->sizes)) {
                return json_decode($this->sizes);
            }
            return $this->sizes;
        }

        return parent::__get($name);
    }

    public function __set($name, $value) {
        if ($name == "filename") {
            $this->filename = $value;
        } elseif ($name == "filetype") {
            $this->filetype = $value;
        } elseif ($name == "originalFilename") {
            $this->originalFilename = $value;
        } elseif ($name == "filesize") {
            $this->filesize = $value;
        } elseif ($name == "imageHeight") {
            $this->imageHeight = $value;
        } elseif ($name == "imageWidth") {
            $this->imageWidth = $value;
        } elseif ($name == "extension") {
            $this->extension = $value;
        } elseif ($name == "isImage") {
            $this->isImage = $value;
        } elseif ($name == "allowedTypes") {
            $this->allowedTypes = $value;
        } elseif ($name == "saveOriginal") {
            $this->saveOriginal = $value;
        } elseif ($name == "sizes") {
            $this->sizes = $value;
        } else {
            parent::__set($name, $value);
        }
    }

}

class ChildField extends RelationsField {
    
}

class UploadsField extends Field {

    private $uploadFields;
    private $isImage;
    private $allowedTypes;
    private $sizes; 
    
    public function __construct($name, $title) {
        parent::__construct($name, $title);
        $this->uploadFields = [];
        $this->allowedTypes = array("jpg", "png", "jpeg", "gif");
        $this->isImage = true;
        $this->sizes = [];
    }

    public function __set($name, $value) {
        
        if ($name == "uploadFields") {
            $this->uploadFields = $value;
            
        } elseif ($name == "isImage") {
            $this->isImage = $value;
            
        } elseif ($name == "allowedTypes") {
            $this->allowedTypes = $value;
            
        } elseif ($name == "sizes") {
            $this->sizes = $value;
            
        } else {
            parent::__set($name, $value);
        }
    }

    public function __get($name) {
        
        if ($name == 'uploadFields') {
            return $this->uploadFields;
        }

        if ($name == "isImage") {
            return $this->isImage;
        }

        if ($name == "allowedTypes") {
            return $this->allowedTypes;
        }
        
        if ($name == "sizes") {
            if (!is_array($this->sizes)) {
                return json_decode($this->sizes);
            }
            return $this->sizes;
        }
        
        return parent::__get($name);
    }

}
