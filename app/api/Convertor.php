<?php

namespace App\Api;

use App\Model\Comment;
use App\Model\Group;
use App\Model\Item;
use App\Model\Log;
use App\Model\Proposal;
use App\Model\Status;
use App\Model\User;
use App\Model\Vote;
use App\Model\VoteType;
use App\Model\Watch;

/**
 * Class Convertor
 * @package App\Api
 */
class Convertor
{

    /**
     * @param $proposals Proposal[]
     * @return array
     */
    public static function convertProposals($proposals)
    {
        $result = array();
        foreach ($proposals as $proposal) {
            $result[] = self::convertProposal($proposal);
        }
        return $result;
    }

    /**
     * @param $proposal Proposal
     * @return array
     */
    public static function convertProposal($proposal)
    {
        return [
            'id' => $proposal->getId(),
            'title' => $proposal->getTitle(),
            'status' => self::convertStatus($proposal->getStatus()),
            'voteType' => self::convertVoteType($proposal->getVoteType()),
            'group' => self::convertGroup($proposal->getVoteType()->getGroup()),
            'dateStart' => $proposal->getDateStart(),
            'dateEnd' => $proposal->getDateEnd(),
            'description' => $proposal->getDescription(),
            'items' => self::convertItems($proposal->getItems()),
            'watches' => self::convertWatches($proposal->getWatches()),
            'comments' => self::convertComments($proposal->getComments()),
            'logs' => self::convertLogs($proposal->getLogs()),
            'votes' => self::convertVotes($proposal->getVotes()),
            'trash' => $proposal->getTrash()
        ];
    }

    /**
     * @param $comments Comment[]
     * @return array
     */
    public static function convertComments($comments)
    {
        if (!isset($comments)) $comments = array();
        $result = array();
        foreach ($comments as $comment) {
            $result[] = self::convertComment($comment);
        }
        return $result;

    }

    /**
     * @param $logs Log[]
     * @return array
     */
    public static function convertLogs($logs)
    {
        if (!isset($logs)) $logs = array();
        $result = array();
        foreach ($logs as $log) {
            $result[] = self::convertLog($log);
        }
        return $result;

    }

    /**
     * @param $watches Watch[]
     * @return array
     */
    public static function convertWatches($watches)
    {
        if (!isset($watches)) $watches= array();
        $result = array();
        foreach ($watches as $watch) {
            $result[] = self::convertWatch($watch);
        }
        return $result;

    }

    /**
     * @param $votes Vote[]
     * @return array
     */
    public static function convertVotes($votes)
    {
        if (!isset($votes)) $votes= array();
        $result = array();
        foreach ($votes as $vote) {
            $result[] = self::convertVote($vote);
        }
        return $result;
    }

    /**
     * @param $vote Vote
     * @return array
     */
    public static function convertVote($vote)
    {
        return [
            'id' => $vote->getId(),
            'type' => $vote->getType(),
            'user' => self::convertUser($vote->getUser()),
            'date' => $vote->getDate(),
        ];
    }

    /**
     * @param $watch Watch
     * @return array
     */
    public static function convertWatch($watch)
    {
        return [
            'id' => $watch->getId(),
            'user' => self::convertUser($watch->getUser())
        ];
    }

    /**
     * @param $log Log
     * @return array
     */
    public static function convertLog($log)
    {
        return [
            'id' => $log->getId(),
            'text' => $log->getText(),
            'date' => $log->getDate(),
            'user' => self::convertUser($log->getUser())
        ];

    }

    /**
     * @param $comment Comment
     * @return array
     */
    public static function convertComment($comment)
    {
        return [
            'id' => $comment->getId(),
            'text' => $comment->getText(),
            'author' => self::convertUser($comment->getUser()),
            'date' => $comment->getDate(),
        ];
    }

    /**
     * @param $user User
     * @return array
     */
    public static function convertUser($user)
    {
        return [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'name' => $user->getUsername()
        ];
    }


    /**
     * @param $items Item[]
     * @return array
     */
    public static function convertItems($items)
    {
        $result = array();
        foreach ($items as $item) {
            $result[] = self::convertItem($item);
        }
        return $result;

    }


    /**
     * @param $item Item
     * @return array
     */
    public static function convertItem($item)
    {
        return [
            'id' => $item->getId(),
            'name' => $item->getName(),
            'amount' => $item->getAmount(),
            'price' => $item->getPrice(),
            'code' => $item->getCode(),
            'url' => $item->getUrl()
        ];
    }

    /**
     * @param $voteType VoteType
     * @return array
     */
    public static function convertVoteType($voteType)
    {
        return [
            'id' => $voteType->getId(),
            'text' => $voteType->getText(),
            'active' => $voteType->getActive(),
            'percentsToPass' => $voteType->getPercentsToPass(),
            'usersToPass' => $voteType->getUsersToPass(),
            'group' => self::convertGroup($voteType->getGroup()),
        ];
    }


    /**
     * @param $groups Group[]
     * @return array
     */
    public static function convertGroups($groups)
    {
        $result = array();
        foreach ($groups as $group) {
            $result[] = self::convertGroup($group);
        }
        return $result;
    }

    /**
     * @param $group Group
     * @return array
     */
    public static function convertGroup($group)
    {
        return [
            'id' => $group->getId(),
            'name' => $group->getName()
        ];
    }

    /**
     * @param $status Status
     * @return array
     */
    public static function convertStatus($status)
    {
        return [
            'id' => $status->getId(),
            'name' => $status->getName(),
        ];
    }

    /**
     * @param $voteTypes VoteType[]
     * @return array
     */
    public static function convertVoteTypes($voteTypes)
    {
        $result = array();
        foreach ($voteTypes as $voteType) {
            $result[] = self::convertVoteType($voteType);
        }
        return $result;

    }
}