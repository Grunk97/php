<html>
    <head></head>
    <body>
        <form action="bilCRUD.php" method="post">
            <?php
                //connect til database
                $conn = new mysqli("localhost:3306", "root", "root", "minebilerdb");

            ?>

            <?php
                if($_SERVER['REQUEST_METHOD'] === 'POST')
                {
                    //CRUD
                    //read
                    //Er knappen trykket?
                    if($_REQUEST['knap'] == "read")
                    {
                        //henter bilid
                        $bilid = $_REQUEST['bilid'];
                        //hvis man har trykket et tal ind gør så det næste
                        if(is_numeric($bilid))
                        {
                            //sikre os mod sql injection
                            $sql = $conn->prepare("select * from bil where id = ?");
                            $sql->bind_param("i", $bilid);
                            $sql->execute();
                            $result = $sql->get_result();
                            $row = $result->fetch_assoc();
                            $bilid = $row["id"];
                            $model = $row["model"];
                            $farve = $row["farve"];
                            $aar = $row["aar"];
                        }
                    }

                    //create
                    //Er knappen trykket på
                    if($_REQUEST['knap'] == "create")
                    {
                        //Henter de 4 fælter
                        $bilid = $_REQUEST['bilid'];
                        $model = $_REQUEST['model'];
                        $farve = $_REQUEST['farve'];
                        $aar = $_REQUEST['aar'];
                        if($model == "") $model = "ukendt";
                        if($farve == "") $farve = "ukendt";
                        if($aar == "") $aar = -1;
                        if(is_numeric($bilid))
                        {
                            $sql = $conn->prepare("insert into bil (id, model, farve, aar) values (?, ?, ?, ?)");
                            $sql->bind_param("issi", $bilid, $model, $farve, $aar);
                            $sql->execute();
                        }
                    }

                    //delete
                    if($_REQUEST['knap'] == "delete")
                    {
                        $bilid = $_REQUEST['bilid'];
                        if(is_numeric($bilid))
                        {
                            $sql = $conn->prepare("delete from bil where id = ?");
                            $sql->bind_param("i", $bilid);
                            $sql->execute();
                        }
                    }

                    //update
                    if($_REQUEST['knap'] == "update")
                    {
                        $bilid = $_REQUEST['bilid'];
                        $model = $_REQUEST['model'];
                        $farve = $_REQUEST['farve'];
                        $aar = $_REQUEST['aar'];
                        if($model == "") $model = "ukendt";
                        if($farve == "") $farve = "ukendt";
                        if($aar == "") $aar = -1;
                        if(is_numeric($bilid))
                        {
                            $sql = $conn->prepare("update bil set model = ?, farve = ?, aar = ? where id = ?");
                            $sql->bind_param("ssii", $model, $farve, $aar, $bilid);
                            $sql->execute();
                        }
                    }


                    //clear
                    if($_REQUEST['knap'] == "clear")
                    {
                        $bilid = "";
                        $model = "";
                        $farve = "";
                        $aar = "";
                    }
                }
            ?>

            <?php
                $sql = "select * from bil";
                $result = $conn->query($sql);

                echo '<table border="5" cellpadding="5">';
                echo "<tr>";
                echo "<th>BilID</th>";
                echo "<th>Model</th>";
                echo "<th>Farve</th>";
                echo "<th>Aar</th>";
                echo "</tr>";

                //Er der nogen rækker hvis ja skal vi have fat i dem
                if($result->num_rows > 0)
                {
                    while($row = $result->fetch_assoc())
                    {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['model'] . "</td>";
                        echo "<td>" . $row['farve'] . "</td>";
                        echo "<td>" . $row['aar'] . "</td>";
                        echo "</tr>";
                    }
                }
                else
                {
                    echo "No cars";
                }

                echo "</table>";

            ?>

            <?php
                $conn->close();
            ?>

            <p>
                BilID : <input type="text" name="bilid" value="<?php echo isset($bilid) ? $bilid :'' ?>" style="position: absolute; left: 100px; width: 100px; height: 22px"><br/><br/>
                Model : <input type="text" name="model" value="<?php echo isset($model) ? $model :'' ?>" style="position: absolute; left: 100px; width: 100px; height: 22px"><br/><br/>
                Farve : <input type="text" name="farve" value="<?php echo isset($farve) ? $farve :'' ?>" style="position: absolute; left: 100px; width: 100px; height: 22px"><br/><br/>
                Aar : <input type="text" name="aar" value="<?php echo isset($aar) ? $aar :'' ?>" style="position: absolute; left: 100px; width: 100px; height: 22px"><br/><br/>
            </p>

            <p>
                <input type="submit" name="knap" value="read" style="width: 80px">
                <input type="submit" name="knap" value="update" style="width: 80px">
                <input type="submit" name="knap" value="create" style="width: 80px">
                <input type="submit" name="knap" value="delete" style="width: 80px">
                <input type="submit" name="knap" value="clear" style="width: 80px">
            </p>
        </form>    
    </body>
</html>