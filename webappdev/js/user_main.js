// Function to show the add user popup
function showAddUserPopup() {
    var popup = document.getElementById("add-user-popup");
    popup.style.display = "block";
  }
  
  // Function to hide the add user popup
  function hideAddUserPopup() {
    var popup = document.getElementById("add-user-popup");
    popup.style.display = "none";
  }
  
  function addUser() {
    // Retrieve the user data from the form fields
    var lastName = document.getElementById("add-user-lastname").value;
    var firstName = document.getElementById("add-user-firstname").value;
    var email = document.getElementById("add-user-email").value;
    var password = document.getElementById("add-user-password").value;
    var status = document.getElementById("add-user-status").value;

    // Send the user data to the server using AJAX
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "main.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4 && xhr.status === 200) {
        if (xhr.responseText === "success") {
          // Update the table or show a success message
          // For example, you can update the table by appending a new row using JavaScript
          var table = document.getElementById("data-list");
          var newRow = table.insertRow(table.rows.length); // Insert at the end of the table
          var cellIndex = newRow.insertCell(0);
          var cellName = newRow.insertCell(1);
          var cellEmail = newRow.insertCell(2);
          var cellStatus = newRow.insertCell(3);
          cellIndex.innerText = table.rows.length; // Update the index
          cellName.innerText = lastName + ", " + firstName;
          cellEmail.innerText = email;
          cellStatus.innerText = status;

          // Clear the form fields
          cancelAddUserPopup();
        } else {
          // Show an error message
          console.error("Failed to add user.");
        }
      }
    };
    xhr.send(
      "user_lastname=" +
      encodeURIComponent(lastName) +
      "&user_firstname=" +
      encodeURIComponent(firstName) +
      "&user_email=" +
      encodeURIComponent(email) +
      "&user_password=" +
      encodeURIComponent(password) +
      "&user_status=" +
      encodeURIComponent(status)
    );
  }
  
  // Event listener for the "Add" button
  var addUserButton = document.getElementById("add-user-button");
  addUserButton.addEventListener("click", showAddUserPopup);
  
  // Event listener for the "Cancel" button
  var cancelAddUserButton = document.getElementById("cancel-add-user-button");
  cancelAddUserButton.addEventListener("click", hideAddUserPopup);
  
  // Function to cancel adding a user and clear the form fields
  function cancelAddUserPopup() {
    hideAddUserPopup();
    document.getElementById("add-user-lastname").value = "";
    document.getElementById("add-user-firstname").value = "";
    document.getElementById("add-user-email").value = "";
    document.getElementById("add-user-password").value = "";
    document.getElementById("add-user-status").value = "Staff";
  }
  
  // Function to handle the search functionality and filter the table
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
  