<?php

/**
 * SQLite 19.02.2 Arcane Helper
 * https://github.com/MEDIA76/arcane
**/

return new class {
  private $database;
  private $construct;

  function __construct() {
    $this->database = new SQLite3(substr(__FILE__, 0, -3) . 'db');
    $this->construct = new class {
      function array($array) {
        return !is_array($array) ? [$array] : $array;
      }

      function statement($statement) {
        $statement = $this->array($statement);

        return implode(' ', array_map('trim', $statement));
      }

      function conditions($conditions) {
        $values = array_values($conditions);
        $columns = array_keys($conditions);

        $parameters = array_map(function($column) {
          return ":{$column}";
        }, $columns);
        $values = array_combine($parameters, $values);

        $conditions = array_map(function($column, $parameter) {
          return "{$column} = {$parameter}";
        }, $columns, $parameters);
        $conditions = implode(' AND ', $conditions);

        return [
          'conditions' => $conditions,
          'values' => $values
        ];
      }
    };
  }

  function create($table, $columns) {
    $columns = $this->construct->array($columns);

    $columns = implode(',', array_map(function($column, $value) {
      if(is_numeric($column)) {
        list($column, $value) = [$value, 'TEXT'];
      }

      return "{$column} {$value}";
    }, array_keys($columns), array_values($columns)));

    if(!stripos($columns, 'PRIMARY KEY')) {
      $columns = implode(',', [
        'id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL', $columns
      ]);
    }

    return $this->query([
      'CREATE TABLE IF NOT EXISTS', $table, "({$columns})"
    ]);
  }

  function insert($table, $columns) {
    $columns = $this->construct->array($columns);
    $values = array_values($columns);
    $columns = array_keys($columns);

    $parameters = array_map(function($column) {
      return ":{$column}";
    }, $columns);
    $values = array_combine($parameters, $values);

    foreach(['columns', 'parameters'] as $variable) {
      if(is_array($$variable)) {
        $$variable = implode(',', $$variable);
      }
    }

    return $this->query([
      'INSERT INTO', $table, "({$columns})", 'VALUES', "({$parameters})"
    ], $values);
  }

  function select($table, $conditions = null, $columns = '*') {
    if(is_array($conditions)) {
      extract($this->construct->conditions($conditions));

      $statement = ['WHERE', $conditions];
    } else {
      $columns = !is_null($conditions) ? $conditions : $columns;
    }

    return $this->fetch($this->query(array_merge([
      'SELECT', $columns, 'FROM', $table
    ], $statement ?? []), $values ?? []));
  }

  function delete($table, $conditions = null) {
    if(is_array($conditions)) {
      extract($this->construct->conditions($conditions));

      return $this->fetch($this->query([
        'DELETE FROM', $table, 'WHERE', $conditions
      ], $values));
    } else {
      return $this->fetch($this->query([
        'DELETE FROM', $table
      ]));
    }
  }

  function drop($table) {
    return $this->query([
      'DROP TABLE IF EXISTS', $table
    ]);
  }

  function query($statement, $values = []) {
    $statement = $this->construct->statement($statement);
    $statement = $this->database->prepare($statement);

    if($statement) {
      if(!empty($values)) {
        $values = $this->construct->array($values);

        foreach($values as $parameter => $value) {
          $statement->bindValue($parameter, $value);
        }
      }
    }

    return $statement ? $statement->execute() : $statement;
  }

  function fetch($result, $mode = SQLITE3_ASSOC) {
    if($result) {
      while($record = $result->fetchArray($mode)) {
        $records[] = $record;
      }
    }

    return $result ? $records ?? $record : $result;
  }
};

?>