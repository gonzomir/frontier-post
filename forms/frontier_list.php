<?php 

$concat= get_option("permalink_structure")?"?":"&";    
//set the permalink for the page itself
$frontier_permalink = get_permalink();

//error_log("pid: ".$tmp_page_id);
//error_log("text: ".$tmp_button_txt);

//Display message
frontier_post_output_msg();

if (frontier_can_add() )
	{
?>
	<form  action="" method="post" name="frontier_list" id="frontier_list" enctype="multipart/form-data" >
	<input type="hidden" name="frontier_return_page_id" id="id" value="<?php echo $tmp_page_id; ?>">
	<input type="hidden" name="frontier_return_text" id="id" value="<?php echo $tmp_button_txt; ?>">
	
	
	<table class="frontier-menu" >
		<tr class="frontier-menu">
			<th class="frontier-menu" >&nbsp;</th>
			<th class="frontier-menu" ><a href='<?php echo frontier_post_add_link($tmp_page_id) ?>'><?php _e("Create New Post", "frontier-post"); ?></a></th>
			<th class="frontier-menu" >&nbsp;</th>
		</tr>
	</table>
	</form>
	</br>
<?php
	
	} // if can_add

if( $user_posts->found_posts > 0 )
	{
	
	$tmp_status_list = get_post_statuses( );

?>

<table class="frontier-list" id="user_post_list">
 
	<thead>
		<tr>
			<th><?php _e("Date", "frontier-post"); ?></th>
			<th><?php _e("Title", "frontier-post"); ?></th>
			<th><?php _e("Status", "frontier-post"); ?></th>
			<th><?php _e("Category", "frontier-post"); ?></th>
			<th><?php _e("Cmt", "frontier-post"); ?></th> <!--number of comments-->
			<th><?php _e("Action", "frontier-post"); ?></th>
		</tr>
	</thead> 
	<tbody>
	<?php 
	while ($user_posts->have_posts()) 
		{
			$user_posts->the_post();
	?>
			<tr>
				<td><?php echo mysql2date('Y-m-d', $post->post_date); ?></td>
				<td>
				<?php if ($post->post_status == "publish")
						{ ?>
						<a href="<?php echo post_permalink($post->ID);?>"><?php echo $post->post_title;?></a>
				<?php	} 
					else
						{
						echo $post->post_title;
						} ?>
						
				</td>
				<td><?php  echo isset($tmp_status_list[$post->post_status]) ? $tmp_status_list[$post->post_status] : $post->post_status;   ;?></td>
				<td><?php  
					// List categories
					$categories=get_the_category( $post->ID );
					$cnt = 0;
					foreach ($categories as $category) :
						$cnt = $cnt+1;
						if ($cnt > 1)
							echo "</br>".$category->cat_name; 
						else
							echo $category->cat_name; 
					endforeach;
				?></td>
				<td><?php  echo $post->comment_count;?></td>
				<td>
					<?php
						if (frontier_can_edit($post) == true)
							{
								?>
									<a href="<?php echo $frontier_permalink; ?><?php echo $concat;?>task=edit&postid=<?php echo $post->ID;?>"><?php _e("Edit", "frontier-post") ?></a>&nbsp;&nbsp;
								<?php
							}
												
						if (frontier_can_delete($post) == true)
							{
								?>
									<a href="<?php echo $frontier_permalink; ?><?php echo $concat;?>task=delete&postid=<?php echo $post->ID;?>" ><?php _e("Delete", "frontier-post") ?></a>
								<?php
							}
						
						if ($post->post_status != "publish")
							{ 
							$tmp_post_link = site_url();
							$tmp_post_link = $tmp_post_link."/?p=".$post->ID."&preview=true"
							?>
							<a href="<?php echo $tmp_post_link;?>" target="_blank"><?php _e("Preview","frontier-post") ?></a>
							<?php		
							} 

					?>
					&nbsp;
				</td>
			</tr>
		<?php 
		} 
		?>
	</tbody>
</table>
<?php

	$pagination = paginate_links( 
			array(
				'base' => add_query_arg( 'pagenum', '%#%' ),
				'format' => '',
				'prev_text' => __( '&laquo;', 'frontier-post' ),
				'next_text' => __( '&raquo;', 'frontier-post' ),
				'total' => $user_posts->max_num_pages,
				'current' => $pagenum
				) 
			);

	if ( $pagination ) 
		{
			echo $pagination;
		}
	echo "</br>".__("Number of posts already created by you: ", "frontier-post").$user_posts->found_posts."</br>";
	}
	
else
	{
		echo "</br><center>";
		_e('Sorry, you do not have any posts (yet)', 'frontier-post');
		echo "</center><br></br>";
	} // end post count
	
//Re-instate $post for the page
wp_reset_postdata();

?>