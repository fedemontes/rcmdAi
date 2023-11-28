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

    public function createProject(array $data): string 
    {
        // Input validation
        $this->ProjectValidator->validateProject($data);
        //
        if ( !isset ($_SESSION['email'] ) ) {
            return "{ error : 'Alert! Please, start you session.' }";
        }

        $yourApiKey = $_ENV['OPENAI_KEY'];
        $client = OpenAI::client($yourApiKey);

        $proyecto           = isset($data['proyecto']) ? $data['proyecto'] : "trabajo final de grado";
        $perfil_ideal       = isset($data['perfil_ideal']) ? $data['perfil_ideal'] : "innovador";
        $horas              = isset ( $data['horas'] )? $data['horas'] : 300;
        $perfil_alumn       = isset ( $data['perfil_alumnado']) ? $data['perfil_alumnado'] : $perfil_ideal;
        $menor              = @intval(strtok($data['tamano'],","));
        $mayor              = @intval(strtok(","));
        $tamano             = "entre $menor y $mayor empleados";
        $sector             = isset ( $data['sector'] ) ? $data['sector'] : "sostenibilidad";
        $vision             = isset ($data['vision'] ) ? $data['vision'] : $sector;
        $publico_obj        = isset ($data['publico_objetivo']) ? $data['publico_objetivo'] : "hombres y mujeres de todas las edades";
        $proyecto_obj       = isset ($data['proyecto_objetivo']) ? $data['proyecto_objetivo'] : $vision;
        $sostenibilidad     = isset ($data['sostenibilidad']) ? $data['sostenibilidad'] : $sector;
        $tecnologias        = isset ($data['tecnologias']) ? $data['tecnologias'] : "tecnologías emergentes";


        $content = <<<OPENAI_CONTENT
        Actúa como un especialista en $perfil_ideal que lleva  20 años trabajando. 
        Quiero un proyecto $proyecto de $horas horas de $perfil_alumn para una empresa con 
        tamaño de $tamano. El proyecto quiero que tenga impacto sostenible.  
        La empresa es del sector de la $sector'. El objetivo de la empresa es $vision'.  
        El público objetivo son $publico_obj. 
        El objetivo del proyecto es $proyecto_obj.  
        La empresa no ha hecho nada similar a esto antes. La empresa quiere utilizar $tecnologias.
        Además también quieren usar $sostenibilidad en el proyecto. Quiero que definas con contenido específico las fases del proyecto.
        Necesito una planificación aproximada. Proporcióname una orientación de KPI para evaluar el impacto del proyecto. 
        Pónmelo todo en un lenguaje cercano y motivador para un público joven.  Índícame marcas de referencia de la
        competencia que hay a nivel europeo y su link a la web. Si hay presencia de redes sociales, por favor, 
        indícame qué hashtags consideras más adecuados. 
        Recuerda que es un $proyecto y que esto lo hará un estudiante. Si encuentras las tecnologias mencionadas, muéstranos
        un ejemplo de uso concreto em este proyecto.
        OPENAI_CONTENT;

        $response = $client->chat()->create([
              'model' => 'gpt-4',
                 'messages' => [
                    ['role' => 'user', 
                    'content' => $content ],
                    ],
        ]);

        $Projecte = [];
        $Projecte['email'] = $this->session->get('email');

        $message = $response->choices[0]->message->content;
        $Projecte['descripcion']          = $message;

        $Projecte['arguments']            =  [];
        $Projecte['arguments']['proyecto'] = $proyecto;
        $Projecte['arguments']['perfil_ideal']          = $perfil_ideal;
        $Projecte['arguments']['horas']                 = $horas;
        $Projecte['arguments']['perfil_alumn']          = $perfil_alumn;
        $Projecte['arguments']['tamano']                = "entre $menor y $mayor empleados";
        $Projecte['arguments']['sector']                = $sector;
        $Projecte['arguments']['vision']                = $vision;
        $Projecte['arguments']['publico_obj']           = $publico_obj;
        $Projecte['arguments']['proyecto_obj']          = $proyecto_obj;
        $Projecte['arguments']['sostenibilidad']        = $sostenibilidad;
        $Projecte['arguments']['tecnologias']          = $tecnologias;

            
        // Insert Project and get new Project ID
        $ProjectId = $this->repository->insertProject($Projecte);
        
         return json_encode($Projecte, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT );
    }
        

    
}
