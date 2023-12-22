<?php
      function connect(){
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "demo_2";
        
        $mysqli = new mysqli($servername,$username,$password,$dbname);
        if($mysqli->connect_errno != 0){
           return $mysqli->connect_error;
        }else{
           $mysqli->set_charset("utf8mb4");	
        }
        return $mysqli;
     }
      
     function get_all_req() {
      $mysqli = connect();
  
      $query = "SELECT req.req_status, req.request_id, us.name, us.surname, req.objective, req.det_obj, COUNT(req_doc.doc_id) AS count_req_doc
                FROM request req
                JOIN user us ON us.user_id = req.user_id
                LEFT JOIN request_doc req_doc ON req.request_id = req_doc.request_id
                GROUP BY req.request_id
                ORDER BY req.request_id DESC";
  
      $result = $mysqli->query($query);
  
      // Check if the query was successful
      if ($result) {
          $num_rows = $result->num_rows;
  
          if ($num_rows != 0) {
              $reqs = array();
  
              while ($row = $result->fetch_assoc()) {
                  $reqs[] = $row;
              }
  
              // Free the result set
              $result->free_result();
  
              // Close the database connection
              $mysqli->close();
  
              return $reqs;
          } else {
              // No results found
              // Free the result set
              $result->free_result();
  
              // Close the database connection
              $mysqli->close();
  
              return null;
          }
      } else {
          // Query execution failed
          // Close the database connection
          $mysqli->close();
  
          return null;
      }
  }
  
      
      function get_req_by_status($req_status){
        $mysqli = connect();
        $res = $mysqli->query("SELECT req.req_status, req.request_id, us.name, us.surname, req.objective, req.det_obj, count(req_doc.doc_id) AS count_req_doc
        FROM request req, user us, request_doc req_doc 
        WHERE us.user_id = req.user_id AND req.request_id = req_doc.request_id AND req_status = '$req_status'
        Group by req.request_id DESC");
        while($row = $res->fetch_assoc()){
            $reqs[] = $row;
        }
        return $reqs;
    }
      function get_basic_retrieved_doc($q){
         $mysqli = connect();
         $sql_all = $mysqli->query("SELECT doc.doc_id
         FROM document doc, department dep
         WHERE doc.dept_id = dep.dept_id 
         AND (doc.doc_id in (SELECT doc_id  
             FROM document
             WHERE doc_name LIKE ('%" . $q . "%') 
             OR doc_id LIKE ('%" . $q . "%')
             OR box_id LIKE ('%" . $q . "%')
             OR end_date LIKE ('%" . $q . "%')
             OR start_date LIKE ('%" . $q . "%')
             OR end_date LIKE ('%" . $q . "%')
             OR description LIKE ('%" . $q . "%')
             OR query LIKE ('%" . $q . "%')
             OR class_name LIKE ('%" . $q . "%')) 
           OR
           (dep.dept_id in (SELECT dept_id  
           FROM department
           WHERE dept_name LIKE ('%" . $q . "%'))))
         ORDER BY doc.end_date");
         $retrieves = [];
         while($row = $sql_all->fetch_array()){
            //$retrieves[] = $row['doc_id'];
            array_push($retrieves, $row['doc_id']);
        }
        return $retrieves;
      }




?>