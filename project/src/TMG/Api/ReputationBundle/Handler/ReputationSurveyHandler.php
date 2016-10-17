<?php

namespace TMG\Api\ReputationBundle\Handler;

use TMG\Api\ApiBundle\Entity\Repository\ReputationSurveyRepository;
use TMG\Api\ApiBundle\Entity\ReputationCustomer;
use TMG\Api\ApiBundle\Entity\ReputationSource;
use TMG\Api\ApiBundle\Entity\ReputationSurvey;
use TMG\Api\ApiBundle\Handler\ApiHandler;
use TMG\Api\ApiBundle\Entity\ReputationQuestion;
use stdClass;
use TMG\Api\ApiBundle\Util\PagingInfo;

class ReputationSurveyHandler extends ApiHandler
{
    /**
     * @param array $data
     * @return array
     */
    public function getDataTableResponse(array $data)
    {
        $response = [];
        /** @var ReputationSurvey $survey */
        foreach ($data as $survey) {
            $temp = new stdClass();

            /** @var ReputationCustomer $customer */
            $customer = $survey->getCustomer();
            /** @var ReputationSource $source */
            $source = $survey->getSource();

            $temp->name = $customer->getFirstName() . ' ' . $customer->getLastName();
            $temp->source = $source->getName();
            $responseDate = $survey->getResponseDate();
            $temp->response_date = date('m/d/Y', $responseDate->getTimestamp());
            $temp->overall_rating = $survey->getOverallRating();
            $temp->questions = $this->getQuestionsForDatatables($survey->getQuestions());
            $temp->email = $customer->getEmail();
            $comment = $this->getSurveyComment($survey->getQuestions());
            $temp->comment = $comment ? $comment : '';

            $response[] = (array) $temp;
        }

        return $response;
    }

    /**
     * handleSurveySnapshot function.
     *
     * @access public
     * @param mixed $id
     * @return array
     */
    public function getSurveySnapshot($id)
    {
        $results = [];
        $pagingInfo = new PagingInfo();

        /** @var ReputationSurveyRepository $surveyRepository */
        $surveyRepository = $this->repository;
        do {
            $results = array_merge($results, $surveyRepository->findPropertySurveys($id, $pagingInfo));
            $pagingInfo->setPage(($pagingInfo->getPage() + 1) * $pagingInfo->getCount());
        } while ($surveyRepository->findPropertySurveys($id, $pagingInfo));

        return $this->parseSurveyData($results);
    }


    /**
     * parseSurveyData function.
     *
     * @access private
     * @param mixed $reputationSurveys
     * @return array
     */
    private function parseSurveyData($reputationSurveys)
    {
        $aggregatedAnswers = [];
        $summaryData = [];

        /** @var ReputationSurvey $reputationSurvey */
        foreach ($reputationSurveys as $reputationSurvey) {
            $questions = $reputationSurvey->getQuestions();
            foreach ($questions as $q) {
                $aggregatedAnswers[$q->getQuestion()][] = $q->getShortAnswer();
            }
        }

        foreach ($aggregatedAnswers as $question => $answers) {
            switch ($question) {
                case 'Please rate the cleanliness of your room upon check-in.':
                    $summaryData['cleanliness'] = $this->generateSnapshot($answers);
                    break;

                case 'Please rate the friendliness and courtesy of our staff.':
                    $summaryData['friendliness'] = $this->generateSnapshot($answers);
                    break;

                case 'Please rate the location of the property.':
                    $summaryData['location'] = $this->generateSnapshot($answers);
                    break;
            }
        }

        return $summaryData;
    }

    /**
     * generateSnapshot function.
     *
     * take an array of numbers and return an array of simple statistics
     *
     * @access private
     * @param array $data
     * @return array
     */
    private function generateSnapshot($data)
    {
        $avg = 0;
        $count = 0;
        $max = null;
        $min = null;
        $sum = 0;

        foreach ($data as $d) {
            if (is_numeric($d)) {
                if ($max < $d || empty($max)) {
                    $max = (float) $d;
                }

                if ($min > $d || empty($min)) {
                    $min = (float) $d;
                }

                $sum += $d;
                ++$count;
            }
            
            if ($count) {
                $avg = $sum / $count;
            }
        }

        return [
            'avg' => $avg,
            'count' => $count,
            'max' => $max,
            'min' => $min,
            'sum' => $sum,
        ];
    }

    /**
     * @param $questions
     * @return null
     */
    private function getSurveyComment($questions)
    {
        foreach ($questions as $question) {
            if ($question->getQuestion() == ReputationQuestion::OVER_ALL_RATING) {
                return $question->getShortAnswer();
            }
        }
        return null;
    }

    /**
     * @param $questions
     * @return array
     */
    private function getQuestionsForDataTables($questions)
    {
        $response = [];
        foreach ($questions as $question) {
            $temp = new stdClass();
            $temp->question = $question->getQuestion();
            $temp->answer = $question->getLongAnswer();
            $response[] = (array) $temp;
        }

        return $response;
    }
}
