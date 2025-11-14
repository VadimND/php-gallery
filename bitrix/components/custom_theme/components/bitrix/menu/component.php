<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class CustomMenuComponent extends CBitrixComponent
{
    public function executeComponent()
    {
       
        $this->arResult = array();
        foreach($aMenuLinks as $item) {
            $this->arResult[] = array(
                "TEXT" => $item[0],
                "LINK" => $item[1],                
            );
        }
        
        $this->includeComponentTemplate();
    }   
}
?>