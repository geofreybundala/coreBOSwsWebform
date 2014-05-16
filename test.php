<?php
/*************************************************************************************************
 * Copyright 2014 JPL TSolucio, S.L. -- This file is a part of TSOLUCIO coreBOS customizations.
 * You can copy, adapt and distribute the work under the "Attribution-NonCommercial-ShareAlike"
 * Vizsage Public License (the "License"). You may not use this file except in compliance with the
 * License. Roughly speaking, non-commercial users may share and modify this code, but must give credit
 * and share improvements. However, for proper details please read the full License, available at
 * http://vizsage.com/license/Vizsage-License-BY-NC-SA.html and the handy reference for understanding
 * the full license at http://vizsage.com/license/Vizsage-Deed-BY-NC-SA.html. Unless required by
 * applicable law or agreed to in writing, any software distributed under the License is distributed
 * on an  "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and limitations under the
 * License terms of Creative Commons Attribution-NonCommercial-ShareAlike 3.0 (the License).
 *************************************************************************************************
 *  Module       : coreBOSwsWebform
 *  Version      : 0.x
 *  Author       : JPL TSolucio, S. L.
 *************************************************************************************************/

require_once('WsWebform.php');

// Emulate data filled from an array, this replaces: $data = $_REQUEST;
$uid = time();
$data = array(
    'firstname' => 'n' . $uid,
    'lastname' => 'ln' . $uid,
    'email' => $uid . '@example.com',
    'potential_name' => 'My Potential ' . $uid,
    'potential_amount' => $uid / 1000,
    'potential_closingdate' => date('Y-m-d'),
    'potential_sales_stage' => 'Prospecting',
);

// Config data for the REST service
$config = array(
    'url' => 'http://localhost/corebos/',
    'user' => 'admin',
    'password' => '',
    'map' => array(
        'Contacts' => array(
            'fields' => array(
                'firstname',
                'lastname',
                'email',
            ),
            'matching' => array(
                'or' => array(
                    'and' => array(
                        'firstname',
                        'lastname',
                    ),
                    'email',
                ),
            ),
            'has' => array(
                'Potentials' => array(
                    'fields' => array(
                        'related_to' => '[Contacts]',
                        'potentialname' => 'potential_name',
                        'closingdate' => 'potential_closingdate',
                        'sales_stage' => 'potential_sales_stage',
                    ),
                    'matching' => array(
                        'related_to',
                        'potentialname',
                    ),
                ),
            ),
        ),
    ),
);

$webform = new WsWebform($config);

// Process the form
$webform->send($data);

// Process the form again to test the duplicate matching
$webform->send($data);
