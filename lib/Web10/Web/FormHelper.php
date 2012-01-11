<?
namespace Web10\Web;

class FormHelper
{
  public function __construct()
  {
  }

  public function buildNameValuePairs($objList, $nameMethod, $valueMethod)
  {
    $a = array();
    foreach ($objList as $obj)
    {
      $a[] = array($obj->$nameMethod(), $obj->$valueMethod());
    }
    return $a;
  }

  public function formStart($formActionUrl, $name='', $formParams=array(), $cls='')
  {
    $html  = "<form method='post' action='{$formActionUrl}' name='$name' class='$cls'>";
    foreach ($formParams as $name=>$value)
    {
      $html .= "<input type='hidden' name='$name' value='$value'>";
    }
    return $html;
  }

  public function formEnd()
  {
    return "</form>";
  }

  public function button($name, $text, $onclick)
  {
    return "<a href='javascript:void(0);' name='$name' onclick='javascript:$onclick(this);'>$text</a>";
  }

  public function inputText($name, $value='', $extra='')
  {
    $html = "<input type='text' name='$name' $extra value='$value'>";
    return $html;
  }

  public function longText($name, $value='', $rows=5, $cols=60, $extra='')
  {
    $html = "<textarea name='$name' rows='$rows' cols='$cols' $extra>$value</textarea>";
    return $html;
  }

  public function inputSelect($name, $nameValuePairs, $selectedValue=null)
  {
    $html  = "<select name='$name'>";
    foreach ($nameValuePairs as $nameValuePair)
    {
      list($name, $value) = $nameValuePair;
      $selected = ($selectedValue == $value) ? "selected" : "";
      $html .= "<option $selected value='$value'>$name</option>";
    }
    $html .= "</select>";
    return $html;
  }
}
?>
