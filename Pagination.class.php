
<?php


class Pagination{
    protected $baseURL        = '';
    protected $baseURLFOLDER        = '';
    protected $baseURLFOLDERDataList        = '';
    protected $totalRows      = '';
    protected $totalRowsFolder      = '';
    protected $totalRowsDataLink      = '';
    protected $perPage        = 10;
    protected $numLinks       = 2;
    protected $numLinksFolder       = 2;
    protected $currentPage    =  0;
    protected $firstLink      = 'First';
    protected $firstLinkFolder      = 'First';
    protected $nextLink       = 'Next &raquo;';
    protected $prevLink       = '&laquo; Prev';
    protected $lastLink       = 'Last';
    protected $lastLinkFolder       = 'Last';
    protected $fullTagOpen    = '<div class="pagination">';
    protected $fullTagClose   = '</div>';
    protected $firstTagOpen   = '';
    protected $firstTagClose  = '&nbsp;';
    protected $lastTagOpen    = '&nbsp;';
    protected $lastTagClose    = '';
    protected $curTagOpen    = '&nbsp;<b>';
    protected $curTagClose    = '</b>';
    protected $nextTagOpen    = '&nbsp;';
    protected $nextTagClose    = '&nbsp;';
    protected $prevTagOpen    = '&nbsp;';
    protected $prevTagClose    = '';
    protected $numTagOpen    = '&nbsp;';
    protected $numTagClose    = '';
    protected $showCount    = true;
    protected $currentOffset= 0;
    protected $queryStringSegment = 'page';
    
    function __construct($params = array()){
        if (count($params) > 0){
            $this->initialize($params);        
        }
    }
    
    function initialize($params = array()){
        if (count($params) > 0){
            foreach ($params as $key => $val){
                if (isset($this->$key)){
                    $this->$key = $val;
                }
            }        
        }
    }
    
