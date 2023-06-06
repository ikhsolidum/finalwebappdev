<link rel="stylesheet" href="css/user_main.css">
<h1> Users </h1>

<!-- Search -->
<div class="search-container">
<form method="post">
    <input type="text" id="search-input" name="search" placeholder="Search..." oninput="updateTable()">
    <button class="add-user-button" type="button" onclick="window.location.href='user/register.php'">Add User</button>

</form>
  </div>

<!-- Updated table structure -->
<div id="subcontent">
  <table id="data-list">
    <thead>
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Email</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody id="product-table">
      <?php
      $count = 1;
      $userList = $user->list_users(); // Retrieve the user list only once

      if ($userList !== false) {
        foreach ($userList as $value) {
          extract($value);
          $user_email = $user->get_user_email($user_id); // Get the updated email value
          ?>
          <tr>
          <td><?php echo $count; ?></td>
          <td><a href="user/edit-user.php?id=<?php echo $user_id; ?>"><?php echo $user_lastname . ', ' . $user_firstname; ?></a></td>
          <td><a href="user/edit-user.php?id=<?php echo $user_id; ?>"><?php echo $user_email; ?></a></td> 
          <td><a href="user/edit-user.php?id=<?php echo $user_id; ?>"><?php echo $user_status; ?></a></td>
          </tr>
          <?php
          $count++;
        }
      } else {
        echo "<tr><td colspan='4'>No Record Found.</td></tr>";
      }
      ?>
    </tbody>
  </table>
</div>

<script>
  function updateTable() {
    var search = document.getElementById("search-input").value.toLowerCase();
    var table = document.getElementById("data-list");
    var rows = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr");

    for (var i = 0; i < rows.length; i++) {
      var name = rows[i].getElementsByTagName("td")[1].innerText.toLowerCase();
      var email = rows[i].getElementsByTagName("td")[2].innerText.toLowerCase();
      var status = rows[i].getElementsByTagName("td")[3].innerText.toLowerCase();

      if (name.includes(search) || email.includes(search) || status.includes(search)) {
        rows[i].style.display = "";
      } else {
        rows[i].style.display = "none";
      }
    }
  }
</script>
