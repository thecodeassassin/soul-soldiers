<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace Soul\Model\StaticModel;


use Soul\Model\StaticModel\DataList\ListInterface;
use Soul\Module;
use Soul\Util;

class DataList extends Module
{

    protected  $availableLists = [
        'PayedUsers' => 'Lijst van betalende gebruikers voor volgend evenement.',
        'EventUsers' => 'Lijst van inschrijvingen voor het volgende evenement. '
    ];

    protected  $listName = '';

    /**
     * @param $name
     *
     * @throws Exception
     */
    public function __construct($name)
    {
        if ((!$name || !array_key_exists($name, $this->availableLists)) && (get_called_class() != __CLASS__)) {
            throw new Exception("$name is not a valid list");
        }

        $this->listName = $name;
    }

    /**
     * @return string
     */
    public function getListName()
    {
        return $this->listName;
    }

    /**
     * @param string $listName
     */
    public function setListName($listName)
    {
        $this->listName = $listName;
    }

    /**
     * @return array
     */
    public function getAvailableLists()
    {
        return $this->availableLists;
    }

    /**
     * @param array $availableLists
     */
    public function setAvailableLists($availableLists)
    {
        $this->availableLists = $availableLists;
    }

    /**
     * Get the data as an array
     *
     * @return array
     */
    public function getData()
    {
        $listObjectName = "\\Soul\\Model\\StaticModel\\DataList\\$this->listName";

        $dataObject = $this->getDataObject($listObjectName);

        return $dataObject->getData();

    }

    /**
     * @param $listObjectName
     *
     * @return ListInterface
     * @throws Exception
     */
    protected function getDataObject($listObjectName)
    {
        if (!class_exists($listObjectName)) {
            throw new Exception(sprintf("DataList object %s not found!",$listObjectName));
        }

        $dataObject = new $listObjectName();

        if (!$dataObject instanceof ListInterface) {
            throw new Exception(sprintf("DataList object %s is not a valid DataList object!",$listObjectName));
        }

        return $dataObject;
    }

    /**
     * @return string
     */
    public function outputAsCSV()
    {
        $data = $this->getData();
        $output = '';

        $output .= Util::arrayToCSV(array_keys($data[0]), ';', '"', false) . PHP_EOL;
        foreach ($data as $item) {
            $output .= Util::arrayToCSV($item, ';', '"', false) .  PHP_EOL;
        }

//        die(var_dump($output));
        return $output;
    }

    /**
     * @return array
     */
    public static function getAvailableListsStatic()
    {
        $obj = new self(null);
        $list = $obj->getAvailableLists();
        unset($obj);
        return $list;
    }
}