    /**
     * Generate the pagination links
     */ 
    function getTotalRows(){
        if ($this->totalRows == 0 OR $this->perPage == 0){
            return 0;
         }
         if ($this->totalRows > 0){
             return $this->totalRows;
         }
    }  
    function getTotalRowsFolder(){
        if ($this->totalRowsFolder == 0 OR $this->perPage == 0){
            return 0;
         }
         if ($this->totalRowsFolder > 0){
             return $this->totalRowsFolder;
         }
    }  
    
    
    function createLinks(){ 
        // If total number of rows is zero, do not need to continue
        if ($this->totalRows == 0 OR $this->perPage == 0){
           return '';
        }
        // Calculate the total number of pages
        $numPages = ceil($this->totalRows / $this->perPage);
        // Is there only one page? will not need to continue
        if ($numPages == 1){
            if ($this->showCount){
                $info = 'Showing : ' . $this->totalRows . ' out of ' . $this->totalRows;
                return $info;
            }else{
                return '';
            }
        }
        
        // Determine query string
        $query_string_sep = (strpos($this->baseURL, '?') === FALSE) ? '?page=' : '&amp;page=';
        $this->baseURL = $this->baseURL.$query_string_sep;
        
        // Determine the current page
        $this->currentPage = isset($_GET[$this->queryStringSegment])?$_GET[$this->queryStringSegment]:0;
        
        if (!is_numeric($this->currentPage) || $this->currentPage == 0){
            $this->currentPage = 1;
        }
        
        // Links content string variable
        $output = '';
        
        // Showing links notification
        if ($this->showCount){
           $currentOffset = ($this->currentPage > 1)?($this->currentPage - 1)*$this->perPage+1:$this->currentPage;
           $info = 'Showing ' . $currentOffset . ' to ' ;
        
           if( ($currentOffset + $this->perPage) <= $this->totalRows )
              $info .= $this->currentPage * $this->perPage;
           else
              $info .= $this->totalRows;
        
           $info .= ' out of ' . $this->totalRows . ' | ';
        
           $output .= $info;
        }
        
        $this->numLinks = (int)$this->numLinks;
        
        // Is the page number beyond the result range? the last page will show
        if($this->currentPage > $this->totalRows){
            $this->currentPage = $numPages;
        }
        
        $uriPageNum = $this->currentPage;
        
        // Calculate the start and end numbers. 
        $start = (($this->currentPage - $this->numLinks) > 0) ? $this->currentPage - ($this->numLinks - 1) : 1;
        $end   = (($this->currentPage + $this->numLinks) < $numPages) ? $this->currentPage + $this->numLinks : $numPages;
        
        // Render the "First" link
        if($this->currentPage > $this->numLinks){
            $firstPageURL = str_replace($query_string_sep,'',$this->baseURL);
            $output .= $this->firstTagOpen.'<a href="'.$firstPageURL.'"                 
            
            style="background:#05161D;
            color:#fff;
            text-decoration:none; 
            padding: 6px;">
            '.$this->firstLink.'</a>'.$this->firstTagClose;
        }
        // Render the "previous" link
        if($this->currentPage != 1){
            $i = ($uriPageNum - 1);
            if($i == 0) $i = '';
            $output .= $this->prevTagOpen.'<a href="'.$this->baseURL.$i.'"   

                style="background:#05161D;
                color:#fff;
                text-decoration:none; 
                padding: 5px;">
            
            '.$this->prevLink.'</a>'.$this->prevTagClose;
        }
        // Write the digit links
        for($loop = $start -1; $loop <= $end; $loop++){
            $i = $loop;
            if($i >= 1){
                if($this->currentPage == $loop){
                    $output .= $this->curTagOpen.$loop.$this->curTagClose;
                }else{
                    $output .= $this->numTagOpen.'<a href="'.$this->baseURL.$i.'"                 
                    
                    style="background:#05161D;
                    color:#fff;
                    text-decoration:none; 
                    padding: 6px 10px 6px 6px;">
                    
                    '.$loop.'</a>'.$this->numTagClose;
                }
            }
        }
        // Render the "next" link
        if($this->currentPage < $numPages){
            $i = ($this->currentPage + 1);
            $output .= $this->nextTagOpen.'<a href="'.$this->baseURL.$i.'" 
                
                style="background:#05161D;
                color:#fff;
                text-decoration:none; 
                padding: 6px;">
                
                '.$this->nextLink.'</a>'.$this->nextTagClose;
        }
        // Render the "Last" link
        if(($this->currentPage + $this->numLinks) < $numPages){
            $i = $numPages;
            $output .= $this->lastTagOpen.'<a href="'.$this->baseURL.$i.'"

            style="background:#05161D;
            color:#fff;
            text-decoration:none; 
            padding: 6px;">
            '.$this->lastLink.'</a>'.$this->lastTagClose;
        }
        // Remove double slashes
        $output = preg_replace("#([^:])//+#", "\\1/", $output);
        // Add the wrapper HTML if exists
        $output = $this->fullTagOpen.$output.$this->fullTagClose;
        
        return $output;        
    }


