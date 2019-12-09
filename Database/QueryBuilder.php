<?php 

class QueryBuilder {

    protected $pdo;

    protected static $instance; 

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public static function getInstance($pdo)
    {   

        if(is_null(static::$instance))
        {   
            static::$instance = new static($pdo);
        }

        return static::$instance;
    }

    public function insert($table, $parameters)
    {   
        $sql = sprintf(

            'insert into %s (%s) values (%s)',

            $table,

            implode(', ', array_keys($parameters)),

            ':' . implode(', :', array_keys($parameters))

        );

        try {

            $statement = $this->pdo->prepare($sql);

            $statement->execute($parameters);

            return $this->pdo->lastInsertId();

        } catch (Exception $e) {

            die("Exception!");

        }
    }

    public function update($table, $parameters, $whereParameters)
    {
        $counter = 1;

        $sql = sprintf(

            'update %s set %s where %s',

            $table,

            implode(' = ?, ', array_keys($parameters)) . ' = ?',

            implode(' = ? and ', array_keys($whereParameters)) . ' = ?'

        );

        try {

            $statement = $this->pdo->prepare($sql);

            foreach($parameters as $parameterKey => &$parameter)
            {
                $statement->bindParam($counter, $parameter);
                $counter++;
            }

            foreach($whereParameters as $parameterKey => &$parameter)
            {
                $statement->bindParam($counter, $parameter);
                $counter++;
            }

            $statement->execute();

        } catch (Exception $e) {

            die("Exception!");

        }


    }

}