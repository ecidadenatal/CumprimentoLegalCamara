<?php
/**
 * E-cidade Software Publico para Gestão Municipal
 *   Copyright (C) 2015 DBSeller Serviços de Informática Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa é software livre; você pode redistribuí-lo e/ou
 *   modificá-lo sob os termos da Licença Pública Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a versão 2 da
 *   Licença como (a seu critério) qualquer versão mais nova.
 *   Este programa e distribuído na expectativa de ser útil, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia implícita de
 *   COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
 *   PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
 *   detalhes.
 *   Você deve ter recebido uma cópia da Licença Pública Geral GNU
 *   junto com este programa; se não, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   Cópia da licença no diretório licenca/licenca_en.txt
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
  				$oRetorno->message = urlencode("Informações do sequencial {$iSequencial} não encontradas.");
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
	        	$oRetorno->message = urlencode("Erro ao atualizar a previsão da receita.");
	        	$oRetorno->status  = 0;
	        	break;
	        }
        	$oRetorno->message = urlencode("Previsão atualizada com sucesso.");
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