    function createfolderDataLinks(){ 
        // If total number of rows is zero, do not need to continue
        if ($this->totalRowsDataLink == 0 OR $this->perPage == 0){
           return '';
        }
        // Calculate the total number of pages
        $numPages = ceil($this->totalRowsDataLink / $this->perPage);
        // Is there only one page? will not need to continue
        if ($numPages == 1){
            if ($this->showCount){
                $info = 'Showing : ' . $this->totalRowsDataLink . ' out of ' . $this->totalRowsDataLink;
                return $info;
            }else{
                return '';
            }
        }
        
        // Determine query string
        $query_string_sep = (strpos($this->baseURLFOLDERDataList, '?') === FALSE) ? '?page=' : '&amp;page=';
        $this->baseURLFOLDERDataList = $this->baseURLFOLDERDataList.$query_string_sep;
        
        // Determine the current page
        $this->currentPage = isset($_GET[$this->queryStringSegment])?$_GET[$this->queryStringSegment]:0;
        
        if (!is_numeric($this->currentPage) || $this->currentPage == 0){
            $this->currentPage = 1;
        }
        
        // Links content string variable
        $output = '';
        
        // Showing links notification
        if ($this->showCount){
           $currentOffset = ($this->currentPage > 1)?($this->currentPage - 1)*$this->perPage+1:$this->currentPage;
           $info = 'Showing ' . $currentOffset . ' to ' ;
        
           if( ($currentOffset + $this->perPage) <= $this->totalRowsDataLink )
              $info .= $this->currentPage * $this->perPage;
           else
              $info .= $this->totalRowsDataLink;
        
           $info .= ' out of ' . $this->totalRowsDataLink . ' | ';
        
           $output .= $info;
        }
        
        $this->numLinks = (int)$this->numLinks;
        
        // Is the page number beyond the result range? the last page will show
        if($this->currentPage > $this->totalRowsDataLink){
            $this->currentPage = $numPages;
        }
        
        $uriPageNum = $this->currentPage;
        
        // Calculate the start and end numbers. 
        $start = (($this->currentPage - $this->numLinks) > 0) ? $this->currentPage - ($this->numLinks - 1) : 1;
        $end   = (($this->currentPage + $this->numLinks) < $numPages) ? $this->currentPage + $this->numLinks : $numPages;
        
        // Render the "First" link
        if($this->currentPage > $this->numLinks){
            $firstPageURL = str_replace($query_string_sep,'',$this->baseURLFOLDERDataList);
            $output .= $this->firstTagOpen.'<a href="'.$firstPageURL.'"                 
            
            style="background:#05161D;
            color:#fff;
            text-decoration:none; 
            padding: 6px;">
            '.$this->firstLink.'</a>'.$this->firstTagClose;
        }
        // Render the "previous" link
        if($this->currentPage != 1){
            $i = ($uriPageNum - 1);
            if($i == 0) $i = '';
            $output .= $this->prevTagOpen.'<a href="'.$this->baseURLFOLDERDataList.$i.'"   

                style="background:#05161D;
                color:#fff;
                text-decoration:none; 
                padding: 5px;">
            
            '.$this->prevLink.'</a>'.$this->prevTagClose;
        }
        // Write the digit links
        for($loop = $start -1; $loop <= $end; $loop++){
            $i = $loop;
            if($i >= 1){
                if($this->currentPage == $loop){
                    $output .= $this->curTagOpen.$loop.$this->curTagClose;
                }else{
                    $output .= $this->numTagOpen.'<a href="'.$this->baseURLFOLDERDataList.$i.'"                 
                    
                    style="background:#05161D;
                    color:#fff;
                    text-decoration:none; 
                    padding: 6px 10px 6px 6px;">
                    
                    '.$loop.'</a>'.$this->numTagClose;
                }
            }
        }
        // Render the "next" link
        if($this->currentPage < $numPages){
            $i = ($this->currentPage + 1);
            $output .= $this->nextTagOpen.'<a href="'.$this->baseURLFOLDERDataList.$i.'" 
                
                style="background:#05161D;
                color:#fff;
                text-decoration:none; 
                padding: 6px;">
                
                '.$this->nextLink.'</a>'.$this->nextTagClose;
        }
        // Render the "Last" link
        if(($this->currentPage + $this->numLinks) < $numPages){
            $i = $numPages;
            $output .= $this->lastTagOpen.'<a href="'.$this->baseURLFOLDERDataList.$i.'"

            style="background:#05161D;
            color:#fff;
            text-decoration:none; 
            padding: 6px;">
            '.$this->lastLink.'</a>'.$this->lastTagClose;
        }
        // Remove double slashes
        $output = preg_replace("#([^:])//+#", "\\1/", $output);
        // Add the wrapper HTML if exists
        $output = $this->fullTagOpen.$output.$this->fullTagClose;
        
        return $output;        
    }


