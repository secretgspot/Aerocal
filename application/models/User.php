<?php

/**
 * User
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class User extends BaseUser
{
    const WRONG_PW = 1;
    const NOT_FOUND = 2;

    /**
     * Perform authenticatino of a user
     * @param string $username
     * @param string $password
     * @return Model_User
     */
    public static function authenticate($username, $password)
    {
        $user = Doctrine_Query::create()
                    ->from("User u")
                    ->leftJoin("u.AclRole r")
                    ->leftJoin("r.AclPermission p")
                    ->leftJoin("p.AclResource re")
                    ->leftJoin("p.AclResource pre")
                    ->addWhere("u.username = '$username'")
                    ->fetchOne();

        if ($user)
        {
            if ($user->password == $password)
                return $user;

            throw new Exception(self::WRONG_PW);
        }

        throw new Exception(self::NOT_FOUND);
    }
    
    public static function findAll()
    {
        return Doctrine_Query::create()
                    ->from("User u")
                    ->leftJoin("u.AclRole r")
                    ->fetchArray(true);
    }
}