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
      $cqr->questionReceipt->_value = 'Ack';
    }
    if (xDEBUG) { echo '<pre>'; print_r($param); print_r($ret); die(); }
    return $ret;
  }


  public function __construct(){
    webServiceServer::__construct('openquestion.ini');

    $this->curl = new curl();
  }

}
/*
 * MAIN
 */

$ws=new openQuestion();
$ws->handle_request();

?>
