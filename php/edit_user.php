<?php
session_start();
require 'config.php';

check_role('Admin'); // Nur Admins dürfen Benutzer bearbeiten

$user_id = $_GET['id'];
$user = $conn->query("SELECT name, email, role FROM users WHERE id = $user_id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
    $stmt->bind_param("sssi", $name, $email, $role, $user_id);

    if ($stmt->execute()) {
        echo "User updated successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<form method="post" action="edit_user.php?id=<?php echo $user_id; ?>">
    Name: <input type="text" name="name" value="<?php echo $user['name']; ?>" required><br>
    Email: <input type="email" name="email" value="<?php echo $user['email']; ?>" required><br>
    Role:
    <select name="role" required>
        <option value="Admin" <?php if ($user['role'] == 'Admin') echo 'selected'; ?>>Admin</option>
        <option value="Project Manager" <?php if ($user['role'] == 'Project Manager') echo 'selected'; ?>>Project Manager</option>
        <option value="Employee" <?php if ($user['role'] == 'Employee') echo 'selected'; ?>>Employee</option>
    </select><br>
    <button type="submit">Update User</button>
</form>
