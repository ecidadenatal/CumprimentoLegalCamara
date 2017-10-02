<?php
/**
 * E-cidade Software Publico para Gest�o Municipal
 *   Copyright (C) 2015 DBSeller Servi�os de Inform�tica Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa � software livre; voc� pode redistribu�-lo e/ou
 *   modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a vers�o 2 da
 *   Licen�a como (a seu crit�rio) qualquer vers�o mais nova.
 *   Este programa e distribu�do na expectativa de ser �til, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia impl�cita de
 *   COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM
 *   PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais
 *   detalhes.
 *   Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU
 *   junto com este programa; se n�o, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   C�pia da licen�a no diret�rio licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta_plugin.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = "";
$oRetorno->erro    = false;
$sMensagem         = "";

try {

  db_inicio_transacao();
  switch ($oParam->exec) {

  	case 'alterarPrevisao':

  		$aDadosPrevisao = $oParam->aDadosPrevisao;
  		$oDaoCumprimentoLegalCamara = db_utils::getDao("cumprimentotetocamara");

  		foreach ($aDadosPrevisao as $iSequencial => $nValorPrevisao) {

  			if(empty($iSequencial)) {
  				continue;
  			}

  			if(empty($nValorPrevisao)){
  				$nValorPrevisao = 0;
  			}

  			$rsCumprimentoLegalCamara = $oDaoCumprimentoLegalCamara->sql_record($oDaoCumprimentoLegalCamara->sql_query($iSequencial));
  			if($oDaoCumprimentoLegalCamara->numrows == 0) {
  				$oRetorno->message = urlencode("Informa��es do sequencial {$iSequencial} n�o encontradas.");
		        $oRetorno->status  = 0;  				
		        break;	
  			}

  			$oCumprimentoLegalCamara = db_utils::fieldsMemory($rsCumprimentoLegalCamara, 0);

  			$oDaoCumprimentoLegalCamara->sequencial    = $oCumprimentoLegalCamara->sequencial;
			$oDaoCumprimentoLegalCamara->bloco         = $oCumprimentoLegalCamara->bloco;
			$oDaoCumprimentoLegalCamara->anousu        = $oCumprimentoLegalCamara->anousu;
			$oDaoCumprimentoLegalCamara->estrutural    = $oCumprimentoLegalCamara->estrutural;
  			$oDaoCumprimentoLegalCamara->valorprevisao = $nValorPrevisao;
	        $oDaoCumprimentoLegalCamara->alterar($oCumprimentoLegalCamara->sequencial);

	        if ($oDaoCumprimentoLegalCamara->erro_status == "0") {
	        	$oRetorno->message = urlencode("Erro ao atualizar a previs�o da receita.");
	        	$oRetorno->status  = 0;
	        	break;
	        }
        	$oRetorno->message = urlencode("Previs�o atualizada com sucesso.");
  		}
        
	    break;
  }

  db_fim_transacao(false);
} catch (Exception $eErro) {

  db_inicio_transacao(true);
  $oRetorno->erro   = true;
  $oRetorno->message = urlencode($eErro->getMessage());
}
echo $oJson->encode($oRetorno);
