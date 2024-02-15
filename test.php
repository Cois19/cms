<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add to Select Options</title>
</head>
<body>

<form action="process_form.php" method="post">
  <label for="selectOption">Select or Add Option:</label>
  <select name="selectOption" id="selectOption">
    <option value="" selected disabled>Select an option or type a new one...</option>
    <!-- PHP code to fetch existing options from the database and populate the select element -->
    <?php
      // Assuming $options is an array containing existing options fetched from the database
      foreach ($options as $option) {
        echo "<option value=\"$option\">$option</option>";
      }
    ?>
  </select>
  <input type="text" name="newOption" id="newOption" placeholder="Type a new option...">
  <button type="submit">Submit</button>
</form>

</body>
</html>
