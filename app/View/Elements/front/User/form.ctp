 



<?php
if (class_exists('JsHelper') && method_exists($this->Js, 'writeBuffer')) {
    echo $this->Js->writeBuffer();
}
?>