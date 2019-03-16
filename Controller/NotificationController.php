<?php

namespace Dywee\CMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class NotificationController extends ParentController
{
    protected $entityName = 'Notification';
    protected $publicName = 'Notification';
    protected $tableViewName = 'dywee_notification_table';
    protected $tableFindAllOrderBy = ['name' => 'asc'];
}
