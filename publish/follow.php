<?php

return [
    /*
     * User tables foreign key name.
     */
    'user_foreign_key' => 'user_id',

    /*
     * Table name for followers table.
     */
    'followables_table' => 'followables',

    /**
     * Model class name for followers table.
     */
    'followables_model' => \Jtar\HyperfFollow\Followable::class,
];
