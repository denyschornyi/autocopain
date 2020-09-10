<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App;

use \Illuminate\Database\Eloquent\Model;

/**
 * Description of Course
 *
 * @author umair-malik
 */
class EmailHistory extends Model {

    protected $table = 'email_history';
    protected $primaryKey = 'historyId';
    public $timestamps = false;

}