    function createFolders(){ 
        // If total number of rows is zero, do not need to continue
        if ($this->totalRowsFolder == 0 OR $this->perPage == 0){
           return '';
        }
        // Calculate the total number of pages
        $numPages = ceil($this->totalRowsFolder / $this->perPage);
        // Is there only one page? will not need to continue
        if ($numPages == 1){
            if ($this->showCount){
                $info = 'Showing : ' . $this->totalRowsFolder . ' out of ' . $this->totalRowsFolder;
                return $info;
            }else{
                return '';
            }
        }
        
        // Determine query string
        $query_string_sep = (strpos($this->baseURLFOLDER, '?') === FALSE) ? '?page=' : '&amp;page=';
        $this->baseURLFOLDER = $this->baseURLFOLDER.$query_string_sep;
        
        // Determine the current page
        $this->currentPage = isset($_GET[$this->queryStringSegment])?$_GET[$this->queryStringSegment]:0;
        
        if (!is_numeric($this->currentPage) || $this->currentPage == 0){
            $this->currentPage = 1;
        }
        
        // Links content string variable
        $output = '';
        
        // Showing links notification
        if ($this->showCount){
           $currentOffset = ($this->currentPage > 1)?($this->currentPage - 1)*$this->perPage+1:$this->currentPage;
           $info = 'Showing ' . $currentOffset . ' to ' ;
        
           if( ($currentOffset + $this->perPage) <= $this->totalRowsFolder )
              $info .= $this->currentPage * $this->perPage;
           else
              $info .= $this->totalRowsFolder;
        
           $info .= ' out of ' . $this->totalRowsFolder . ' | ';
        
           $output .= $info;
        }
        
        $this->numLinksFolder = (int)$this->numLinksFolder;
        
        // Is the page number beyond the result range? the last page will show
        if($this->currentPage > $this->totalRowsFolder){
            $this->currentPage = $numPages;
        }
        
        $uriPageNum = $this->currentPage;
        
        // Calculate the start and end numbers. 
        $start = (($this->currentPage - $this->numLinksFolder) > 0) ? $this->currentPage - ($this->numLinksFolder - 1) : 1;
        $end   = (($this->currentPage + $this->numLinksFolder) < $numPages) ? $this->currentPage + $this->numLinksFolder : $numPages;
        
        // Render the "First" link
        if($this->currentPage > $this->numLinksFolder){
            $firstPageURL = str_replace($query_string_sep,'',$this->baseURLFOLDER);
            $output .= $this->firstTagOpen.'<a href="'.$firstPageURL.'"                 
            
            style="background:#05161D;
            color:#fff;
            text-decoration:none; 
            padding: 6px;">
            '.$this->firstLinkFolder.'</a>'.$this->firstTagClose;
        }
        // Render the "previous" link
        if($this->currentPage != 1){
            $i = ($uriPageNum - 1);
            if($i == 0) $i = '';
            $output .= $this->prevTagOpen.'<a href="'.$this->baseURLFOLDER.$i.'"   

                style="background:#05161D;
                color:#fff;
                text-decoration:none; 
                padding: 5px;">
            
            '.$this->prevLink.'</a>'.$this->prevTagClose;
        }
        // Write the digit links
        for($loop = $start -1; $loop <= $end; $loop++){
            $i = $loop;
            if($i >= 1){
                if($this->currentPage == $loop){
                    $output .= $this->curTagOpen.$loop.$this->curTagClose;
                }else{
                    $output .= $this->numTagOpen.'<a href="'.$this->baseURLFOLDER.$i.'"                 
                    
                    style="background:#05161D;
                    color:#fff;
                    text-decoration:none; 
                    padding: 6px 10px 6px 6px;">
                    
                    '.$loop.'</a>'.$this->numTagClose;
                }
            }
        }
        // Render the "next" link
        if($this->currentPage < $numPages){
            $i = ($this->currentPage + 1);
            $output .= $this->nextTagOpen.'<a href="'.$this->baseURLFOLDER.$i.'" 
                
                style="background:#05161D;
                color:#fff;
                text-decoration:none; 
                padding: 6px;">
                
                '.$this->nextLink.'</a>'.$this->nextTagClose;
        }
        // Render the "Last" link
        if(($this->currentPage + $this->numLinksFolder) < $numPages){
            $i = $numPages;
            $output .= $this->lastTagOpen.'<a href="'.$this->baseURLFOLDER.$i.'"

            style="background:#05161D;
            color:#fff;
            text-decoration:none; 
            padding: 6px;">
            '.$this->lastLinkFolder.'</a>'.$this->lastTagClose;
        }
        // Remove double slashes
        $output = preg_replace("#([^:])//+#", "\\1/", $output);
        // Add the wrapper HTML if exists
        $output = $this->fullTagOpen.$output.$this->fullTagClose;
        
        return $output;        
    }




    /* DASHBOARD DISPLAY */

