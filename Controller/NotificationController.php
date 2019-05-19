<?php

namespace Dywee\CMSBundle\Controller;

class NotificationController extends ParentController
{
    protected $entityName = 'Notification';
    protected $publicName = 'Notification';
    protected $tableViewName = 'dywee_notification_table';
    protected $tableFindAllOrderBy = ['name' => 'asc'];
}
