<?php
require '../src/config.php';
require '../src/db.php';
require './ip_not_allow.php';

?>

<!-- Load all published posts using ajax =============================== -->
<?php
$query = "SELECT * FROM posts WHERE status !='pending'";
$result = mysqli_query($conn,$query);
$data = "";
$data .= '<table class="table" border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Post Image</th>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Date</th>
                    <th>User</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
' ;


if($result && mysqli_num_rows($result)>0){

  while($row=mysqli_fetch_assoc($result)){
    $data .="
    <tr>
      <td><a href='{$local}/view?id={$row['id']}'>{$row['id']}</a></td>
      <td><img src='{$local}/uploads/posts/".htmlspecialchars($row['post_image'])."' alt='Post Image' width='100'></td>
      <td>".htmlspecialchars($row['title'])."</td>
      <td>".htmlspecialchars(substr($row['content'],0,50))."...</td>
      <td>{$row['created_at']}</td>
      <td>{$row['user_id']}</td>
      <td><span class='status published'>published</span></td>
      <td>
        <form method='POST'>
          <input type='hidden' name='post_id' value='{$row['id']}'>
          <select name='status'>
            <option value='1'>Publish</option>
            <option value='0' selected>Pending</option>
          </select>
          <button name='update_post_status'>Update</button>
        </form>
      </td>
    </tr>";
  }
  $data .="</tbody></table>";
echo $data;
}else{
  echo "No published posts found.";
}

?>

