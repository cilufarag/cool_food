<?php require("includes/configuration.inc.php"); ?>

<!-- PHP CODE EXECUTED ONCE THE PAGE IS RELOADED -->

<!-- Handling PHP validation, sanitization and insertion inside form page -->

<?php require_once("includes/db-config.local.inc.php"); ?>

<?php

  if($_SERVER["REQUEST_METHOD"] == "POST") {

    require_once("includes/utils/utils.inc.php"); //Includes functions utils
    $errors = []; //Array for errors
    
    //SANITIZATION

    $name = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $meal = filter_var($_POST["meal"], FILTER_SANITIZE_EMAIL);
    $payment = filter_var($_POST["paymentOption"], FILTER_SANITIZE_EMAIL);
    $additionalInfo = filter_var($_POST["additionalInfo"], FILTER_SANITIZE_STRING);
    
    //VALIDATION

    //Validate Not Empty and Field Size

    if (trim($name) == '' || strlen($name) > 30) {
       array_push($errors, "ERR: Name must contain a value less than 30 chars");
    }
    
    if (trim($email) == '' || strlen($email) > 30) {
      array_push($errors, "ERR: Email must contain a value less than 30 chars");
    }

    if (trim($meal) == '' || strlen($meal) > 30) {
      array_push($errors, "ERR: Meal must contain a value less than 30 chars");
    }

    if (trim($payment) == '' || strlen($payment) > 30) {
      array_push($errors, "ERR: Payment option must contain a value less than 30 chars");
    }

    if (trim($additionalInfo) == '' || strlen($additionalInfo) > 30) {
      array_push($errors, "ERR: Additional information must contain a value less than 30 chars");
    }

    //Validate for valid inputs

    checkIfValid($email, FILTER_VALIDATE_EMAIL, "ERR: Invalid Email", $errors);
    
    //Check if Errors Array has errors and returns a message with errors
    if(!empty($errors)) {
      $message = "ERR: Server rejected data";

      foreach($errors as $error) {
        $message .= "<br>$error";
      }

      exit($message); //Stop PHP code and return error message
    }

    //When no errors found insert field forms to database
    $mysqli = retrieveConnectionToDB(); //Calls function in includes/db-config.inc.php

    //Prepare insert query
    $sql = "INSERT INTO orders (name, email, food, payment, info) VALUES (?, ?, ?, ?, ?)";
    
    $name = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $meal = filter_var($_POST["meal"], FILTER_SANITIZE_EMAIL);
    $payment = filter_var($_POST["paymentOption"], FILTER_SANITIZE_EMAIL);
    $additionalInfo = filter_var($_POST["additionalInfo"], FILTER_SANITIZE_STRING);



    //Send insert query to database
    $stmt = $mysqli->prepare($sql);

    //Bind form fields value types with database types in first parameter "ssssss" s for string 
    $stmt->bind_param("sssss", $name, $email, $meal, $payment, $additionalInfo);

    //Handle error after executing query
    if(!$stmt->execute()) { //When error is retrieved from database while inserting fields popup a modal message
      $modal = "
        <div class='modal fade' id='successModal' tabindex='-1' role='dialog' aria-labelledby='exampleModalCenterTitle' aria-hidden='true'>
          <div class='modal-dialog modal-dialog-centered' role='document'>
            <div class='modal-content'>
              <div class='modal-header'>
                <h5 class='modal-title' id='exampleModalLongTitle'>Order save failed!</h5>
                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                  <span aria-hidden='true'>&times;</span>
                </button>
              </div>
              <div class='modal-body'>
                Execute failed: {$stmt->errno}
              </div>
              <div class='modal-footer'>
                <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
              </div>
            </div>
          </div>
        </div>
      ";

    } else { //When insertion to database is successful popup a modal message

      $modal = "
        <div class='modal fade' id='successModal' tabindex='-1' role='dialog' aria-labelledby='exampleModalCenterTitle' aria-hidden='true'>
          <div class='modal-dialog modal-dialog-centered' role='document'>
            <div class='modal-content'>
              <div class='modal-header'>
                <h5 class='modal-title' id='exampleModalLongTitle'>Order saved!</h5>
                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                  <span aria-hidden='true'>&times;</span>
                </button>
              </div>
              <div class='modal-body'>
                Thank you for your Order!
              </div>
              <div class='modal-footer'>
                <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
              </div>
            </div>
          </div>
        </div>
      ";
    }

    //Close connection to database
    $mysqli->close();
  }

