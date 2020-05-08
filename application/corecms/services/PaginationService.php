<?php

/*
 * @author Radko Lyutskanov
 */

class PaginationService extends SingletonClass {

    const PAGE_REQUEST_PARAM = "page";
    const PAGE_SIZE_REQUEST_PARAM = "pageSize";
    const DEFAULT_PAGE_SIZE = 15;
    const DEFAULT_PAGE = 1;

    public function getPaginationConfig($pageSize = -1, $pageNumber = -1) {
        
        $requestService = RequestService::instance();
        
        if ($pageNumber == -1) {
            $pageNumber = $requestService->getParam(self::PAGE_REQUEST_PARAM);
        }
        
        if (!$pageNumber) {
            $pageNumber = self::DEFAULT_PAGE;
        }
        
        if ($pageSize == -1) {
            $pageSize = $requestService->getParam(self::PAGE_SIZE_REQUEST_PARAM);
        }
        
        if (!$pageSize) {
            $pageSize = self::DEFAULT_PAGE_SIZE;
        }
        
        return array(
            "pageNumber" => $pageNumber,
            "pageSize" => $pageSize
        );
    }

    /**
     * Retrieve Pagination object for particular class
     * @param type $type
     * @param type $filterData
     * @param type $pageNumber
     * @param type $pageSize
     * @param type $orderByColumn
     * @param type $orderByType
     */
    public function getPagination($type, $filterData, $paginationConfig, $orderByColumn = 'id', $orderByType = 'asc') {
        $typeSearch = TypeService::instance()->getSearch($type);
        
        $recordSet = $typeSearch->getPaginatedRecords($filterData, $paginationConfig["pageNumber"], $paginationConfig["pageSize"], $orderByColumn, $orderByType);
        $paginationObject = new Pagination();

        $paginationObject->pageSize = $paginationConfig["pageSize"];
        $paginationObject->currentPage = $paginationConfig["pageNumber"];
        $paginationObject->recordSet = $recordSet;
        $paginationObject->recordSetCount = count($recordSet);
        $paginationObject->totalCount = $typeSearch->getResultsCount($filterData);
        $paginationObject->pageCount = intval($paginationObject->totalCount / $paginationObject->pageSize) + ($paginationObject->totalCount % $paginationObject->pageSize != 0 ? 1 : 0);

        // process query string
        $queryString = RequestService::instance()->getQueryString();
        $pageIndex = strpos($queryString, self::PAGE_REQUEST_PARAM . '=');
        // page param should always be at the end of the query string hence,
        // search for it and remove it
        if ($pageIndex !== FALSE) {
            $queryString = substr($queryString, 0, ($pageIndex == 0 ? $pageIndex : $pageIndex-1));
        }

        if (!empty($queryString)) {
            $paginationObject->hasQueryString = TRUE;
            $paginationObject->pageParamString = '&' . self::PAGE_REQUEST_PARAM . '=';
            $paginationObject->queryString = "?" . $queryString;
        } else {
            $paginationObject->queryString = '?';
            $paginationObject->pageParamString = self::PAGE_REQUEST_PARAM . '=';
        }
        $paginationObject->pageParamIndex = $pageIndex;
        return $paginationObject;
    }

}

class Pagination {

    private $pageSize;
    private $currentPage;
    private $recordSet;
    private $recordSetCount;
    private $totalCount;
    private $pageCount;
    private $queryString;
    private $pageParamIndex;
    private $hasQueryString = false;
    private $pageParamString;

    public function __get($name) {
        return $this->$name;
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

}
