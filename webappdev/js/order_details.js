function updateTable() {
    var search = document.getElementById("search-input").value.toLowerCase();
    var table = document.getElementById("order-table");
    var rows = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr");

    for (var i = 0; i < rows.length; i++) {
      var brand = rows[i].getElementsByTagName("td")[1].innerText.toLowerCase();
      var name = rows[i].getElementsByTagName("td")[2].innerText.toLowerCase();
      var type = rows[i].getElementsByTagName("td")[3].innerText.toLowerCase();

      if (brand.includes(search) || name.includes(search) || type.includes(search)) {          rows[i].style.display = "";
      } else {
        rows[i].style.display = "none";
      }
    }
  }
  function deleteAllOrders() {
    if (confirm('Are you sure you want to delete all orders?')) {
      window.location.href = 'products/delete_all_orders.php';
    }
  }
  
  function confirmOrder(orderId) {
    if (confirm('Confirm Order?')) {
      // Redirect to the confirm_order.php file with the order ID
      window.location.href = 'products/confirm_order.php?id=' + orderId;
    }
  }
  
  function confirmAllOrders() {
    if (confirm('Confirm all orders?')) {
      // Redirect to the confirm_all_orders.php file
      window.location.href = 'products/confirm_all_orders.php';
    }
  }
  