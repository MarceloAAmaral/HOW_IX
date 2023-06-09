<?PHP
class estruturaLayoutMain{
	public $Sub_id;
	public $dados;
	public $DadosMetodos;
	public $Grupos;
	public $Subgrupos;
	public $secao;
	public $estruturaLayout;
	
public function __construct(){	
	#classe estruturaLayout
	include "classe_estrutura_layout.php";
	$this->estruturaLayout = new Estrutura_layout;
}
public function setDados($d){
	$this->dados = $d;
}
public function getDados(){
	return $this->dados;
}	
public function inicio(){
	$dados = $this->getDados();	
	$layout ="<main>";	
	$tabela ='sec';
	$campos ='';
	$filtros = array('ativo'=>'1');
	#se parametro URL conter seção, do contrario exibir o que está marcado para a home
	if(isset($dados['url']['sec'])){		
		$filtros['sec']=$dados['url']['sec'];	 
		$this->setSecao($dados['url']['sec']);
	}else{
		$filtros['home'] = '1';
	}
	$order = '';		
	
	if($retorno = consultaDadosTabela($tabela,$campos,$filtros,$order)){		
		for ($a=0;$a<count($retorno);$a++) {
			$this->setDadosSecao($retorno[$a]);			
			$layout .= $this->estruturaSecao();
		}
	}			
	$layout .=	"</main>";	
	return $layout;
}

public function estruturaSecao(){
	$this->estruturaLayout->setDados($this->getDados());	
	$layout = '';
	$dados = $this->getDadosSecao();	
	$layout .= "<{$dados['estrutura']} id='{$dados['sec']}' class='ativo";							
#classes			
	if (!empty ($dados['classes'])) {
		$layout .= " {$dados['classes']}>";
	}
	$layout .= "'>";
#subsecao			
	if(!empty($dados['sub_id'])){
		$tabela ='sub';
		$campos ='';
		$filtros = array('ativo'=>'1');
		#se parametro URL conter subseção
		if(isset($this->dados['url']['sub'])){
			$filtros['sub']=$this->dados['url']['sub'];
		}else{
			$filtros['sub_id']=$dados['sub_id'];
		}
		$order = '';
		
		if($retorno = consultaDadosTabela($tabela,$campos,$filtros,$order)){
			for ($a=0;$a<count($retorno);$a++) {				
				$this->estruturaLayout->setDadosSub($retorno[$a]);
				$this->estruturaLayout->setSub($retorno[$a]);
				$layout .= $this->estruturaLayout->estruturaSub();
			}			
		}	
			$layout .= "</{$dados['estrutura']}>";
	return $layout;
	}	
}
public function setSecao($a){		
	$this->secao = $a;	
	$this->dados['url']['href'] = $this->dados['url']['href']."/".$a;
}
public function getSecao(){
	return $this->secao;
}
public function setDadosSecao($a){
	$this->dados['sec'] = $a;
}
public function getDadosSecao(){
	return $this->dados['sec'];
}
}