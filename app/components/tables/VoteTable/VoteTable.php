<?php

namespace App\Table;


use App\Model\Vote;
use Nette\Application\UI\Control;

class VoteTable extends Control
{
    /**
     * @var
     */
    private $votes;
    /**
     * @var
     */
    private $didNotVote;

    public function __construct($votes, $didNotVote)
    {
        $this->votes = $votes;
        $this->didNotVote = $didNotVote;
    }

    public function render()
    {
        $template = $this->template;
        $template->setFile(__DIR__ . '/VoteTable.latte');
        $positiveVotes = $this->getPositiveVotes($this->votes);
        $negativeVotes = $this->getNegativeVotes($this->votes);
        $template->positiveCount = count($positiveVotes);
        $template->negativeCount = count($negativeVotes);
        $template->didNotVoteCount = count($this->didNotVote);
        $template->votes = array_merge($positiveVotes,$negativeVotes,$this->didNotVote);
        $template->render();
    }

    private function getNegativeVotes($votes)
    {
        $result = array();
        foreach ($votes as $vote) {
            if (!$vote->getType()) {
                $result[] = $vote->getUser();
            }
        }
        return $result;
    }

    private function getPositiveVotes($votes)
    {
        $result = array();
        foreach ($votes as $vote) {
            if ($vote->getType()) {
                $result[] = $vote->getUser();
            }
        }
        return $result;
    }

}

interface IVoteTableFactory
{
    /**
     * @return VoteTable
     */
    public function create($votes, $didNotVote);
}
