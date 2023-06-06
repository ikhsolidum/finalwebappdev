// to update the search bar each time a letter is typed/deleted
function updateTable() {
    var search = document.getElementById("search-input").value.toLowerCase();
    var table = document.getElementById("product-table");
    var rows = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr");

    for (var i = 0; i < rows.length; i++) {
      var brand = rows[i].getElementsByTagName("td")[1].innerText.toLowerCase();
      var name = rows[i].getElementsByTagName("td")[2].innerText.toLowerCase();
      var type = rows[i].getElementsByTagName("td")[3].innerText.toLowerCase();

      if (brand.includes(search) || name.includes(search) || type.includes(search)) {
        rows[i].style.display = "";
      } else {
        rows[i].style.display = "none";
      }
    }
  }

  // Ordering
function showOrderPopup(id, brand, name, type, gender, description, price, quantity) {
  if (quantity === 0) {
    alert("Sorry, this product is out of stock.");
    return;
  }

  document.getElementById("order-product-id").innerHTML = id;
  document.getElementById("order-product-brand").innerHTML = brand;
  document.getElementById("order-product-name").innerHTML = name;
  document.getElementById("order-product-type").innerHTML = type;
  document.getElementById("order-product-gender").innerHTML = gender;
  document.getElementById("order-product-description").innerHTML = description;
  document.getElementById("order-product-price").innerHTML = price;
  document.getElementById("order-product-quantity").value = 1;

  let quantityInput = document.getElementById("order-product-quantity");
  quantityInput.addEventListener("input", function () {
    let quantity = quantityInput.value;
    let newPrice = quantity * price;
    document.getElementById("order-product-price").innerHTML = newPrice;
  });

  document.getElementById("order-popup").style.display = "block";
}

function cancelOrder() {
  hideOrderPopup();
}

// Function to hide the orderpopup
function hideOrderPopup() {
  document.getElementById("order-popup").style.display = "none";
}

function createOrder() {
  let productId = document.getElementById("order-product-id").innerHTML;
  let quantity = document.getElementById("order-product-quantity").value;
  let firstName = document.getElementById("order-first-name").value;
  let lastName = document.getElementById("order-last-name").value;
  let contactNumber = document.getElementById("order-contact-number").value;
  let email = document.getElementById("order-email").value;

  // Validate the form fields
  if (
    productId.trim() === "" ||
    quantity.trim() === "" ||
    firstName.trim() === "" ||
    lastName.trim() === "" ||
    contactNumber.trim() === "" ||
    email.trim() === ""
  ) {
    alert("Please fill in all fields.");
    return;
  }

  // Deduct the ordered quantity from the total quantity in the table
  const table = document.getElementById("product-table");
  const rows = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr");

  for (let i = 0; i < rows.length; i++) {
    const rowProductId = rows[i].getElementsByTagName("td")[0].innerText;

    if (rowProductId === productId) {
      let rowQuantity = parseInt(rows[i].getElementsByTagName("td")[7].innerText);
      rowQuantity -= parseInt(quantity);
      rows[i].getElementsByTagName("td")[7].innerText = rowQuantity;
      break;
    }
  }

  // Send the order details to the server using AJAX
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "products/create_order.php", true); // Updated URL to the appropriate file
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
      // Reload the page to display the updated order details
      location.reload();
    }
  };

  // Construct the data to be sent
  let data =
    "productId=" +
    productId +
    "&quantity=" +
    quantity +
    "&firstName=" +
    encodeURIComponent(firstName) +
    "&lastName=" +
    encodeURIComponent(lastName) +
    "&contactNumber=" +
    encodeURIComponent(contactNumber) +
    "&email=" +
    encodeURIComponent(email);

  xhr.send(data);
}


  

  // Function to show the edit product popup
  function showEditProductPopup(id, brand, name, type, gender, description, price, quantity) {
// Populate the form fields with the existing product data
document.getElementById("edit-product-id").value = id;
  document.getElementById("edit-product-brand").value = brand;
  document.getElementById("edit-product-name").value = name;
  document.getElementById("edit-product-type").value = type;
  document.getElementById("edit-product-gender").value = gender;
  document.getElementById("edit-product-description").value = description;
  document.getElementById("edit-product-price").value = price;
  document.getElementById("edit-product-quantity").value = quantity;  // Set the quantity value

// Display the edit product popup
document.getElementById("edit-product-popup").style.display = "block";
}
function cancelEditProduct() {
  hideEditProductPopup();
}
// Function to hide the edit product popup
function hideEditProductPopup() {
  document.getElementById("edit-product-popup").style.display = "none";
}

