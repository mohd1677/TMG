<?php

namespace TMG\Console\CommandBundle\Parser;

use Doctrine\ORM\NoResultException;
use GuzzleHttp\Client;
use TMG\Api\ApiBundle\Entity\CallLog;
use TMG\Api\ApiBundle\Entity\PostalCode;
use TMG\Api\ApiBundle\Entity\State;
use TMG\Console\CommandBundle\BaseParser;

class CallButtonParser extends BaseParser
{
    const ENDPOINT = 'http://207.115.70.58/cb/main/jsp/xml/xml.jsp';

    /** @var \DateTime */
    protected $callStartTime;

    /** @var int */
    protected $newCalls = 0;

    public function setStartTime($start)
    {
        if ($start) {
            $this->callStartTime = new \DateTime($start);
        } else {
            $query = $this->entityManager->getRepository('ApiBundle:CallLog')->createQueryBuilder('c')
                ->select('c.startTime')
                ->orderBy('c.startTime', 'DESC')
                ->setMaxResults('1')
                ->getQuery();

            try {
                $this->callStartTime = $query->getSingleResult()['startTime'];
            } catch (NoResultException $e) {
                $this->callStartTime = new \DateTime('January 1, 2013');
            }
        }
    }

    public function getRecords()
    {
        $interval = \DateInterval::createFromDateString('1 hour');

        do {
            $endTime = clone $this->callStartTime;
            $endTime->add($interval);

            $records = $this->fetchRecords($this->callStartTime, $endTime);
            $this->processRecords($records);
            $records = null;
            $this->entityManager->clear();

            $this->callStartTime = $endTime;
        } while ($this->callStartTime < (new \DateTime));
        
        return "Processed {$this->getCallsCount()} new calls.";
    }

    protected function fetchRecords(\DateTime $startTime, \DateTime $endTime)
    {
        // overlap by a minute to avoid missing anything.
        $strStartTime = $startTime
            ->sub(\DateInterval::createFromDateString('60 seconds'))
            ->format("m/d/Y H:i:s");
        $strEndTime = $endTime->format("m/d/Y H:i:s");

        $this->output->writeln("<info>Processing calls for the hour of $strStartTime</info>");

        // Hard Coded XML request - Requests all clients (*) within date range
        $body =
            '<?xml version="1.0"?>
            <Callbutton version="B">
                <CallbuttonService>
                    <GetCallDetailsRequest>
                        <ClientCode>*</ClientCode>
                        <StartDate>'.$strStartTime.'</StartDate>
                        <EndDate>'.$strEndTime.'</EndDate>
                    </GetCallDetailsRequest>
                </CallbuttonService>
            </Callbutton>';

        $data = $this->postXml(self::ENDPOINT, $body);

        // Add Option to escape CDATA
        return simplexml_load_string($data, null, LIBXML_NOCDATA);
    }


    public function processRecords(\SimpleXMLElement $records)
    {
        $count = count($records->CallbuttonService->GetCallDetailsResponse->Client);
        if (!$count) {
            return;
        }

        if ($records->CallbuttonService->GetCallDetailsResponse['status'] != "OK") {
            throw new \Exception(
                "Error Occured : ".$records->CallbuttonService->GetCallDetailResponse['status'].'. '
                .$records->CallbuttonService->GetCallDetailResponse
            );
        }

        foreach ($records->CallbuttonService->GetCallDetailsResponse->Client as $client) {
            $call = $client->CallDetails->Call;

            /** @var CallLog $callLog */
            $callLog = $this->entityManager->getRepository('ApiBundle:CallLog')
                ->findOneBy(array('callId' => $call['ID']));
            if (!$callLog) {
                $callLog = new CallLog();
                $this->output->write('+');
            }

            $callLog->setCallId($call['ID'])
                ->setStartTime(new \DateTime($call->StartTime))
                ->setDuration($call->Duration)
                ->setTalkTime($call->TalkTime)
                ->setCallNum($call->ANI)
                ->setTrackingNum($call->DID)
                ->setEndpointNum($call->Target)
                ->setLocation($call->CallerName)
                ->setAccount(trim($call->CustomA))
                ->setCampaign(trim($call->Campaign))
                ->setRecordingUrl($call->VoiceDirectory);

            if ($call->Address->State) {
                /** @var State $state */
                $state = $this->entityManager->getRepository('ApiBundle:State')
                    ->findOneBy(['abbreviation' => $call->Address->State]);

                if ($state) {
                    $callLog->setState($state);
                }
            }

            // Make sure the zip code is five digits if one is provided
            if ($call->CallerNANPAZipCode) {
                $postalCodeStr = str_pad(
                    $call->CallerNANPAZipCode,
                    5,
                    '0',
                    STR_PAD_LEFT
                );

                /** @var PostalCode $postalCode */
                $postalCode = $this->entityManager->getRepository('ApiBundle:PostalCode')
                    ->findOneBy(['code' => $postalCodeStr]);

                if ($postalCode) {
                    $callLog->setPostalCode($postalCode);
                }
            }

            $property = null;

            if ($callLog->getAccount()) {
                $property = $this->entityManager
                    ->getRepository('ApiBundle:Property')
                    ->findOneBy(['axNumber' => $callLog->getAccount()]);
            }

            if ($property) {
                $callLog->setProperty($property);

                $this->entityManager->persist($callLog);
            }

            $this->newCalls++;
            $this->output->write('.');
        }
        $this->output->writeln('');

        // Cleanup
        $this->entityManager->flush();
        $this->entityManager->clear();
    }

    public function postXml($url, $data)
    {
        $client = new Client(['exceptions' => false]);
        $response = $client->post($url, [
            'body' => $data,
            'headers' => ["Content-Type" => "application/xml; charset=utf-8"]
        ]);

        if ($response->getStatusCode() != 200) {
            throw new \Exception("Non 200 code returned. {$response->getStatusCode()}");
        }

        return $response->getBody()->getContents();
    }

    public function getCallsCount()
    {
        return $this->newCalls;
    }
}
