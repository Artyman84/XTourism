<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 01.05.2015
 * Time: 8:35
 */

return array(
    'guest' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Guest',
        'bizRule' => null,
        'data' => null
    ),

    'agent' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Agent',
        'children' => array(
            'guest', // унаследуемся от гостя
        ),
        'bizRule' => null,
        'data' => null
    ),

    'moderator' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Moderator',
        'children' => array(
            'agent', // унаследуемся от гостя
        ),
        'bizRule' => null,
        'data' => null
    ),

    'admin' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Admin',
        'children' => array(
            'moderator',          // позволим админу всё, что позволено агенту
        ),
        'bizRule' => null,
        'data' => null
    ),

    'superadmin' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Super Admin',
        'children' => array(
            'admin',         // позволим супер-админу всё, что позволено админу
        ),
        'bizRule' => null,
        'data' => null
    ),

);