function saveProduct() {
// Retrieve the edited product data from the form fields
var productId = document.getElementById("edit-product-id").value;
var brand = document.getElementById("edit-product-brand").value;
var name = document.getElementById("edit-product-name").value;
var type = document.getElementById("edit-product-type").value;
var gender = document.getElementById("edit-product-gender").value;
var description = document.getElementById("edit-product-description").value;
var price = document.getElementById("edit-product-price").value;
var quantity = document.getElementById("edit-product-quantity").value; // Retrieve the quantity value

// Send the updated product data to the server using AJAX
var xhr = new XMLHttpRequest();
xhr.open("POST", "products/pedit.php", true);  // Update the URL to the appropriate file
xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
xhr.onreadystatechange = function () {
  if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
    // Update the product row in the table with the updated information
    updateProductRow(productId, brand, name, type, gender, description, price, quantity);

    // Hide the edit product popup
    hideEditProductPopup();
  }
};
xhr.send(
  "productId=" + productId +
  "&brand=" + encodeURIComponent(brand) +
  "&name=" + encodeURIComponent(name) +
  "&type=" + encodeURIComponent(type) +
  "&gender=" + encodeURIComponent(gender) +
  "&description=" + encodeURIComponent(description) +
  "&price=" + price +
  "&quantity=" + quantity // Include the quantity parameter in the request
);
}


function updateProductRow(id, brand, name, type, gender, description, price, quantity) {
const table = document.getElementById("product-table");
const rows = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr");

for (let i = 0; i < rows.length; i++) {
  const productId = rows[i].getElementsByTagName("td")[0].innerText;

  if (productId === id) {
    rows[i].getElementsByTagName("td")[1].innerText = brand;
    rows[i].getElementsByTagName("td")[2].innerText = name;
    rows[i].getElementsByTagName("td")[3].innerText = type;
    rows[i].getElementsByTagName("td")[4].innerText = gender;
    rows[i].getElementsByTagName("td")[5].innerText = description;
    rows[i].getElementsByTagName("td")[6].innerText = price;
    rows[i].getElementsByTagName("td")[7].innerText = quantity; // Set the quantity value
    break;
  }
}
}

// ADD PRODUCT POP UP
// Event listener for the "Add" button
document.getElementById("add-product-button").addEventListener("click", showAddProductPopup);

// Function to show the add product popup
function showAddProductPopup() {
// Display the add product popup
document.getElementById("add-product-popup").style.display = "block";
}
// Function to hide the add product popup
function hideAddProductPopup() {
document.getElementById("add-product-popup").style.display = "none";
}

function addProduct() {
  // Retrieve the product data from the form fields
  var brand = document.getElementById("add-product-brand").value;
  var name = document.getElementById("add-product-name").value;
  var type = document.getElementById("add-product-type").value;
  var gender = document.getElementById("add-product-gender").value;
  var description = document.getElementById("add-product-description").value;
  var price = document.getElementById("add-product-price").value;
  var quantity = document.getElementById("add-product-quantity").value; // Retrieve the quantity value

  // Validate the form fields
  if (
    brand.trim() === "" ||
    name.trim() === "" ||
    type.trim() === "" ||
    gender.trim() === "" ||
    description.trim() === "" ||
    price.trim() === "" ||
    quantity.trim() === ""
  ) {
    alert("Please fill in all fields.");
    return;
  }

  // Send the product data to the server using AJAX
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "products/padd.php", true); // Update the URL to the appropriate file
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
      // Reload the page to display the newly added product
      location.reload();
    }
  };
  xhr.send(
    "brand=" +
      encodeURIComponent(brand) +
      "&name=" +
      encodeURIComponent(name) +
      "&type=" +
      encodeURIComponent(type) +
      "&gender=" +
      encodeURIComponent(gender) +
      "&description=" +
      encodeURIComponent(description) +
      "&price=" +
      price +
      "&quantity=" +
      quantity // Include the quantity parameter in the request
  );
}


function cancelAddProduct() {
// Clear the form fields
document.getElementById("add-product-brand").value = "";
document.getElementById("add-product-name").value = "";
document.getElementById("add-product-type").value = "";
document.getElementById("add-product-gender").value = "";
document.getElementById("add-product-description").value = "";
document.getElementById("add-product-price").value = "";

hideAddProductPopup();
}

function deleteProduct(id) {
if (confirm("Are you sure you want to delete this product?")) {
  // Send the product ID to the server using AJAX
  var xhr = new XMLHttpRequest();
  xhr.open("GET", "products/pdelete.php?id=" + id, true);  // Update the URL to the appropriate file
  xhr.onreadystatechange = function() {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        // Reload the page to reflect the changes
        location.reload();
      } else {
        // Display an error message if the deletion fails
        alert("Failed to delete the product. Please try again.");
      }
    }
  };
  xhr.send();
}
}
