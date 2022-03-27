<?php
class Cities
{
    //Databse related properties
    private $conn;

    //Properties
    public $id;
    public $city;
    public $start_date;
    public $end_date;
    public $price;
    public $status;
    public $color;

    //DB constructor
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // read all data from databse
    public function read_all()
    {
        // query
        $sql = "SELECT * FROM cities_tbl;";

        // Prepare statement
        $stmt = $this
            ->conn
            ->prepare($sql);

        //Execute the query
        $stmt->execute();

        // Get the output
        $result = $stmt->get_result();

        return $result;

    }

    // read data from database filter by date range
    public function read_from_to($from_date, $to_date)
    {
        // query
        $sql = "SELECT * FROM cities_tbl WHERE start_date > ? AND end_date < ?  ORDER BY start_date;";
        // $sql = "SELECT * FROM cities_tbl WHERE start_date BETWEEN ? AND ? OR end_date BETWEEN ? AND ?  ORDER BY start_date;";
        // Prepare statement
        $stmt = $this
            ->conn
            ->prepare($sql);

        // binding
        $stmt->bind_param('ss', $from_date, $to_date);

        //Execute the query
        $stmt->execute();

        // Get the output
        $result = $stmt->get_result();

        return $result;
    }

    // read the json data and insert them to table
    public function insert_data_in_db($json_file)
    {
        // Read the JSON file
        $json = file_get_contents($json_file);

        // Decode the JSON file
        $json_data = json_decode($json, true);

        // Disabling the auto commit funtion
        $this
            ->conn
            ->autocommit(false);

        // Flag if the execution was successful
        $query_success = true;

        if (is_array($json_data))
        {
            $Data_array = array();
            foreach ($json_data as $row)
            {
                $this->id = $this->conn->real_escape_string($row['id']);
                $this->city = $this->conn->real_escape_string($row['city']);
                $this->start_date = $this->conn->real_escape_string($row['start_date']);
                $this->start_date = date('Y-m-d', strtotime($this->start_date)); // Convert the date format to mysql date format
                $this->end_date = $this->conn->real_escape_string($row['end_date']);
                $this->end_date = date('Y-m-d', strtotime($this->end_date)); // Convert the date format to mysql date format
                $this->price = $this->conn->real_escape_string($row['price']);
                $this->status = $this->conn->real_escape_string($row['status']);
                $this->color = $this->conn->real_escape_string($row['color']);

                // ######################### Insert the data row one by one ############################
                $sql_query = "REPLACE INTO cities_tbl (id, city, start_date, end_date, price, status, color) VALUES (?,?,?,?,?,?,?)";
                $stmt = $this->conn->prepare($sql_query);
                $stmt->bind_param('sssssss', $this->id, $this->city, $this->start_date, $this->end_date, $this->price, $this->status, $this->color);
                if (!$stmt->execute())
                {
                    $query_success = false;
                }
                // ######################### Insert the data row one by one ############################
                $Data_array[] = "('$this->id', '$this->city', '$this->start_date', '$this->end_date', '$this->price', '$this->status', '$this->color')";
            }

            // ######################### Insert the whole data at once ############################
            // // print_r($Data_array) ;
            //
            // $Data_array_imp = implode(',', $Data_array);
            //
            // echo $Data_array_imp;
            //
            // $sql_query = "REPLACE INTO cities_tbl (id, city, start_date, end_date, price, status, color) VALUES ";
            // $sql_query .= $Data_array_imp;
            //
            // $stmt = $this->conn->prepare($sql_query);
            //
            // if (!$this->conn($stmt)) {
            //      $query_success = FALSE;
            //  }
            // ######################### Insert the whole data at once ############################
            if ($query_success == true)
            {
                $this->conn->commit();
                return 'Done';
            }
            else
            {
                $this->conn->rollback();
                return 'Not Done';
            }

        }

    }

    public function update_data()
    {
        $sql = 'UPDATE cities_tbl SET city = ?, start_date = ?, end_date = ?, price = ?, status = ?, color = ? WHERE id = ?';
        $stmt = $this->conn->prepare($sql);

        // Clean data
        $this->city = htmlspecialchars(strip_tags($this->city));
        $this->start_date = htmlspecialchars(strip_tags($this->start_date));
        $this->end_date = htmlspecialchars(strip_tags($this->end_date));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->color = htmlspecialchars(strip_tags($this->color));

        // Bind data
        $stmt->bind_param('sssssss', $this->city, $this->start_date, $this->end_date, $this->price, $this->status, $this->color, $this->id);

        // Execute query
        if ($stmt->execute())
        {
            return true;
        }

        // Print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);

        return false;
    }

    public function delete_data()
    {
        $sql = 'DELETE FROM cities_tbl  WHERE id = ?';
        $stmt = $this->conn->prepare($sql);

        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind data
        $stmt->bind_param('s', $this->id);

        // Execute query
        if ($stmt->execute())
        {
            return true;
        }

        // Print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);

        return false;
    }

    public function check_if_table_exists()
    {
        $sql = "SHOW TABLES LIKE 'cities_tbl';";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows == 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function create_table()
    {
        $sql = "CREATE TABLE if not exists cities_tbl (
        	id BIGINT(20) UNSIGNED NOT NULL,
        	city VARCHAR(200) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
        	start_date VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
        	end_date VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
        	price VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
        	status VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
        	color VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
        	PRIMARY KEY (id) USING BTREE
          )
          COLLATE='utf8mb4_unicode_ci'
          ENGINE=InnoDB
          ;";

        $stmt = $this->conn->prepare($sql);

        if ($stmt->execute())
        {
            return true;
        }

        // Print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);

        return false;
    }

}
