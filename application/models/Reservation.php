<?php

/**
 * Reservation
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Reservation extends BaseReservation
{

    public static function findAll($now = true)
    {
        $date = date("Y-m-d G:i:s", time());

        $r = Doctrine_Query::create()
                    ->from("Reservation r")
                    ->leftJoin("r.User u")
                    ->leftJoin("r.Aircraft a")
                    ->leftJoin("a.AircraftType at")
                    ->leftJoin("r.ReservationStatus s")
                    ->leftJoin("s.ReservationType t");

        if ($now)
            $r->addWhere("r.end_date > '$date'");

        return $r->fetchArray(true);
    }

    public static function findByUser($userId)
    {
        $now = date("Y-m-d G:i:s", time());

        return Doctrine_Query::create()
                    ->from("Reservation r")
                    ->leftJoin("r.User u")
                    ->leftJoin("r.Aircraft a")
                    ->leftJoin("a.AircraftType at")
                    ->leftJoin("r.ReservationStatus s")
                    ->leftJoin("s.ReservationType t")
                    ->addWhere("u.id = $userId")
                    ->addWhere("r.end_date > '$now'")
                    ->fetchArray(true);
    }

    /**
     * Converts reservations array into Fullcalendar events
     * @param array $reservations
     * @param bool addNames add names to title?
     * @return array
     */
    public static function toEvents($reservations, $addName = false)
    {
        $rv = array();

        if (!empty($reservations))
        {
            foreach($reservations as $reservation)
            {
                $tmp = array();

                if($addName)
                    $tmp['title'] = $reservation['User']['first_name'] ." "
                                    .$reservation['User']['last_name'] .": "
                                    .$reservation['Aircraft']['name'];
                else
                    $tmp['title'] = $reservation['Aircraft']['name'];
                $tmp['title'] .= " - " . $reservation['Aircraft']['AircraftType']['type'];
                $tmp['start_date'] = $reservation['start_date'];
                $tmp['end_date'] = $reservation['end_date'];

                $rv[] = $tmp;
            }
        }
        return $rv;
    }

}