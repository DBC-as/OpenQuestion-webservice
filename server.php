<?php
/**
 *
 * This file is part of Open Library System.
 * Copyright © 2009, Dansk Bibliotekscenter a/s,
 * Tempovej 7-11, DK-2750 Ballerup, Denmark. CVR: 15149043
 *
 * Open Library System is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Open Library System is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Open Library System.  If not, see <http://www.gnu.org/licenses/>.
*/


/**
 *
 *
 */

require_once('OLS_class_lib/webServiceServer_class.php');

class openQuestion extends webServiceServer {
    protected $curl;

    /** \brief priceCheck -
     *
     * Request:
     *   - CustomerIDType and CustomerID
     *   - one or more: MatIDType and MatID
     *
     * Response:
     */
    public function createQuestion($param) {
        define('xDEBUG', FALSE);
        $cqr = &$ret->createQuestionResponse->_value;
        if (!$this->aaa->has_right('openquestion', 500))
            $cqr->error->_value = 'authentication_error';
        else {
            $req->createQuestionRequest->_namespace = $this->xmlns['open'];
            foreach ($param as $key => $val)
                if ($key <> 'authentication' && $val->_value) 
                    $post_arr[$key] = $val->_value;
            $this->curl->set_post($post_arr);
            $this->curl->set_url($this->config->get_value('question_end_point', 'setup'));
            $this->watch->start('curl');
            $curl_result = $this->curl->get();
            $curl_err = $this->curl->get_status();
            $this->watch->stop('curl');

            if ($curl_result == 'Question created') {
                verbose::log(DEBUG, 'Created question with result: ' . $curl_result);
                $cqr->questionReceipt->_value = 'Ack';
            } else {
                verbose::log(DEBUG, 'createQuestion:: Rejected question: ' . 
                                    str_replace(array("\n", '    '), '', print_r($post_arr, TRUE)) . 
                                    ' With result: ' . $curl_result);
                $cqr->questionReceipt->_value = 'Nack';
            }
        }
        if (xDEBUG) {
            echo '<pre>';
            echo '<br />'; print_r($curl_result);
            echo '<br />'; print_r($curl_err);
            echo '<br />'; print_r($param);
            echo '<br />'; print_r($ret);
            die();
        }
        return $ret;
    }


    public function __construct() {
        webServiceServer::__construct('openquestion.ini');

        if (!$timeout = $this->config->get_value('curl_timeout', 'setup'))
            $timeout = 20;
        $this->curl = new curl();
        $this->curl->set_option(CURLOPT_TIMEOUT, $timeout);
    }

}
/*
 * MAIN
 */

$ws=new openQuestion();
$ws->handle_request();

?>
