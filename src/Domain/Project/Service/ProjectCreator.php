<?php

namespace App\Domain\Project\Service;

use App\Domain\Project\Repository\ProjectRepository;
use App\Factory\LoggerFactory;
use Psr\Log\LoggerInterface;
use OpenAI;
use Odan\Session\SessionManagerInterface;


final class ProjectCreator
{
    private ProjectRepository $repository;

    private ProjectValidator $ProjectValidator;

    private LoggerInterface $logger;

    private SessionManagerInterface $session;

    public function __construct(
        ProjectRepository $repository,
        ProjectValidator $ProjectValidator,
        LoggerFactory $loggerFactory,
        SessionManagerInterface $session
    ) {
        $this->repository = $repository;
        $this->ProjectValidator = $ProjectValidator;
        $this->logger = $loggerFactory
            ->addFileHandler('Project_creator.log')
            ->createLogger();
        $this->session = $session;
    }

    public function createProject(array $data): array 
    {
        // Input validation
        $this->ProjectValidator->validateProject($data);
        //
        if ( !isset ($_SESSION['email'] ) ) {
            $message = array("code" => 200, "message" => "Please, start session");
            return $message;
        }

        $yourApiKey = $_ENV['OPENAI_KEY'];
        $client = OpenAI::client($yourApiKey);
  
        $proyecto           = isset($data['trabajo']) ? $data['trabajo'] : "Trabajo final de Grado";
        $perfil_ideal       = isset($data['perfil_ideal']) ? $data['perfil_ideal'] : "innovador";
        $horas              = isset ( $data['horas'] )? $data['horas'] : 300;
        $perfil_alumnado    = isset ( $data['perfil_alumnado']) ? $data['perfil_alumnado'] : $perfil_ideal;
        $tamano             = isset($data['tamano']) ? $data['tamano'] : "0-100";
        if ( str_contains($data['tamano'],"-") ) {
            $menor              = @intval(strtok($data['tamano'],"-"));
            $mayor              = @intval(strtok("-"));
        } else {
            $menor = 0;
            $mayor = 100;
        }
        
        $tamano             = "entre $menor y $mayor empleados";
        $sector             = isset ( $data['sector'] ) ? $data['sector'] : "sostenibilidad";
        $mision             = isset ($data['mision'] ) ? $data['mision'] : $sector;
        $publico_objetivo   = isset ($data['publico_objetivo']) ? $data['publico_objetivo'] : "hombres y mujeres de todas las edades";
        $proyecto_objetivo  = isset ($data['proyecto_objetivo']) ? $data['proyecto_objetivo'] : $mision;
        $sostenibilidad     = isset ($data['sostenibilidad']) ? $data['sostenibilidad'] : $sector;
        $tecnologias        = isset ($data['tecnologias']) ? $data['tecnologias'] : "tecnologías emergentes";
        $experiencia        = isset ($data['experiencia']) ? $data['experiencia'] : "no";
        $experiencia        = $experiencia == "false" ? "sí" : "no";

        $content = <<<OPENAI_CONTENT
        Actúa como un especialista en $perfil_ideal que lleva  20 años trabajando. 
        Quiero un proyecto $proyecto de $horas horas de $perfil_alumnado para una empresa con 
        tamaño de $tamano. El proyecto quiero que tenga impacto sostenible.  
        La empresa es del sector de la $sector'. El objetivo de la empresa es $mision'.  
        El público objetivo son $publico_objetivo. 
        El objetivo del proyecto es $proyecto_objetivo.  
        La empresa $experiencia ha hecho similares a esto antes. La empresa quiere utilizar las tecnologías $tecnologias.
        ¿Podrías sugerir qué tecnologías irían mejor para este proyecto?
        Además también quieren usar $sostenibilidad en el proyecto. Quiero que definas con una descripción y fases del proyecto. 
        Las fases del proyecto han de ser precisas y profundas, y además me gustaría que pongas varios 
        ejemplos de interés para la empresa en cada una de ellas.
        También puedes poner un ejemplo de empresa que haga algo parecido de esa fase del proyecto. 
        Necesito una planificación aproximada. Proporcióname una orientación de KPI para evaluar el impacto del proyecto. 
        Pónmelo todo en un lenguaje cercano y motivador para un público joven.  Indícame marcas de referencia de la
        competencia que hay a nivel europeo y su link a la web. Si hay presencia de redes sociales, por favor, 
        indícame qué hashtags consideras más adecuados. 
        Recuerda que es un $proyecto. Si encuentras las tecnologias mencionadas, muéstranos
        un ejemplo de uso concreto em este proyecto. Recuerda ponerle un título al proyecto al principio de todo.
        Ponme ejemplos de Partes del Proyecto de Interés para la Empresa en cada una de las fases. 
        Al final de todo, por favor ponme referencias a bibliografía online que creas necesaria.
        OPENAI_CONTENT;

        $response = $client->chat()->create([
              'model' => 'gpt-3.5-turbo-1106',
                 'messages' => [
                    ['role' => 'user', 
                    'content' => $content ],
                    ],
        ]);

        $Projecte = [];

        $message = $response->choices[0]->message->content;
        $Projecte['code']                       = 200;
        $Projecte['descripcion']                = $message;
        $Projecte['arguments']                  =  [];
        $Projecte['arguments']['proyecto']      = $proyecto;
        $Projecte['arguments']['perfil_ideal']  = $perfil_ideal;
        $Projecte['arguments']['horas']                 = $horas;
        $Projecte['arguments']['perfil_alumnado']          = $perfil_alumnado;
        $Projecte['arguments']['tamano']                = "entre $menor y $mayor empleados";
        $Projecte['arguments']['sector']                = $sector;
        $Projecte['arguments']['mision']                = $mision;
        $Projecte['arguments']['publico_objetivo']           = $publico_objetivo;
        $Projecte['arguments']['proyecto_objetivo']          = $proyecto_objetivo;
        $Projecte['arguments']['sostenibilidad']        = $sostenibilidad;
        $Projecte['arguments']['tecnologias']           = $tecnologias;
        $Projecte['arguments']['experiencia']           = $experiencia;
        $Projecte['data']                               = $data;

        // Insert Project and get new Project ID
        $ProjectId = $this->repository->insertProject($Projecte);
        
         return $Projecte;
    }
        

    
}
