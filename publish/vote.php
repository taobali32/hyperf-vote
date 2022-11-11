<?php

return [

    /*
     * User tables foreign key name.
     */
    'user_foreign_key' => 'user_id',

    /**
     * If uses table use uuid as primary, please set to true.
     */
    'users_use_uuids' => false,

    /*
     * Table name for vote records.
     */
    'votes_table' => 'votes',

    /*
     * Model name for Vote record.
     */
    'vote_model' => \Jtar\HyperfVote\Vote::class,

];
