<?php
/**
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 * @package Challonge
 */

namespace Soul\Tournaments;


use SimpleXMLElement;

/**
 * Class Challonge
 *
 * @package Soul\Tournaments
 */
class Challonge
{
    // Attributes
    private $api_key;
    public $errors = array();
    public $warnings = array();
    public $status_code = 0;
    public $verify_ssl = true;
    public $result = false;

    public $subDomain = null;


    /**
     * @param null $subDomain
     */
    public function setSubDomain($subDomain)
    {
        $this->subDomain = $subDomain;
    }

    /**
     * @return null
     */
    public function getSubDomain()
    {
        return $this->subDomain;
    }

    /**
     * @param string $api_key
     * @param null $subDomain
     */
    public function __construct($api_key = '', $subDomain = null)
    {
        $this->api_key = $api_key;

        if ($subDomain) {
            $this->setSubDomain($subDomain);
        }
    }

    /*
      makeCall()
      $path - String
      $params - array()
      $method - String (get, post, put, delete)
    */
    public function makeCall($path = '', $params = array(), $method = 'get')
    {

        // Clear the public vars
        $this->errors = array();
        $this->status_code = 0;
        $this->result = false;

        // Append the api_key to params so it'll get passed in with the call
        $params['api_key'] = $this->api_key;

        // Build the URL that'll be hit. If the request is GET, params will be appended later
        $call_url = "https://challonge.com/api/" . $path . '.xml';

        $curl_handle = curl_init();
        // Common settings
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);

