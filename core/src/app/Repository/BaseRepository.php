<?php


namespace App\Repository;


use App\Utility\Logger\Log;
use Medoo\Medoo;
use SplSubject;

class BaseRepository implements \SplObserver
{
    /**
     * @var Medoo
     */
    protected Medoo $db;

    protected $dbEventMap = [
      Medoo::EVENT_CON_START_AT => 'eventConStartAt',
      Medoo::EVENT_CON_END_AT => '',
      Medoo::EVENT_CON_BEFORE => '',
      Medoo::EVENT_CON_SUCCESS => '',
      Medoo::EVENT_CON_FAILS => '',
      Medoo::EVENT_QUERY_START_AT => '',
      Medoo::EVENT_QUERY_END_AT => '',
      Medoo::EVENT_QUERY_BEFORE => '',
      Medoo::EVENT_QUERY_SUCCESS => '',
      Medoo::EVENT_QUERY_FAILS => '',
    ];
    /**
     * @var Log
     */
    protected Log $log;

    public function __construct(Medoo $database, Log $log)
    {
        $this->db = $database;
        $this->log = $log;
    }

    public function update(SplSubject $subject, $event = null, $data = null)
    {
        $eventFn = $this->getDbEventMap($event);
        if (!$eventFn) return;

        call_user_func_array([$this, $eventFn], [$data]);
    }

    public function getDbEventMap($name = null)
    {
        $event = $this->dbEventMap;

        if (is_null($name)) return $event;

        return $event[$name] ?? null;
    }

    protected function eventConStartAt($data)
    {
        $this->log->set('database.connection_start_at', $data);
    }

}