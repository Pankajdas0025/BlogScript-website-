<?php
require '../src/config.php';
require '../src/db.php';
require './ip_not_allow.php';
require './send_post_email.php';

?>

<!-- Load all active users using ajax =============================== -->
<?php
$query = "SELECT * FROM posts WHERE status='pending'";
$result = mysqli_query($conn,$query);
$data = "";
$data .= '<table class="table" border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Date</th>
                    <th>User</th>
                    <th>Status</th>
                    <th>Action</th>
                    <th>Send Email</th>
                </tr>
            </thead>
            <tbody>
' ;
if($result && mysqli_num_rows($result)>0){

  while($row=mysqli_fetch_assoc($result)){
    $data .="
    <tr>
      <td><a href='{$local}/view?id={$row['id']}'>{$row['id']}</a></td>
      <td>".htmlspecialchars($row['title'])."</td>
      <td>".htmlspecialchars(substr($row['content'],0,50))."...</td>
      <td>{$row['created_at']}</td>
      <td>{$row['user_id']}</td>
      <td><span class='status pending'>pending</span></td>
      <td>
        <form method='POST'>
          <input type='hidden' name='post_id' value='{$row['id']}'>
          <select name='status'>
            <option value='1' selected>Publish</option>
            <option value='0'>Pending</option>
            <option value='2'>Delete</option>
          </select>
          <button name='update_post_status'>Update</button>
        </form>
      </td>
      <td>
        <form method='POST' action='send_post_email.php'>
          <input type='hidden' name='post_id' value='{$row['id']}'>
          <button name='send_email' type='submit' style='background-color: #007bff; color: white; padding: 5px; border: none; border-radius: 4px; cursor: pointer;'>Send Email</button>
        </form>
      </td>
    </tr>";
  }
    $data .="</tbody></table>";
echo $data;

}else{
  echo "No posts found.";
}

?>

