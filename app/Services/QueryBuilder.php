<?php

namespace App\Services;

use Aura\SqlQuery\QueryFactory;
use PDO;

class QueryBuilder
{
    private $queryFactory;
    private $pdo;

    public function __construct(\PDO $pdo, QueryFactory $queryFactory)
    {
        $this->queryFactory = $queryFactory;
        $this->pdo = $pdo;
    }

    public function all($table) {

        $select = $this->queryFactory->newSelect();
        $select
            ->cols(['*'])
            ->from($table);

        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        return $sth->fetchAll(PDO::FETCH_ASSOC);

    }

    public function find($table, $id) {

        $select = $this->queryFactory->newSelect();
        $select
            ->cols(['*'])
            ->from($table)
            ->where('id = :id')
            ->bindValue('id', $id);

        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        return $sth->fetch(PDO::FETCH_ASSOC);

    }

    public function findCategoryId($table, $title) {

        $select = $this->queryFactory->newSelect();
        $select
            ->cols(['id'])
            ->from($table)
            ->where('title = :title')
            ->bindValue('title', $title);

        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        return $sth->fetch(PDO::FETCH_ASSOC)['id'];

    }

    public function create($table, $data) {

        $insert = $this->queryFactory->newInsert();
        $insert
            ->into($table)
            ->cols($data);

        $sth = $this->pdo->prepare($insert->getStatement());
        $sth->execute($insert->getBindValues());

// get the last insert ID
        $name = $insert->getLastInsertIdName('id');
        return $this->pdo->lastInsertId($name);

    }

    public function update($table, $id, $data) {

        $update = $this->queryFactory->newUpdate();
        $update
            ->table($table)
            ->cols($data)
            ->where('id = :id')
            ->bindValue('id', $id);

        $sth = $this->pdo->prepare($update->getStatement());
        $sth->execute($update->getBindValues());

    }

    public function delete($table, $id) {

        $delete = $this->queryFactory->newDelete();
        $delete
            ->from($table)
            ->where('id = :id')
            ->bindValue('id', $id);

        $sth = $this->pdo->prepare($delete->getStatement());
        $sth->execute($delete->getBindValues());

    }

    public function getUsersPhotos($table, $user_id)
    {
        $select = $this->queryFactory->newSelect();
        $select
            ->cols(['*'])
            ->from($table)
            ->where('user_id = :user_id')
            ->bindValue('user_id', $user_id);

        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        return $sth->fetchAll(PDO::FETCH_ASSOC);

    }

    public function getPhotoOwner($id)
    {
        $select = $this->queryFactory->newSelect();
        $select
            ->cols(['*'])
            ->from('users')
            ->join(
                'LEFT',             // the join-type
                'image',        // join to this table ...
                'users.id = image.user_id' // ... ON these conditions
            )
            ->where("image.id = $id");


        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        return $sth->fetch(PDO::FETCH_ASSOC);
    }
}