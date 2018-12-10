<?php

/**
 * Class PdoAdapter
 *
 * General class for connecting to database
 */
class PdoAdapter extends Model
{
    /**
     * Pdo class instance
     *
     * @var PDO|null
     */
    protected static $_pdo = null;

    /**
     * Get instance
     */
    public static function getInstance()
    {
        if (!self::$_pdo) {
            self::$_pdo = self::getPdo('localhost', 'elena', 'root', '');
        }
        return self::$_pdo;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Clone
     */
    private  function __clone()
    {
    }

    public function getPdo($host, $dbName, $userName, $password)
    {
        try{
            return new PDO('mysql:host='.$host.';dbname='.$dbName, $userName, $password);
        } catch (PDOException $e) {
            echo 'Could not connect to the database';
        }
    }

    /**
     * Get select
     *
     * Condition should be formed like 'condition' => 'value'
     *
     * @param array $conditionArray
     * @return array
     */
    public function selectByCondition($conditionArray = null)
    {
        $pdo = self::getInstance();
        $className = $this->getTableName();
        if($conditionArray){
            $condition = $className . ' ' . $this->formCondition($conditionArray);
            $sql = "SELECT * FROM $condition";
        } else {
            $sql = "SELECT * FROM $className";
        }
        try{
            $pdo->beginTransaction();
            $stmt = $pdo->prepare($sql);
            $stmt->execute($conditionArray);
            $resultData = $stmt->fetchAll();
            $pdo->commit();
            if ($resultData){
                return $resultData;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            $pdo->rollBack();
            echo $e->getMessage();
        }

    }

    /**
     * String condition forming by array
     *
     * @param array $conditionArray
     * @return string
     */
    public function formCondition($conditionArray)
    {
        $condition = 'WHERE ';
        foreach ($conditionArray as $key => $value) {
            $condition .= "$key=:$key AND ";
        }
        return substr($condition, 0, -4);
    }

    /**
     * Get table name of model, which is uses
     *
     * @return string
     */
    public function getTableName()
    {
        return strtolower(get_called_class());
    }

    /**
     * Insert function
     *
     * @param array $valuesArray
     * @return string
     */
    public function insert($valuesArray)
    {
        $pdo = self::getInstance();
        $fields = $this->generateValuesForInsert(array_keys($valuesArray));
        $preparedValues = $this->generateValuesForInsert(array_keys($valuesArray),true);
        $tableName = $this->getTableName();
        $sql = "INSERT INTO $tableName $fields VALUES $preparedValues";
        try{
            $pdo->beginTransaction();
            $stmt = $pdo->prepare($sql);
            $stmt->execute($valuesArray);
            $lastInsertId = $pdo->lastInsertId();
            $pdo->commit();
            return $lastInsertId;
        } catch (PDOException $e) {
            $pdo->rollBack();
            echo $e->getMessage();
        }

    }

    /**
     * Generates values string for insert query
     *
     * @param   array   $valuesArray
     * @param null|bool $isPrepare
     * @return string
     */
    public function generateValuesForInsert($valuesArray, $isPrepare = null)
    {
        $resultString = '(';
        if ($isPrepare) {
            foreach($valuesArray as $value){
                $resultString .= ':' . $value . ', ';
            }
        } else {
            foreach($valuesArray as $value){
                $resultString .= $value . ', ';
            }
        }
        $resultString = substr($resultString, 0, -2);
        return $resultString . ')';
    }

    /**
     * Generate SET part of query in update from array ('field' => 'value')
     *
     * @param array $valuesToChange
     * @return string
     */
    public function generateSetCondition($valuesToChange)
    {
        $condition = 'SET ';
        foreach ($valuesToChange as $key => $value) {
            $condition .= "$key=:$key" . "1, ";
        }
        return substr($condition, 0, -2);
    }

    /**
     * Update function
     *
     * Arrays should be formed like 'field' => 'value'
     *
     * @param array $valuesToChange
     * @param array $condition
     */
    public function update($valuesToChange, $condition)
    {
        $pdo = self::getInstance();
        $tableName = $this->getTableName();
        $setCondition = $this->generateSetCondition($valuesToChange);
        foreach($valuesToChange as $key => $val) {
            $valuesToChange[$key . '1'] = $valuesToChange[$key];
            unset($valuesToChange[$key]);
        }
        $whereCondition = $this->formCondition($condition);
        $sql = "UPDATE $tableName $setCondition $whereCondition";
        try{
            $pdo->beginTransaction();
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array_merge($valuesToChange, $condition));
            $pdo->commit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            echo $e->getMessage();
        }
    }

    /**
     * Delete data from the database by condition
     *
     * @param array $conditionArray
     */
    public function delete($conditionArray)
    {
        $pdo = self::getInstance();
        $tableName = $this->getTableName();
        $condition = $tableName . ' ' . $this->formCondition($conditionArray);
        $sql = "DELETE FROM $condition";
        try{
            $pdo->beginTransaction();
            $stmt = $pdo->prepare($sql);
            $stmt->execute($conditionArray);
            $pdo->commit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            echo $e->getMessage();
        }
    }
}
