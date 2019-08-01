<?php
class AutolocationHelper extends AppHelper {
    var $helpers = array('Js','Html');
    var $output ='';
    
    /*
     * $idInput = ID dell'input text su cui fare l'autocomplete
     * $modelSearch = Modello/NomeCampo nel quale cercare al stringa inserita nell'input
     * $other = Array che contiene l'id del campo da aggiornare ed il nome del campo da prendere dal db
     * $numResult = Numero di risultati da mostrare nella lista
     * $strlen = Numero di caratteri dopo i quali iniziare le richieste dell'autocomplete
     */
    function autocomplete($idInput,$fun_name) {
       /*  $fields= "";
        $setBody = "";        
        $search = explode("/",$modelSearch);
        if (is_array($other)) { 
            foreach ($other As $k => $v) {
                $fields .= $v.',';
                $setBody .= "$('#".$k."').val(".$v.");";
            }
        }
        $fields .= $search[1]; */

        $this->output.=$this->Html->scriptBlock('
            $("#'.$idInput.'").ready(function(){
                $("#'.$idInput.'").attr("onkeyup","query_'.$idInput.'(this.value)");
                $("#'.$idInput.'").attr("autocomplete","off");
                $("#'.$idInput.'").after("<div id=\"autoresult_'.$idInput.'\" class=\"autocomplete_live\"></div>");
            });
        
            function query_'.$idInput.'(txt) {
					var country = $("#UserAdCountry").val();
			        if(txt.length >= 3) {
                    $.post("'.SITE_URL.$fun_name.'", {query: txt,rand: "'.$idInput.'",countryName:country}					
					, function(data){
                        $("#autoresult_'.$idInput.'").html("<ul id=\'ul_'.$idInput.'\' class=\'autocomplete_live autoserachAdd\' style=\'border:1px solid #ddd;display: block;  float: left; list-style-type: none; padding: 0px 0; width: 500px;\'>"+data+"</ul>");
                        $("#ul_'.$idInput.'").width(500);
                        $("#autoresult_'.$idInput.'>ul>li>a").keypress(function(e) {       
                            pressedKey = e.charCode || e.keyCode || -1;
                            switch(pressedKey) {
                                case 38://up
                                    position=position-1;
                                    if (position<0) {
                                        position=dimensione-1;
                                    }
                                    $("#autoresult_'.$idInput.'>ul>li>a").eq(position).focus();
                                    return false;
                                break;
                                
                                case 40://down
                                    position=position+1;
                                    if (position>=dimensione) {position=0;}
                                        $("#autoresult_'.$idInput.'>ul>li>a").eq(position).focus();
                                        return false;
                                    break;
                            }
                        });                        
                    });    
                }            
            }
            
            $("#'.$idInput.'").keypress(function(e) {       
                pressedKey = e.charCode || e.keyCode || -1;
                dimensione=$("#autoresult_'.$idInput.'>ul>li").size();
                switch(pressedKey) {
                    case 38://up
                        $("#autoresult_'.$idInput.'>ul>li>a").eq($("#autoresult_'.$idInput.'>ul>li").size()-1).focus();
                        position = $("#autoresult_'.$idInput.'>ul>li").size()-1;
                    break;
                
                    case 40://down
                        $("#autoresult_'.$idInput.'>ul>li>a").eq(0).focus();
                        position=0;
                    break;
                }
            });
            
            function set_'.$idInput.'(id) {
                $("#'.$idInput.'").val(id);
                $("#autoresult_'.$idInput.'").html("");
            }
        ');
        return $this->output;
    }
}
?>