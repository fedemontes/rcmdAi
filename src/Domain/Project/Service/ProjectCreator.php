<?php

namespace App\Domain\Project\Service;

use App\Domain\Project\Repository\ProjectRepository;
use App\Factory\LoggerFactory;
use Psr\Log\LoggerInterface;

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

    public function createProject(array $data): string 
    {
        // Input validation
        $this->ProjectValidator->validateProject($data);
        //

        $yourApiKey = $_ENV['OPENAI_EKY'];
        $client = OpenAI::client($yourApiKey);

        $content <<<OPENAI_CONTENT
        "Actúa como un especialista en $data['perfil_ideal'] que lleva  20 años trabajando. 
        Quiero un proyecto trabajo final de grado de $data['horas'] horas de $data['perfil_alumando'] para una empresa con 
        tamaño de $data['tamano']. El proyecto quiero que tenga impacto sostenible.  
        La empresa es del sector de la $data['sector'] y realiza $data['vision'].  
        El público objetivo son $data['publico_objetivo']. 
        El objetivo del proyecto es $data['proyecto_objetivo'].  
        La empresa no ha hecho nada similar a esto antes. La empresa quiere utilizar tecnologías emergentes.
        Además también usar $data['sostenibilidad']. Quiero que definas con contenido específico las fases del proyecto.
        Quiero una planificación aproximada. Quiero una orientación de KPI para evaluar el impacto del mismo. 
        Pónmelo todo en un lenguaje cercano y motivador para un público joven.  Índícame marcas de referencia de la
        competencia que hay a nivel europeo y su link a la web. Si hay presencia de redes sociales, por favor, 
        indícame qué hashtags consideras más adecuados. 
        Recuerda que es un trabajo final de grado y que esto lo hará un estudiante.
        OPENAI_CONTENT

        $result = $client->chat()->create([
              'model' => 'gpt-4',
                 'messages' => [
                    ['role' => 'user', 
                    'content' => $content,
                 ],
        ]);

         return $result->choices[0]->message->content;

        // Insert Project and get new Project ID
        //$ProjectId = $this->repository->insertProject($data);

        // Logging
        //$this->logger->info(sprintf('Project created successfully: %s', $ProjectId));

    }
}
