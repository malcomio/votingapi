<?php

namespace Drupal\votingapi\Plugin\migrate\source\d6;

use Drupal\Core\Database\Query\SelectInterface;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Extension\ModuleHandler;
use Drupal\Core\State\StateInterface;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate\Row;
use Drupal\migrate_drupal\Plugin\migrate\source\DrupalSqlBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/*
 *
 *
D6
mysql> DESCRIBE drupal_votingapi_vote;
+--------------+------------------+------+-----+---------+----------------+
| Field        | Type             | Null | Key | Default | Extra          |
+--------------+------------------+------+-----+---------+----------------+
| vote_id      | int(10) unsigned | NO   | PRI | NULL    | auto_increment |
| content_type | varchar(64)      | NO   | MUL | node    |                |
| content_id   | int(10) unsigned | NO   |     | 0       |                |
| value        | float            | NO   |     | 0       |                |
| value_type   | varchar(64)      | NO   |     | percent |                |
| tag          | varchar(64)      | NO   |     | vote    |                |
| uid          | int(10) unsigned | NO   |     | 0       |                |
| timestamp    | int(10) unsigned | NO   | MUL | 0       |                |
| vote_source  | varchar(255)     | YES  |     | NULL    |                |

mysql> SELECT * FROM drupal_votingapi_vote LIMIT 1\G
*************************** 1. row ***************************
     vote_id: 5
content_type: node
  content_id: 461
       value: 100
  value_type: percent
         tag: vote
         uid: 3
   timestamp: 1244816483
 vote_source: 91.84.12.124

D8

mysql> DESCRIBE votingapi_result;
+-------------+------------------+------+-----+---------+----------------+
| Field       | Type             | Null | Key | Default | Extra          |
+-------------+------------------+------+-----+---------+----------------+
| id          | int(10) unsigned | NO   | PRI | NULL    | auto_increment |
| type        | varchar(32)      | YES  | MUL | NULL    |                |
| entity_type | varchar(64)      | YES  |     | NULL    |                |
| entity_id   | int(10) unsigned | YES  | MUL | NULL    |                |
| value       | float            | YES  |     | NULL    |                |
| value_type  | varchar(64)      | YES  |     | NULL    |                |
| function    | varchar(50)      | YES  |     | NULL    |                |
| timestamp   | int(11)          | YES  |     | NULL    |                |
+-------------+------------------+------+-----+---------+----------------+


mysql> DESCRIBE votingapi_vote;
+-------------+------------------+------+-----+---------+----------------+
| Field       | Type             | Null | Key | Default | Extra          |
+-------------+------------------+------+-----+---------+----------------+
| id          | int(10) unsigned | NO   | PRI | NULL    | auto_increment |
| type        | varchar(32)      | NO   | MUL | NULL    |                |
| uuid        | varchar(128)     | NO   | UNI | NULL    |                |
| entity_type | varchar(64)      | YES  |     | NULL    |                |
| entity_id   | int(10) unsigned | YES  | MUL | NULL    |                |
| value       | float            | YES  |     | NULL    |                |
| value_type  | varchar(64)      | YES  |     | NULL    |                |
| user_id     | int(10) unsigned | YES  | MUL | NULL    |                |
| timestamp   | int(11)          | YES  |     | NULL    |                |
| vote_source | varchar(255)     | YES  |     | NULL    |                |
| field_name  | varchar(255)     | YES  |     | NULL    |                |
+-------------+------------------+------+-----+---------+----------------+

 *
 */


/**
 * Drupal 6 vote source from database.
 *
 * @MigrateSource(
 *   id = "d6_vote"
 * )
 */
class Vote extends DrupalSqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    $query = $this->select('votingapi_vote', 'vv');
    $query->fields('vv', array(
      'vote_id',
      'content_type',
      'content_id',
      'value',
      'value_type',
      'tag',
      'uid',
      'timestamp',
      'vote_source',
    ));
    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = array(
      'vote_id' =>$this->t('Vote ID'),
      'content_type' =>$this->t('Content Type'),
      'content_id' =>$this->t('Content ID'),
      'value' =>$this->t('Value'),
      'value_type' =>$this->t('Value Type'),
      'tag' =>$this->t('Tag'),
      'uid' =>$this->t('User ID'),
      'timestamp' =>$this->t('Timestamp'),
      'vote_source' =>$this->t('IP Address'),
    );
    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    $ids['vote_id']['type'] = 'integer';
    $ids['vote_id']['alias'] = 'v';
    return $ids;
  }

}