        if (!$this->verify_ssl) {
            // WARNING: this would prevent curl from detecting a 'man in the middle' attack
            curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, 0);
        }

        $curlheaders = array(); //array('Content-Type: text/xml','Accept: text/xml');

        // Determine REST verb and set up params
        switch (strtolower($method)) {
            case "post":
                $fields = http_build_query($params, '', '&');
                $curlheaders[] = 'Content-Length: ' . strlen($fields);
                curl_setopt($curl_handle, CURLOPT_POST, 1);
                curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $fields);
                break;
            case 'put':
                $fields = http_build_query($params, '', '&');
                $curlheaders[] = 'Content-Length: ' . strlen($fields);
                curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $fields);
                break;
            case 'delete':
                $params["_method"] = "delete";
                $fields = http_build_query($params, '', '&');
                $curlheaders[] = 'Content-Length: ' . strlen($fields);
                curl_setopt($curl_handle, CURLOPT_POST, 1);
                curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $fields);
                // curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;
            case "get":
            default:
                $call_url .= "?" . http_build_query($params, "", "&");
        }

        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $curlheaders);
        curl_setopt($curl_handle, CURLOPT_URL, $call_url);

        $curl_result = curl_exec($curl_handle);
        $info = curl_getinfo($curl_handle);
        $this->status_code = (int)$info['http_code'];
        $return = false;
        if ($curl_result === false) {
            // CURL Failed
            $this->errors[] = curl_error($curl_handle);
        } else {
            switch ($this->status_code) {

                case 401: // Bad API Key
                case 422: // Validation errors
                case 404: // Not found/Not in scope of account

                    if (strpos($curl_result,'Access denied') !== false) {
                        $this->errors[] = $curl_result;
                    } else {

                        $return = $this->result = new SimpleXMLElement($curl_result);
                        foreach ($return->error as $error) {
                            $this->errors[] = $error;
                        }
                        $return = false;
                    }
                    break;

                case 500: // Oh snap!
                    $return = $this->result = false;
                    $this->errors[] = "Server returned HTTP 500";
                    break;

                case 200:
                    $return = $this->result = new SimpleXMLElement($curl_result);
                    // Check if the result set is nil/empty
                    if (sizeof($return) == 0) {
                        $this->errors[] = "Result set empty";
                        $return = false;
                    }
                    break;

                default:
                    $this->errors[] = "Server returned unexpected HTTP Code ($this->status_code)";
                    $return = false;
            }
        }

        curl_close($curl_handle);
        return $return;
    }

    /**
     * @param array $params
     * @return bool|SimpleXMLElement
     */
    public function getTournaments($params = array())
    {
        return $this->makeCall('tournaments', $params, 'get');
    }

    /**
     * @param $tournament_id
     * @param array $params
     * @return bool|SimpleXMLElement
     */
    public function getTournament($tournament_id, $params = array())
    {
        $tournament_id = $this->getRealTournamentId($tournament_id);

        return $this->makeCall("tournaments/$tournament_id", $params, "get");
    }

    /**
     * @param array $params
     * @return bool|SimpleXMLElement
     */
    public function createTournament($params = array())
    {
        if (sizeof($params) == 0) {
            $this->errors = array('$params empty');
            return false;
        }
        return $this->makeCall("tournaments", $params, "post");
    }

    /**
     * @param $tournament_id
     * @param array $params
     * @return bool|SimpleXMLElement
     */
    public function updateTournament($tournament_id, $params = array())
    {
        $tournament_id = $this->getRealTournamentId($tournament_id);

        return $this->makeCall("tournaments/$tournament_id", $params, "put");
    }

    /**
     * @param $tournament_id
     * @return bool|SimpleXMLElement
     */
    public function deleteTournament($tournament_id)
    {
        $tournament_id = $this->getRealTournamentId($tournament_id);

        return $this->makeCall("tournaments/$tournament_id", array(), "delete");
    }

    /**
     * @param $tournament_id
     * @param array $params
     * @return bool|SimpleXMLElement
     */
    public function publishTournament($tournament_id, $params = array())
    {
        $tournament_id = $this->getRealTournamentId($tournament_id);

        return $this->makeCall("tournaments/publish/$tournament_id", $params, "post");
    }

    /**
     * @param $tournament_id
     * @param array $params
     * @return bool|SimpleXMLElement
     */
    public function startTournament($tournament_id, $params = array())
    {
        $tournament_id = $this->getRealTournamentId($tournament_id);

        return $this->makeCall("tournaments/start/$tournament_id", $params, "post");
    }

    /**
     * @param $tournament_id
     * @param array $params
     * @return bool|SimpleXMLElement
     */
    public function endTournament($tournament_id, $params = array())
    {
        $tournament_id = $this->getRealTournamentId($tournament_id);

        return $this->makeCall("tournaments/finalize/$tournament_id", $params, "post");
    }

    /**
     * @param $tournament_id
     * @param array $params
     * @return bool|SimpleXMLElement
     */
    public function resetTournament($tournament_id, $params = array())
    {
        $tournament_id = $this->getRealTournamentId($tournament_id);

        return $this->makeCall("tournaments/reset/$tournament_id", $params, "post");
    }

    /**
     * @param $tournament_id
     * @return bool|SimpleXMLElement
     */
    public function getParticipants($tournament_id)
    {
        $tournament_id = $this->getRealTournamentId($tournament_id);

        return $this->makeCall("tournaments/$tournament_id/participants");
    }

    /**
     * @param $tournament_id
     * @param $participant_id
     * @param array $params
     * @return bool|SimpleXMLElement
     */
    public function getParticipant($tournament_id, $participant_id, $params = array())
    {
        $tournament_id = $this->getRealTournamentId($tournament_id);

        return $this->makeCall("tournaments/$tournament_id/participants/$participant_id", $params);
    }

    /**
     * @param $tournament_id
     * @param array $params
     * @return bool|SimpleXMLElement
     */
    public function createParticipant($tournament_id, $params = array())
    {
        $tournament_id = $this->getRealTournamentId($tournament_id);

        if (sizeof($params) == 0) {
            $this->errors = array('$params empty');
            return false;
        }
        return $this->makeCall("tournaments/$tournament_id/participants", $params, "post");
    }

    /**
     * @param $tournament_id
     * @param $participant_id
     * @param array $params
     * @return bool|SimpleXMLElement
     */
    public function updateParticipant($tournament_id, $participant_id, $params = array())
    {
        $tournament_id = $this->getRealTournamentId($tournament_id);

        return $this->makeCall("tournaments/$tournament_id/participants/$participant_id", $params, "put");
    }

    /**
     * @param $tournament_id
     * @param $participant_id
     * @return bool|SimpleXMLElement
     */
    public function deleteParticipant($tournament_id, $participant_id)
    {
        $tournament_id = $this->getRealTournamentId($tournament_id);

        return $this->makeCall("tournaments/$tournament_id/participants/$participant_id", array(), "delete");
    }

    /**
     * @param $tournament_id
     * @return bool|SimpleXMLElement
     */
    public function randomizeParticipants($tournament_id)
    {
        $tournament_id = $this->getRealTournamentId($tournament_id);

        return $this->makeCall("tournaments/$tournament_id/participants/randomize", array(), "post");
    }

    /**
     * @param $tournament_id
     * @param array $params
     * @return bool|SimpleXMLElement
     */
    public function getMatches($tournament_id, $params = array())
    {
        $tournament_id = $this->getRealTournamentId($tournament_id);

        return $this->makeCall("tournaments/$tournament_id/matches", $params);
    }

    /**
     * @param $tournament_id
     * @param $match_id
     * @return bool|SimpleXMLElement
     */
    public function getMatch($tournament_id, $match_id)
    {
        $tournament_id = $this->getRealTournamentId($tournament_id);

        return $this->makeCall("tournaments/$tournament_id/matches/$match_id");
    }

    /**
     * @param $tournament_id
     * @param $match_id
     * @param array $params
     * @return bool|SimpleXMLElement
     */
    public function updateMatch($tournament_id, $match_id, $params = array())
    {
        $tournament_id = $this->getRealTournamentId($tournament_id);

        if (sizeof($params) == 0) {
            $this->errors = array('$params empty');
            return false;
        }
        return $this->makeCall("tournaments/$tournament_id/matches/$match_id", $params, "put");
    }

    /**
     * @param $tournamentId
     * @return string
     */
    protected function getRealTournamentId($tournamentId) {
        if ($this->subDomain) {
            return sprintf('%s-%s', $this->subDomain, $tournamentId);
        }

        return $tournamentId;
    }

}