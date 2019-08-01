<?php  
class BreadcrumbComponent extends Object { 

    private $breadcrumbs = array(); 

    /* 
     * @usage add breadcrumb to the list 
     */ 
    public function addBreadcrumb($breadcrumb = array()) { 
        if (is_array($breadcrumb)) { 
          $this->breadcrumbs[] = $breadcrumb; 
        } 
    } 

    /*  
     * @usage Return the breadcrumbs to the controller  
     * @return void 
     */ 
    public function getBreadcrumbs() { 
        return $this->breadcrumbs; 
    } 

} 
?>