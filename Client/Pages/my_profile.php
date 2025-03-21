<?php
include_once __DIR__ . "/../php/functions.php";

if (empty($_GET)) {
  header("Location: ?profile=true");
  exit();
}

if(isset($_GET['profile'])){
  include __DIR__ . "/../php/my_profile/read_profile.php";
} else if(isset($_GET['address'])) {
  include __DIR__ . "/../php/my_profile/read_address.php";
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
  <link rel="stylesheet" href="../Styles/my_profile.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>

  <div class="navbar">
    <a href="index.html"><i class="fa-solid fa-house"></i>Home</a>
  </div>
  <section class="container">
    <div class="sidebar">
      <div class="sidebar-header">
        <div class="logo"></div>
        <div class="writing">
          <p><?= ucfirst($fname)?></p>
          <div class="clock" id="clock"></div>
        </div>
      </div>
      <div class="sidebar-list">
        <ul>
          <li><a href="my_profile.php?profile=true" id="profile-link"
              class="<?= (isset($_GET['profile'])) ? 'active': ''?>"><i class="fa-solid fa-user"></i>My
              Profile</a></li>
          <li><a href="my_profile.php?address=true" id="address-link"
              class="<?= (isset($_GET['address'])) ? 'active': ''?>"><i class="fa-solid fa-location-dot"></i>Delivery
              Address</a></li>
          <li><a href="my_profile.php?myOrders=true" id="orders-link"
              class="<?= (isset($_GET['myOrders'])) ? 'active': ''?>"><i class="fa-solid fa-basket-shopping"></i>My
              Orders</a></li>
        </ul>
      </div>
    </div>
    <div class="content-area">
      <p>Good Morning <?= ucfirst($fname) ?></p>
      <div class="display-box" id="display-box">
        <?php if(isset($_GET['profile'])): ?>

        <div class="profile-form-container">
          <div class="profile-form-header">
            <h2><i class="fa-solid fa-user"></i>My Profile</h2>
            <i onclick="getEnabled()" class="fa-solid fa-pen-to-square" id="edit-icon"></i>
          </div>
          <form id="profile-form" action="../php/my_profile/update_profile.php" method="POST">
            <!-- Row 1: First Name / Last Name -->
            <div class="profile-form-row">
              <div class="form-group">
                <label for="firstName">First Name</label>
                <input type="text" id="firstName" name="fname" placeholder="first name" value="<?= $fname ?>"
                  disabled />
              </div>
              <div class="form-group">
                <label for="lastName">Last Name</label>
                <input type="text" id="lastName" name="lname" placeholder="last name" value="<?= $lname ?>" disabled />
              </div>
            </div>

            <!-- Row 2: Email / Contact Number -->
            <div class="profile-form-row">
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Email" value="<?= $email ?>" disabled />
              </div>
              <div class="form-group">
                <label for="contactNumber">Contact Number</label>
                <input type="text" id="contactNumber" name="contact" placeholder="Contact Number"
                  value="<?= $contact ?>" disabled />
              </div>
            </div>

            <!-- Row 3: Birthdate / Gender -->
            <div class="profile-form-row">
              <div class="form-group birthdate-group">
                <label>Birthdate</label>
                <div class="birthdate-fields">
                  <input type="text" placeholder="DD" name="dob_day" value="<?= $dob_day ?>" disabled />
                  <input type="text" placeholder="MM" name="dob_month" value="<?= $dob_month ?>" disabled />
                  <input type="text" placeholder="YYYY" name="dob_year" value="<?= $dob_year ?>" disabled />
                </div>
              </div>
              <div class="form-group gender-group">
                <label>Gender</label>
                <div class="gender-options">
                  <label><input type="radio" name="gender" value="M" <?=($gender=='M' )?'checked': '' ?> disabled />
                    Male</label>
                  <label><input type="radio" name="gender" value="F" <?=($gender=='F' )?'checked': '' ?> disabled />
                    Female</label>
                  <label><input type="radio" name="gender" value="O" <?=($gender=='O' )?'checked': '' ?> disabled />
                    Other</label>
                </div>
              </div>
            </div>
            <div class="form-group">
              <!-- have to change id value -->
              <input type="hidden" name="id" value="1">
              <button id="saveBtn" class="button" type="submit" name="save" disabled>Save</button>
            </div>
          </form>
        </div>

        <?php elseif(isset($_GET['address'])) : ?>
        <div class="address-form-container">
          <div class="address-form-header">
            <h2><i class="fa-solid fa-location-dot"></i>Delivery Address</h2>
            <button onclick="toggleAddMode(<?= no_of_addresses() ?>)" id="add-address">
              <i class="fa-solid fa-plus"></i>
            </button>
          </div>

          <div class="address-form-row">
            <div class="form-group">
              <!-- insert address -->
              <form id="address-insert-form" action="../php/my_profile/insert_address.php" method="POST" style="display: none;">
                <input type="hidden" name="id" value="1">
                <input id="insert-address-input" type="text" name="address" placeholder="Enter new Address" >
                <button  id="insert-address-icon" type="submit" style="display: none;">
                    <i class="fa-solid fa-check fa-lg"></i>
                </button>
              </form>
              <?php while($row = $result->fetch_assoc()) : ?>
              <form class="address-form" name="address-form"  method="POST">
                <div class="address-input">
                  <input type="hidden" name="id" value="1">
                  <input type="hidden" name="old_address" value="<?=$row['address'] ?>">
                  <input id="address-input-<?=$row['address']?>" type="text" name="address"
                    value="<?=$row['address'] ?>" disabled />
                  <button onclick="toggleEditMode('<?=$row['address']?>')" id="edit-icon-<?=$row['address']?>"
                    class="edit-address" type="button">
                    <i class="fa-solid fa-pen-to-square fa-lg"></i>
                  </button>
                  <button onclick="confirmDeletion(event)" class="delete-address" type="submit" formaction="../php/my_profile/delete_address.php">
                    <i class="fa-solid fa-xmark fa-lg"></i>
                  </button>
                  <button id="save-address-<?=$row['address']?>" class="save-address" type="submit" style="display: none;" formaction="../php/my_profile/update_address.php">
                    <i class="fa-solid fa-check fa-lg"></i>
                  </button>
                </div>
              </form>
              <?php endwhile; ?>
            </div>
          </div>

        </div>

        <?php elseif(isset($_GET['myOrders'])) : ?>
        <div class="profile-form-container">
          <div class="profile-form-header">
            <h2><i class="fa-solid fa-basket-shopping"></i>My Orders</h2>
          </div>

          <div class="profile-form-row">
            <ul class="orders-list">
              <li>
                <div class="order-item">
                  <span class="order-id">Order #1</span>
                  <span class="order-status status-delivered">Status: Delivered</span>
                </div>
              </li>
              <li>
                <div class="order-item">
                  <span class="order-id">Order #2</span>
                  <span class="order-status status-processing">Status: Processing</span>
                </div>
              </li>
              <li>
                <div class="order-item">
                  <span class="order-id">Order #3</span>
                  <span class="order-status status-shipped">Status: Shipped</span>
                </div>
              </li>
            </ul>
          </div>
        </div>

        <?php endif; ?>


      </div>
    </div>
    <script src="../js/my_profile.js"></script>
  </section>
</body>

</html>