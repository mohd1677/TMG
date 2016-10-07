<?php

namespace TMG\Api\VideoBundle\Command;

use Doctrine\ORM\EntityManager;
use Gaufrette\Adapter;
use Gaufrette\Filesystem;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Container;
use TMG\Api\LegacyBundle\Entity\Property;
use TMG\Api\LegacyBundle\Entity\Video;
use TMG\Api\VideoBundle\Handler\VideoHandler;
use GuzzleHttp\Client as Client;
use Aws\S3\S3Client as S3Client;

class PullVideosCommand extends ContainerAwareCommand
{
    /** @var VideoHandler  */
    private $videoHandler;

    /** @var  \GuzzleHttp\Client */
    private $client;

    /** @var  string */
    private $token;

    /** @var S3Client */
    private $s3Client;

    /** @var  Adapter */
    private $s3Adapter;

    /** @var  Adapter */
    private $localAdapter;
    /**
     * @param VideoHandler $videoHandler
     */
    public function __construct(VideoHandler $videoHandler, Container $container)
    {
        parent::__construct();
        $this->videoHandler = $videoHandler;
        $this->client = new Client();
        $this->setContainer($container);
        $this->token = $this->getContainer()->getParameter('vidyard_key');

        $this->s3Client = S3Client::factory([
            "credentials" => [
                "key" => $this->getContainer()->getParameter('aws_access_key_id'),
                "secret" => $this->getContainer()->getParameter('aws_secret_access_key')
            ]
        ]);

        /** @var Filesystem $fileSystem */
        $fileSystem = $this->getContainer()->get('knp_gaufrette.filesystem_map')->get('general_storage');
        $this->s3Adapter = $fileSystem->getAdapter();
        /** @var Filesystem $localFileSystem */
        $localFileSystem = $this->getContainer()->get('knp_gaufrette.filesystem_map')->get('local_storage');
        $this->localAdapter = $localFileSystem->getAdapter();
    }

    /**
     * Configuring command
     */
    protected function configure()
    {
        $this
            ->setName('social:video:rake')
            ->setDescription('Pulls videos from vidyard and stores them in s3.');
    }

    /**
     * Execution scrip
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return null;
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManager $lem */
        $lem = $this->getContainer()->get('doctrine')->getManager('legacy');

        $videoCount = $lem->getRepository("TMGApiLegacyBundle:Video")
            ->createQueryBuilder('v')
            ->select("count('*')")
            ->where("v.vidyardId IS NOT NULL")
            ->getQuery()
            ->getSingleScalarResult();


        for ($i = 0; $i < ($videoCount / 10); $i++) {
            $videos = $lem->getRepository("TMGApiLegacyBundle:Video")->createQueryBuilder('v')
                ->select("v")
                ->where("v.vidyardId IS NOT NULL")
                ->setFirstResult($i * 10)
                ->setMaxResults(10)
                ->getQuery()
                ->getResult();

            $this->parseVideos($videos, $lem);
        }
    }

    /**
     * @param array $videos
     * @param EntityManager $lem
     *
     * Parses videos to get location of video.
     */
    private function parseVideos(array $videos, EntityManager $lem)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine')->getManager();

        /** @var Video $video */
        foreach ($videos as $video) {
            $response = $this->client->get(
                "https://api.vidyard.com/dashboard/v1/videos/". $video->getVidyardId()."/files.json",
                ['query' => ["auth_token" => $this->token]]
            );
            $contents = json_decode($response->getBody()->getContents());
            $fileId = sprintf('%s/%s/%s/%s_%s', date('Y'), date('m'), date('d'), uniqid(), uniqid());
            $fileName = sprintf('%s.%s', $fileId, "mp4");
            $file = file_get_contents($contents->urls->hd);

            if (!$this->s3Client->upload(
                "assets.hotelcoupons.com",
                '/videos/'.$fileName,
                $file
            )) {
                die('failed loading video');
            }

            /** @var Property $legacyProperty */
            $legacyProperty = $video->getProperty();
            $legacyPropertyHash = $legacyProperty->getId();

            /** @var \TMG\Api\ApiBundle\Entity\Property $property */
            $property = $em->getRepository("TMG\\Api\\ApiBundle\\Entity\\Property")
                ->findOneBy(["hash" => $legacyPropertyHash]);
            /** @var \TMG\Api\ApiBundle\Entity\Video $apiVideo */
            $apiVideo = $property->getVideo();
            if ($apiVideo) {
                $apiVideo->setCreateUrl('/videos/' . $fileName);
                $apiVideo->setVidyardId($video->getVidyardId());
                $apiVideo->setPlayerId($video->getPlayerId());
                $apiVideo->setInline($video->getVidyardInline());
                $apiVideo->setIframe($video->getVidyardIframe());
                $apiVideo->setDuration($video->getDuration());
                $apiVideo->setLightBox($video->getVidyardLightBox());
                $apiVideo->setTitle($video->getTitle());
                $em->persist($apiVideo);
            }
            $video->setUrl('/videos/' . $fileName);
            $lem->persist($video);
        }
        $em->flush();
        $lem->flush();
    }
}