?>

<!-- END OF PHP CODE EXECUTED ONCE THE PAGE IS RELOADED -->

<!-- HTML PAGE FIRST LOAD -->

<!doctype html>
<html lang="en">

<head>

  <!-- Page title -->  
  <title>Cool Food</title>

  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet"
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
        crossorigin="anonymous" />

  <!-- Custom CSS -->
  <link rel="stylesheet" href="css/style.css" />

</head>

<body>
            
        <!-- Hero -->

    <div class="jumbotron jumbotron-fluid">
        <div class="container text-center text-white">
          <h1 class="display-4">Cool Food</h1>
          <p class="lead">Amazing food all in one place</p>
          <p class="lead">Enter the world of amazing tastes and experience</p>
          <button type="button" class="btn btn-danger align-center">Let's start</button>
        </div>
    </div>

    <!-- End of Hero -->
    
    <!-- Featured cuisines -->

    <div class="custom-container text-center justify-content-center mb-5">
        <h1 class="display-4 text-dark">Featured cuisines</h1>
        <div id="food-carousel" class="carousel slide" data-ride="carousel">
            
            <ol id="carousel-indicators-container" class="carousel-indicators">
            <!-- Place indicators here using JS -->
                <li data-target="#food-carousel" data-slide-to="0" class="active"></li>
                <li data-target="#food-carousel" data-slide-to="1"></li>
                <li data-target="#food-carousel" data-slide-to="2"></li>
            </ol>

            <!-- Featured cuisines -->
            <div id="carousel-items-container" class="carousel-inner">
                
            <!-- ITEMS BELOW GENERATED DYNAMICALLY USING JAVASCRIPT WITH JSON DATA -->

            </div>    

            <!-- End of Carousel Container -->

            <a class="carousel-control-prev" href="#food-carousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#food-carousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon"></span>
                <span class="sr-only">Next</span>
            </a>

        </div>

        <!-- End of food carousel -->

    </div>

    <!-- End of Featured cuisines -->

    <!-- About food -->

    <div class="custom-container mb-5">

        <h1 class="Display-4 text-center mb-5">About Food</h1>
        <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Reiciendis quidem inventore ipsum iste, quasi itaque
          ipsam
          laboriosam. Qui veniam unde maxime libero neque quis nihil, autem sequi, consectetur ipsum ducimus?
          Voluptatibus
          expedita iste, itaque sunt fugiat numquam excepturi magnam incidunt tenetur ad soluta similique assumenda
          totam
          cumque explicabo autem at quod fuga aliquid fugit libero! Blanditiis adipisci repudiandae laudantium nihil!
          Laudantium officia doloribus nostrum, laborum sed minima.
          Totam quod quo fugit omnis libero odit modi tenetur qui doloremque earum, dignissimos voluptas impedit veniam
          expedita quae tempore sequi. Illo, eligendi accusamus. Nobis pariatur minus, fuga assumenda voluptate aliquid
          repellendus molestias harum. Iusto voluptatibus dolore explicabo est voluptas minima, architecto molestias
          quaerat
          impedit, quisquam minus porro dolorum ratione eos maiores nisi optio. Deserunt hic voluptate, atque similique
          odio
          sequi dignissimos vitae laborum dolores quis
          quod asperiores magni ab non at incidunt, quae harum officia! Maiores velit voluptate distinctio ipsam
          obcaecati
          quasi repellat. Ad dolore neque laboriosam, iusto rerum exercitationem, voluptatibus non quam dolorem, nemo
          asperiores voluptas nesciunt aperiam blanditiis officia expedita quae nobis modi. Dolorum tempora veniam eaque
          soluta neque ut odio! Atque voluptas adipisci eligendi, optio porro illum nemo harum earum doloremque nam
          inventore
          incidunt maiores sapiente laborum laboriosam
          ipsam deserunt accusantium reiciendis odit esse placeat ullam nisi at? Repudiandae, quam! Eos cupiditate
          doloremque
          reiciendis deleniti quae ad! Molestiae cum molestias optio non, dolor consectetur tempore alias placeat
          consequatur
          voluptate veritatis officiis eligendi aspernatur hic repellat mollitia expedita similique nam laboriosam.</p>
    </div>

    <!-- End About food -->

    <!-- Icons section-->

    <div class="custom-container mb-5">
          <div class="row align-items-center text-center">
                <div class="col-lg-4">
                    <div class="icon-container">
                        <img class="icon" src="img/home/hamburger.svg" alt="Hamburger">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="icon-container">
                        <img src="img/home/chicken.svg" alt="Chicken">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="icon-container">
                        <img src="img/home/pizza.svg" alt="Pizza">
                    </div>
                </div>
          </div>
    </div>

    <!-- End of Icons section-->

    <!-- Featured Meals -->
    
    <div class="custom-container mb-5">

      <h1 class="Display-4 mb-5">Featured meals</h1>

      <div class="row mb-5";>

          <!-- CARD 1 -->

          <div class="col-lg-4 col-md-6">
            <div class="card">
                <img src="img/home/featured-burger.jpg" class="card-img-top" alt="burger image">
                <div class="card-body">
                <p class="card-text">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Reiciendis quidem inventore ipsum iste, quasi itaque ipsam laboriosam.</p>
                </div>
            </div>
          </div>


          <!-- CARD 2 -->

          <div class="col-lg-4 col-md-6">
            <div class="card">
                <img src="img/home/featured-pizza.jpg" class="card-img-top" alt="Pizza image">
                <div class="card-body">
                <p class="card-text">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Reiciendis quidem inventore ipsum iste, quasi itaque ipsam laboriosam.</p>
                </div>
            </div>
          </div>

          <!-- CARD 3 -->

          <div class="col-lg-4 col-md-6">
            <div class="card">
                <img src="img/home/featured-steak.jpg" class="card-img-top" alt="Steak image">
                <div class="card-body">
                <p class="card-text">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Reiciendis quidem inventore ipsum iste, quasi itaque ipsam laboriosam.</p>
                </div>
            </div>
          </div>

          <!-- END OF CARDS -->

      </div>

      <!-- END OF ROW -->

      <!-- Quote -->

      <blockquote class="blockquote text-center">
        <p>&quot;When the waitress asked if I wanted my pizza cut into four or eight slices, I said, 'Four. I don’t think I can eat eight.&quot;</p>
        <footer class="blockquote-footer">Yogi Berra in Somewhere</footer>
      </blockquote>
      
      <!-- End of Quote -->

    </div>
    
    <!-- End of Featured meals -->

    <!-- Order Form and Orders Display -->

    <div class="container-fluid bg-light pt-5">

        <div class="container">

          <div class="row">

              <!-- FORM CONTAINER -->

              <div class="col-lg-6 col-md-12">

                  <!-- Form Title -->
                  <h1 class="Display-4 text-center">Order your meal</h1>

                  <!-- FORM -->

                  <form class="p-2 mt-3 mb-5 w-100 mx-auto" 
                        style="Transform: none; cursor: default;" 
                        name="OrderForm" 
                        action="" 
                        onsubmit="" 
                        method="post">
                  
                        <!-- Name Field Row -->

                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label>Name</label>
                                <input class="form-control" type="text" name="name" id="name" placeholder="Enter your name" required >
                            </div>

                        </div>
                      
                        <!-- E-Mail Field Row -->

                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="email">Email</label>
                                <input class="form-control" type="email" name="email" id="email" placeholder="Enter your email" required >
                                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                            </div>
                        </div>

                        <!-- Food Field Row -->

                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="meal">Food</label>
                                <select class="form-control custom-select" name="meal" id="meal" >
                                      <option value="Italian" selected>Italian</option>
                                      <option value="Mexican" >Mexican</option>
                                      <option value="Japanese" >Japanese</option>
                                </select>
                            </div>
                        </div>

                        <!-- Cash of Card Field Row -->

                        <div class="form-group">
                           
                            <div class="form-check form-check-inline pr-2">
                              <input class="form-check-input" type="radio" name="paymentOption" id="cash" value="cash" checked>
                              <label class="form-check-label pl-1" for="cash">
                                Cash
                              </label>
                            </div>
            
                            <div class="form-check form-check-inline pr-2">
                              <input class="form-check-input" type="radio" name="paymentOption" id="card" value="card" >
                              <label class="form-check-label pl-1" for="card">
                                Card
                              </label>
                            </div>
            
                        </div>

                        <!-- Aditional Information Field Row -->
                    
                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="additionalInfo">Additional Information</label>
                                <textarea class="form-control" type="text" name="additionalInfo" id="additionalInfo" placeholder="Enter additional information" rows="3"></textarea>
                            </div>
                        </div>
                    
                        <!-- Submit Button -->
                        
                        <button class="btn btn-danger" type="submit" style="font-size: 1rem;">
                            Order
                        </button>

                    </form>

                    <!-- END OF FORM -->

                </div>

                <!--  END OF FORM CONTAINER -->

                <!-- ORDERS CONTAINER -->

                <div class="db-result col-lg-6 col-md-12">

                    <div class="card d-flex">
                        <ul class="list-group list-group-flush">

                            <!-- <li class="list-group-item">John Doe - <strong>Italian</strong></li> -->
                            <!-- <li class="list-group-item">Jane Doe - <strong>Japanese</strong></li> -->
                            <!-- <li class="list-group-item">James Doe - <strong>Mexican</strong></li> -->

                        <?php 
                            //Calls function in includes/db-config.inc.php
                            $mysqli = retrieveConnectionToDB(); 
                            
                            //Prepare SQL query
                            $sql = "SELECT name, food FROM orders";
                            
                            $result = $mysqli->query($sql); //Assign SQL query result to variable
                            $numOfRows = $result->num_rows; //Assign to variable $numberOfRows the rows retrieved from database

                            //Check query result
                            if($numOfRows > 0) { //When query to database has values matching criteria
                                
                                $htmlList = ""; //Declare string variable to add results from data

                                while($row = $result->fetch_assoc()) {
                                    $htmlList .= "<li class='list-group-item'>{$row['name']} - <strong>{$row['food']}</strong></li>";
                                }

                                echo $htmlList;

                            } else { //When query to database retrieves no values

                                echo "There are no ordets yet!";
                            }

                            //Close database connection
                        
                            $mysqli->close();
                      
                        ?>

                        </ul>
                    </div>
                    
                </div>

                <!--  END OF ORDERS CONTAINER -->

            </div>

            <!-- END OF ROW -->

        </div>

    </div>

    <!-- END OF FORM AND ORDERS CONTAINER -->            

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
            integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
            integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
            integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
            crossorigin="anonymous"></script>

     <!-- PHP SCRIPT WHEN POST -->
     <?php 
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            echo $modal;
            echo "<script>$('#successModal').modal('show');</script>"; 
        }
    ?>
    
    <!-- Custom JavaScript -->
    <script src="js/script.js"></script>

</body>
</html>