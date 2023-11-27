<?php

namespace App\Domain\Project\Service;

use App\Domain\Project\Repository\ProjectRepository;
use App\Factory\LoggerFactory;
use Psr\Log\LoggerInterface;
use OpenAI;
use OpenAI\Responses\Chat\CreateResponse; 

final class ProjectCreator
{
    private ProjectRepository $repository;

    private ProjectValidator $ProjectValidator;

    private LoggerInterface $logger;

    public function __construct(
        ProjectRepository $repository,
        ProjectValidator $ProjectValidator,
        LoggerFactory $loggerFactory
    ) {
        $this->repository = $repository;
        $this->ProjectValidator = $ProjectValidator;
        $this->logger = $loggerFactory
            ->addFileHandler('Project_creator.log')
            ->createLogger();
    }

    public function createProject(array $data): CreateResponse 
    {
        // Input validation
        $this->ProjectValidator->validateProject($data);
        //

        $yourApiKey = $_ENV['OPENAI_KEY'];
        $client = OpenAI::client($yourApiKey);

        $proyecto           = $data['proyecto'];
        $perfil_ideal       = $data['perfil_ideal'];
        $horas              = isset ( $data['horas'] )? $data['horas'] : 300;
        $perfil_alumn       = @$data['perfil_alumnado'];
        $menor              = @intval(strtok($data['tamano'],","));
        $mayor              = @intval(strtok(","));
        $tamano             = "entre $menor y $mayor empleados";
        $sector             = $data['sector'];
        $vision             = @$data['vision'];
        $publico_obj        = $data['publico_objetivo'];
        $proyecto_obj       = $data['proyecto_objetivo'];
        $sostenibilidad     = $data['sostenibilidad'];

        $content = <<<OPENAI_CONTENT
        Actúa como un especialista en $perfil_ideal que lleva  20 años trabajando. 
        Quiero un proyecto trabajo final de grado de $horas horas de $perfil_alumn para una empresa con 
        tamaño de $tamano. El proyecto quiero que tenga impacto sostenible.  
        La empresa es del sector de la $sector' y realiza $vision'.  
        El público objetivo son $publico_obj. 
        El objetivo del proyecto es $proyecto_obj.  
        La empresa no ha hecho nada similar a esto antes. La empresa quiere utilizar tecnologías emergentes.
        Además también usar $sostenibilidad. Quiero que definas con contenido específico las fases del proyecto.
        Quiero una planificación aproximada. Quiero una orientación de KPI para evaluar el impacto del mismo. 
        Pónmelo todo en un lenguaje cercano y motivador para un público joven.  Índícame marcas de referencia de la
        competencia que hay a nivel europeo y su link a la web. Si hay presencia de redes sociales, por favor, 
        indícame qué hashtags consideras más adecuados. 
        Recuerda que es un trabajo final de grado y que esto lo hará un estudiante.
        OPENAI_CONTENT;

        $response = $client->chat()->create([
              'model' => 'gpt-4',
                 'messages' => [
                    ['role' => 'user', 
                    'content' => $content ],
                    ],
        ]);
       
        }
         return $response;

        // Insert Project and get new Project ID
        //$ProjectId = $this->repository->insertProject($data);

        // Logging
        //$this->logger->info(sprintf('Project created successfully: %s', $ProjectId));

    }
}