    function createLinksDashboard(){ 
        // If total number of rows is zero, do not need to continue
        if ($this->totalRows == 0 OR $this->perPage == 0){
           return '';
        }
        // Calculate the total number of pages
        $numPages = ceil($this->totalRows / $this->perPage);
        // Is there only one page? will not need to continue
        if ($numPages == 1){
            if ($this->showCount){
                $info = 'Showing : ' . $this->totalRows . ' out of ' . $this->totalRows;
                return $info;
            }else{
                return '';
            }
        }
        
        // Determine query string
        $query_string_sep = (strpos($this->baseURL, '?') === FALSE) ? '?page=' : '&amp;page=';
        $this->baseURL = $this->baseURL.$query_string_sep;
        
        // Determine the current page
        $this->currentPage = isset($_GET[$this->queryStringSegment])?$_GET[$this->queryStringSegment]:0;
        
        if (!is_numeric($this->currentPage) || $this->currentPage == 0){
            $this->currentPage = 1;
        }
        
        // Links content string variable
        $output = '';
        
        // Showing links notification
        if ($this->showCount){
           $currentOffset = ($this->currentPage > 1)?($this->currentPage - 1)*$this->perPage+1:$this->currentPage;
           $info = 'Showing ' . $currentOffset . ' to ' ;
        
           if( ($currentOffset + $this->perPage) <= $this->totalRows )
              $info .= $this->currentPage * $this->perPage;
           else
              $info .= $this->totalRows;
        
           $info .= ' out of ' . $this->totalRows . ' | ';
        
           $output .= $info;
        }
        
        $this->numLinks = (int)$this->numLinks;
        
        // Is the page number beyond the result range? the last page will show
        if($this->currentPage > $this->totalRows){
            $this->currentPage = $numPages;
        }
        
        $uriPageNum = $this->currentPage;
        
        // Calculate the start and end numbers. 
        $start = (($this->currentPage - $this->numLinks) > 0) ? $this->currentPage - ($this->numLinks - 1) : 1;
        $end   = (($this->currentPage + $this->numLinks) < $numPages) ? $this->currentPage + $this->numLinks : $numPages;
        
        // Render the "previous" link
        if($this->currentPage != 1){
            $i = ($uriPageNum - 1);
            if($i == 0) $i = '';
            $output .= $this->prevTagOpen.'<a href="'.$this->baseURL.$i.'"   

                style="background:#05161D;
                color:#fff;
                text-decoration:none; 
                padding: 5px;">
            
            '.$this->prevLink.'</a>'.$this->prevTagClose;
        }
        // Write the digit links
        for($loop = $start -1; $loop <= $end; $loop++){
            $i = $loop;
            if($i >= 1){
                if($this->currentPage == $loop){
                    $output .= $this->curTagOpen.$loop.$this->curTagClose;
                }else{
                    $output .= $this->numTagOpen.'<a href="'.$this->baseURL.$i.'"                 
                    
                    style="background:#05161D;
                    color:#fff;
                    text-decoration:none; 
                    padding: 6px 10px 6px 6px;">
                    
                    '.$loop.'</a>'.$this->numTagClose;
                }
            }
        }
        // Render the "next" link
        if($this->currentPage < $numPages){
            $i = ($this->currentPage + 1);
            $output .= $this->nextTagOpen.'<a href="'.$this->baseURL.$i.'" 
                
                style="background:#05161D;
                color:#fff;
                text-decoration:none; 
                padding: 6px;">
                
                '.$this->nextLink.'</a>'.$this->nextTagClose;
        }
        // Render the "Last" link
        if(($this->currentPage + $this->numLinks) < $numPages){
            $i = $numPages;
            $output .= $this->lastTagOpen.'<a href="'.$this->baseURL.$i.'"

            style="background:#05161D;
            color:#fff;
            text-decoration:none; 
            padding: 6px;">
            '.$this->lastLink.'</a>'.$this->lastTagClose;
        }
        // Remove double slashes
        $output = preg_replace("#([^:])//+#", "\\1/", $output);
        // Add the wrapper HTML if exists
        $output = $this->fullTagOpen.$output.$this->fullTagClose;
        
        return $output;        
    }


    
    function createLinksDashboardFolder(){ 
        // If total number of rows is zero, do not need to continue
        if ($this->totalRowsFolder == 0 OR $this->perPage == 0){
           return '';
        }
        // Calculate the total number of pages
        $numPages = ceil($this->totalRowsFolder / $this->perPage);
        // Is there only one page? will not need to continue
        if ($numPages == 1){
            if ($this->showCount){
                $info = 'Showing : ' . $this->totalRowsFolder . ' out of ' . $this->totalRowsFolder;
                return $info;
            }else{
                return '';
            }
        }
        
        // Determine query string
        $query_string_sep = (strpos($this->baseURLFOLDER, '?') === FALSE) ? '?page=' : '&amp;page=';
        $this->baseURLFOLDER = $this->baseURLFOLDER.$query_string_sep;
        
        // Determine the current page
        $this->currentPage = isset($_GET[$this->queryStringSegment])?$_GET[$this->queryStringSegment]:0;
        
        if (!is_numeric($this->currentPage) || $this->currentPage == 0){
            $this->currentPage = 1;
        }
        
        // Links content string variable
        $output = '';
        
        // Showing links notification
        if ($this->showCount){
           $currentOffset = ($this->currentPage > 1)?($this->currentPage - 1)*$this->perPage+1:$this->currentPage;
           $info = 'Showing ' . $currentOffset . ' to ' ;
        
           if( ($currentOffset + $this->perPage) <= $this->totalRowsFolder )
              $info .= $this->currentPage * $this->perPage;
           else
              $info .= $this->totalRowsFolder;
        
           $info .= ' out of ' . $this->totalRowsFolder . ' | ';
        
           $output .= $info;
        }
        
        $this->numLinks = (int)$this->numLinks;
        
        // Is the page number beyond the result range? the last page will show
        if($this->currentPage > $this->totalRowsFolder){
            $this->currentPage = $numPages;
        }
        
        $uriPageNum = $this->currentPage;
        
        // Calculate the start and end numbers. 
        $start = (($this->currentPage - $this->numLinks) > 0) ? $this->currentPage - ($this->numLinks - 1) : 1;
        $end   = (($this->currentPage + $this->numLinks) < $numPages) ? $this->currentPage + $this->numLinks : $numPages;
        
        // Render the "previous" link
        if($this->currentPage != 1){
            $i = ($uriPageNum - 1);
            if($i == 0) $i = '';
            $output .= $this->prevTagOpen.'<a href="'.$this->baseURLFOLDER.$i.'"   

                style="background:#05161D;
                color:#fff;
                text-decoration:none; 
                padding: 5px;">
            
            '.$this->prevLink.'</a>'.$this->prevTagClose;
        }
        // Write the digit links
        for($loop = $start -1; $loop <= $end; $loop++){
            $i = $loop;
            if($i >= 1){
                if($this->currentPage == $loop){
                    $output .= $this->curTagOpen.$loop.$this->curTagClose;
                }else{
                    $output .= $this->numTagOpen.'<a href="'.$this->baseURLFOLDER.$i.'"                 
                    
                    style="background:#05161D;
                    color:#fff;
                    text-decoration:none; 
                    padding: 6px 10px 6px 6px;">
                    
                    '.$loop.'</a>'.$this->numTagClose;
                }
            }
        }
        // Render the "next" link
        if($this->currentPage < $numPages){
            $i = ($this->currentPage + 1);
            $output .= $this->nextTagOpen.'<a href="'.$this->baseURLFOLDER.$i.'" 
                
                style="background:#05161D;
                color:#fff;
                text-decoration:none; 
                padding: 6px;">
                
                '.$this->nextLink.'</a>'.$this->nextTagClose;
        }
        // Render the "Last" link
        if(($this->currentPage + $this->numLinks) < $numPages){
            $i = $numPages;
            $output .= $this->lastTagOpen.'<a href="'.$this->baseURLFOLDER.$i.'"

            style="background:#05161D;
            color:#fff;
            text-decoration:none; 
            padding: 6px;">
            '.$this->lastLink.'</a>'.$this->lastTagClose;
        }
        // Remove double slashes
        $output = preg_replace("#([^:])//+#", "\\1/", $output);
        // Add the wrapper HTML if exists
        $output = $this->fullTagOpen.$output.$this->fullTagClose;
        
        return $output;        
    }
}