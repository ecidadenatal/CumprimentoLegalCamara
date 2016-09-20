<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

//MODULO: orcamento
$sCampos = "sequencial, (case when
                          estrutural = '000000000000000' then 'PESSOAL INATIVOS(APOSENTADORIAS E REFORMAS)'
                          else o57_fonte || ' - ' || o57_descr
                         end) as elemento, valorprevisao";
$sWhere  = "anousu = ".db_getsession('DB_anousu');
$rsCumprimentoCamara   = $oDaoCumprimentoCamara->sql_record($oDaoCumprimentoCamara->sql_receitas($sCampos, $sWhere));
$db_opcao = 2;
?>
<div style="margin-left: 40%;">
<form id="form1" name="form1" method="post" action="orc4_cumprimentolegalcamara001.php">
<center>
<fieldset style="width: 700px; margin-top: 30px;">
  <legend><b>Previsão das Receitas</b></legend>
    <table border="0">
    <?
      if($oDaoCumprimentoCamara->numrows == 0) {
        echo "<strong>Nenhum dado da previsão encontrado para este exercício.</strong>";
      }
      for ($i = 0; $i < $oDaoCumprimentoCamara->numrows; $i++) { 

        $oCumprimentoCamara = db_utils::fieldsMemory($rsCumprimentoCamara, $i);
        $sElementoReceita   = $oCumprimentoCamara->elemento;
        $nValorPrevisao     = $oCumprimentoCamara->valorprevisao;
        $iSequencial        = $oCumprimentoCamara->sequencial;
    ?>
        <tr>
          <td nowrap>
            <? echo $sElementoReceita; ?>
          </td>
          <td name="valorcumprimentolegal"> 
            <?
              db_input('iSequencial',10,$iSequencial,true,'hidden',$db_opcao,"");
              db_input('nValorPrevisao',10,1,true,'text',$db_opcao,"");
            ?>
          </td>
        </tr>
    <? 
      } 
    ?>
    </table>
</fieldset>     
</center>
  <input name="alterar" type="button" id="btn_alterar" style="margin-top: 20px;margin-left: 48%" value="Alterar" onclick="js_alterar()" >
</form>
</div>
<script>

function js_alterar() {

  var aDadosPrevisao  = new Array();
  var aLinhasPrevisao = document.getElementsByName('valorcumprimentolegal');
    
  for (var i = 0; i < aLinhasPrevisao.length; i++) {
    var aLinha = aLinhasPrevisao[i].childNodes;
    //a chave é o sequencial e o valor é o valor do campo
    aDadosPrevisao[aLinha[1].value] = aLinha[3].value;
  }

  js_divCarregando('Realizando alterações nos valores da previsão',"msgBox");

  var oParam = new Object();
  oParam.aDadosPrevisao = aDadosPrevisao;
  oParam.exec = "alterarPrevisao";

  var oAjax = new Ajax.Request("orc4_cumprimentolegalcamara.RPC.php",
    {
       method: 'post',
       parameters:'json='+Object.toJSON(oParam),
       onComplete: js_retornoAlterar
    });
}

function js_retornoAlterar(oAjax) {
  js_removeObj("msgBox");

  var oRetorno = eval("("+oAjax.responseText+")");
  alert(unescape(oRetorno.message.replace(/\+/g," ")));
  if (oRetorno.status == 0) {
    return false;
  }
  js_emite();
}

function js_emite(){
 jan = window.open('orc2_cumprimentolegaldacamara_natal002.php?ano='+<?=db_getsession('DB_anousu')?>,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
 jan.moveTo(0,0);
}

</script>